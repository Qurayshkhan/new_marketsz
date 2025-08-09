<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            // 'address' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'year' => 'required|digits:4',
            'day' => 'required|numeric|min:1|max:31',
            'month' => 'required|string',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email',
            'tax_id' => 'nullable|string',
            'password' => ['required', Rules\Password::defaults()],
        ]);


        $suite = $this->generateRandomSuiteNumber();
        $monthNumber = date_parse($request->month)['month'];
        $dateOfBirth = sprintf('%04d-%02d-%02d', $request->year, $monthNumber, $request->day);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'zip_code' => $request->zip_code,
            'state' => $request->state,
            'country' => $request->country,
            'tax_id' => $request->tax_id,
            'suite' => $suite,
            'date_of_birth' => $dateOfBirth,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    public function generateRandomSuiteNumber()
    {
        $prefixes = ['XA', 'XB', 'XC', 'XM'];
        $prefix = $prefixes[array_rand($prefixes)];

        $random = strtoupper(bin2hex(random_bytes(3)));
        $final = $prefix . $random;

        return $final;
    }
}
