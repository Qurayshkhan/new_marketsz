<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserCardRequest;
use App\Http\Requests\UpdateUserCardRequest;
use App\Payments\Stripe;
use App\Repositories\PaymentMethodRepository;
use App\Repositories\UserRepository;
use App\Traits\CommonTrait;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PaymentMethodController extends Controller
{
    use CommonTrait;

    protected $paymentMethodRepository, $userRepository, $stripe;
    public function __construct(PaymentMethodRepository $paymentMethodRepository, UserRepository $userRepository, Stripe $stripe)
    {
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->userRepository = $userRepository;
        $this->stripe = $stripe;
    }
    public function paymentMethods()
    {
        $cards = $this->paymentMethodRepository->getCardsByUser(Auth::id());
        return Inertia::render('Customers/Profile/EditTabs/PaymentMethod', [
            'publishableKey' => env('STRIPE_KEY'),
            'cards' => $cards,
        ]);
    }

    /**
     * Store a new payment method (card) for the authenticated user.
     *
     * This method handles both modern Stripe payment method IDs (from Stripe Elements/Checkout)
     * and legacy Stripe tokens (from Stripe.js). It supports:
     * - Creating new Stripe customers if the user doesn't have one
     * - Attaching payment methods to existing customers
     * - Setting cards as default payment methods
     * - Storing card details in the local database
     * - Supporting multiple cards per user
     *
     * @param StoreUserCardRequest $request The validated request containing card details
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCard(StoreUserCardRequest $request)
    {
        try {
            DB::beginTransaction();

            // Get the authenticated user
            $user = $this->userRepository->findById(Auth::id());
            if (!$user) {
                throw new \Exception('Authenticated user not found.');
            }

            // Get payment method ID or token from request
            $paymentMethodId = $request->input('payment_method_id');
            $token = $request->input('token');

            if (!$paymentMethodId && !$token) {
                throw new \Exception('Payment method ID or token is required.');
            }

            $stripePaymentMethod = null;
            $cardDetails = [];

            // Handle payment method ID (from Stripe Elements or Checkout)
            if ($paymentMethodId) {
                $stripePaymentMethod = $this->stripe->retrievePaymentMethod($paymentMethodId);

                if (isset($stripePaymentMethod['error'])) {
                    throw new \Exception('Failed to retrieve payment method: ' . $stripePaymentMethod['error']);
                }

                $cardDetails = [
                    'exp_month' => $stripePaymentMethod->card->exp_month,
                    'exp_year' => $stripePaymentMethod->card->exp_year,
                    'brand' => $stripePaymentMethod->card->brand,
                    'last4' => $stripePaymentMethod->card->last4,
                ];
            }
            // Handle token (legacy Stripe.js)
            elseif ($token) {
                if (!isset($token['id']) || !isset($token['card'])) {
                    throw new \Exception('Invalid card token received.');
                }

                $cardDetails = [
                    'exp_month' => data_get($token['card'], 'exp_month'),
                    'exp_year' => data_get($token['card'], 'exp_year'),
                    'brand' => data_get($token['card'], 'brand'),
                    'last4' => data_get($token['card'], 'last4'),
                ];

                $paymentMethodId = $token['id'];
            }

            // Check if user has Stripe customer ID
            if (empty($user->stripe_id)) {
                // Create new Stripe customer
                $customerData = [
                    'email' => $user->email,
                    'name' => $user->name,
                    'metadata' => ['userId' => $user->id],
                ];

                // If using payment method ID, attach it to the customer
                if ($paymentMethodId && !$token) {
                    $customerData['payment_method'] = $paymentMethodId;
                    $customerData['invoice_settings'] = [
                        'default_payment_method' => $paymentMethodId
                    ];
                }
                // If using token, use source parameter
                elseif ($token) {
                    $customerData['source'] = $token['id'];
                }

                $customer = $this->stripe->createCustomerWithPaymentMethod($customerData);

                if (isset($customer['error'])) {
                    throw new \Exception('Failed to create Stripe customer: ' . $customer['error']);
                }

                // Update user with Stripe customer ID
                $this->userRepository->update($user->id, ['stripe_id' => $customer->id]);
                $user->stripe_id = $customer->id;

                // If using token, get the attached source
                if ($token) {
                    if (!empty($customer->default_source)) {
                        $stripePaymentMethod = $this->stripe->retrieveSource($customer->id, $customer->default_source);
                        if (isset($stripePaymentMethod['error'])) {
                            throw new \Exception('Failed to retrieve attached card: ' . $stripePaymentMethod['error']);
                        }
                    } else {
                        throw new \Exception('No default card was attached to the customer.');
                    }
                }
            } else {
                // User already has Stripe customer ID, attach payment method
                if ($paymentMethodId && !$token) {
                    $attachedPaymentMethod = $this->stripe->attachPaymentMethod($paymentMethodId, $user->stripe_id);
                    if (isset($attachedPaymentMethod['error'])) {
                        throw new \Exception('Failed to attach payment method: ' . $attachedPaymentMethod['error']);
                    }
                    $stripePaymentMethod = $attachedPaymentMethod;
                } elseif ($token) {
                    $stripePaymentMethod = $this->stripe->createSource($user, $token['id']);
                    if (isset($stripePaymentMethod['error'])) {
                        throw new \Exception('Failed to create source: ' . $stripePaymentMethod['error']);
                    }
                }
            }

            // Check if this should be set as default payment method
            $setAsDefault = $request->input('set_as_default', false);
            if ($setAsDefault && $paymentMethodId && !$token) {
                $this->stripe->setDefaultPaymentMethod($user->stripe_id, $paymentMethodId);
            }

            // Prepare data for database storage
            $data = [
                'user_id' => $user->id,
                'card_id' => $stripePaymentMethod->id,
                'exp_month' => $cardDetails['exp_month'],
                'exp_year' => $cardDetails['exp_year'],
                'brand' => $cardDetails['brand'],
                'last4' => $cardDetails['last4'],
                'card_holder_name' => $request->input('card_holder_name'),
                'address_line1' => $request->input('address_line1'),
                'address_line2' => $request->input('address_line2'),
                'country' => $request->input('country'),
                'state' => $request->input('state'),
                'city' => $request->input('city'),
                'postal_code' => $request->input('postal_code'),
                'country_code' => $request->input('country_code'),
                'phone_number' => $request->input('phone_number'),
                'is_default' => $setAsDefault,
            ];

            // If setting as default, unset other cards as default
            if ($setAsDefault) {
                $this->paymentMethodRepository->setDefaultCard($data['card_id'], $user->id);
            }

            // Store card in database
            $this->paymentMethodRepository->storeUserCard($data);

            DB::commit();

            return Redirect::back()->with('alert', 'Card added successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to store card: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return Redirect::back()->withErrors(['message' => 'Failed to save card: ' . $e->getMessage()]);
        }
    }

    public function setDefault($id)
    {

        try {
            DB::beginTransaction();
            $this->paymentMethodRepository->setDefaultCard($id, Auth::id());
            DB::commit();
            return Redirect::route('customer.payment.paymentMethods')->with('alert', 'Default card updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors(['message' => 'Failed to set default card: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $this->paymentMethodRepository->deleteCard($id, Auth::id());
            DB::commit();
            return Redirect::route('customer.payment.paymentMethods')->with('alert', 'Card deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors(['message' => 'Failed to destroy card: ' . $e->getMessage()]);
        }
    }
    public function updateCard(UpdateUserCardRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $this->paymentMethodRepository->updateUserCard($request->validated(), $id);
            DB::commit();
            return Redirect::route('customer.payment.paymentMethods')->with('alert', 'Card updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors(['message' => 'Failed to update card: ' . $e->getMessage()]);
        }
    }
}
