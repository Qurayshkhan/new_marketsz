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

    public function storeCard(StoreUserCardRequest $request)
    {
        try {
            DB::beginTransaction();
            $token = $request->input('token');
            if (!$token || !isset($token['id']) || !isset($token['card'])) {
                throw new \Exception('Invalid card token received.');
            }
            $card = $token['card'];
            $user = $this->userRepository->findById(Auth::id());
            if (!$user) {
                throw new \Exception('Authenticated user not found.');
            }
            if (empty($user->stripe_id)) {
                $customer = $this->stripe->createCustomer([
                    'source' => $token['id'],
                    'email' => $user->email,
                    'name' => $user->name,
                    'metadata' => [
                        'userId' => $user->id,
                    ]
                ]);
                $update = $this->userRepository->updateUser($user->id, ['stripe_id' => $customer->id]);
            } else {
                $this->stripe->createSource($user, $token['id']);
            }
            $data = [
                'user_id' => $user->id,
                'card_id' => $token['id'],
                'exp_month' => data_get($card, 'exp_month'),
                'exp_year' => data_get($card, 'exp_year'),
                'brand' => data_get($card, 'brand'),
                'last4' => data_get($card, 'last4'),
                'card_holder_name' => $request->input('card_holder_name'),
                'address_line1' => $request->input('address_line1'),
                'address_line2' => $request->input('address_line2'),
                'country' => $request->input('country'),
                'state' => $request->input('state'),
                'city' => $request->input('city'),
                'postal_code' => $request->input('postal_code'),
                'country_code' => $request->input('country_code'),
                'phone_number' => $request->input('phone_number'),
            ];
            $this->paymentMethodRepository->storeUserCard($data);
            DB::commit();
            return Redirect::back()->with('alert', 'Card added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
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
