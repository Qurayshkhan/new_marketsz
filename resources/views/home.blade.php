@extends('layout.master')
@section('title', 'Home')
@section('content')
    <header class="container-fluid text-white bg-[#9e1d22] space-y-12 pt-16 sm:pt-32" style="background: #9E1D22">
        <div class="px-4 sm:px-0 max-w-7xl mx-auto text-center">
            <h1 class="font-bold leading-snug text-4xl sm:text-10xl">Hassle-Free Shipping and Forwarding
            </h1>
            <div class="text-4xl font-bold block mx-auto">From the US to The Caribbean</div>
            <p class="mx-auto mt-3 text-xl sm:text-2xl">
                Shop at any online store using your unique and personal Marketsz USA Address and we quickly
                ship your
                orders to your home or business in the Caribbean.
            </p>
        </div>
        <div class="relative flex flex-col items-center w-full px-4 mx-auto -mb-2 -top-2 sm:px-0">
            <div
                class="flex flex-col flex-wrap items-center justify-center space-y-2 text-lg font-bold text-center sm:flex-row sm:space-y-0 sm:space-x-5 mb-7">
                <span class="flex items-center space-x-1.5 whitespace-nowrap">
                    <div class="flex items-center justify-center w-5 h-5 rounded-full bg-white text-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <span>1-3 Hassle Free Shipping</span>
                </span>
                <span class="flex items-center space-x-1.5 whitespace-nowrap">
                    <div class="flex items-center justify-center w-5 h-5 rounded-full bg-white text-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <span>Combine and Tracking</span>
                </span>
                <span class="flex items-center space-x-1.5 whitespace-nowrap">
                    <div class="flex items-center justify-center w-5 h-5 rounded-full bg-white text-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <span>24/7 Support</span>
                </span>
            </div>
            <template>
                <form action="register.html" method="get" class="relative w-full max-w-sm">
                    <input type="hidden" name="country" value="">
                    <input type="email" name="email" placeholder="someone@mail.com"
                        class="w-full bg-white rounded-full h-14 pl-4 text-gray-900 focus:ring-2 placeholder-gray-500 text-base sm:text-lg border-none"
                        aria-label="Enter your email address">
                    <button
                        class="absolute flex items-center h-10 px-4 font-bold text-white bg-primary rounded-full top-2 right-2 focus:ring-2 focus:ring-black">Get
                        Started</button>
                </form>
            </template>
            <form action="https://www.getrevue.co/profile/flowbite/add_subscriber" method="post" id="revue-form"
                name="revue-form">
                <div class="flex items-center mb-3">
                    <div class="relative w-full mr-3 revue-form-group">
                        <label for="member_email"
                            class="hidden block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Email
                            address</label>
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <!-- <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                                                                                                                                                                                                                 <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                                                                                                                                                                                                                                                 <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                                                                                                                                                                                                                                             </svg> -->
                        </div>
                        <input
                            class="revue-form-field bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-black focus:border-black block w-72 pl-10 p-2.5"
                            placeholder="Your email address..." type="email" name="member[email]" id="member_email"
                            required="">
                    </div>
                    <div class="revue-form-actions">
                        <input type="submit" value="Subscribe"
                            class="cursor-pointer bg-gray-50 focus:ring-2 focus:ring-black font-medium rounded-lg text-rose-900 text-sm px-5 py-2.5 text-center"
                            name="member[subscribe]" id="member_submit">
                    </div>
                </div>
            </form>
            <div class="flex items-center mt-2 text-sm text-center">Shop - Ship - Receive</div>
        </div>
    </header>
    <div class="relative max-w-7xl mx-auto z-10 mt-12">
        <img src="{{ asset('assets') }}/image/home/airplane.svg" alt="EU illustration" class="z-10 -mb-px xl:mx-0 airplane">
        <div class="flex justify-between items-end">
            <img src="{{ asset('assets/image/home/us-with-plane.svg') }}" alt="U.S. illustration" class="city">
            <img src="{{ asset('assets/image/home/island.svg') }}" alt="Island illustration" class="city">
            <img src="{{ asset('assets/image/home/home2.svg') }}" alt="EU illustration" class="city">
        </div>
        <div class="w-40">
            <img src="{{ asset('assets') }}/image/home/ship.svg" alt="Ship" class="z-10 -mb-px xl:mx-0 ship w-full">
        </div>
    </div>
    <img src="{{ asset('assets') }}/image/home/box.svg" alt="floating icon"
        class="absolute h-12 -ml-64 opacity-50 top-28 left-1/2">
    <img src="{{ asset('assets') }}/image/home/box.svg" alt="floating icon"
        class="absolute h-8 mt-2 ml-64 opacity-25 top-32 left-1/2">
    <img src="{{ asset('assets') }}/image/home/box.svg" alt="floating icon"
        class="absolute right-8 h-10 mr-8 opacity-50 top-64">
    <img src="{{ asset('assets') }}/image/home/box.svg" alt="floating icon"
        class="absolute h-8 opacity-25 top-80 left-20">
    <section class="container space-y-12 pt-12 sm:pt-20">
        <header class="max-w-2xl px-4 mx-auto text-center sm:pt-20">
            <h2 class="text-3xl font-bold leading-snug sm:text-4xl">Hassle-Free Worldwide Shopping and Shipping
            </h2>
            <p class="mt-2 text-xl leading-snug sm:text-2xl">Here’s how Marketsz Works</p>
        </header>
        <div class="relative">
            <div class="relative z-10 grid grid-cols-1 gap-12 px-4 md:grid-cols-3 sm:px-0">
                <div class="space-y-5 text-center">
                    <div
                        class="flex items-center justify-center w-10 h-10 mx-auto text-xl font-bold text-white rounded-full bg-primary">
                        1</div>
                    <div><img src="{{ asset('assets') }}/image/home/home.svg" alt=""
                            class="mx-auto shipment-icon">
                    </div>
                    <div class="text-xl leading-tight">Get a <strong>Marketsz USA Shipping Address</strong>
                        instantly when you sign up</div>
                </div>
                <div class="space-y-5 text-center">
                    <div
                        class="flex items-center justify-center w-10 h-10 mx-auto text-xl font-bold text-white rounded-full bg-primary">
                        2</div>
                    <div><img src="{{ asset('assets') }}/image/home/cart.svg" alt=""
                            class="mx-auto shipment-icon">
                    </div>
                    <div class="text-xl leading-tight">Shop at ANY online store using your <strong>Marketsz
                            unique USA Address</strong> as your shipping address</div>
                </div>
                <div class="space-y-5 text-center">
                    <div
                        class="flex items-center justify-center w-10 h-10 mx-auto text-xl font-bold text-white rounded-full bg-primary">
                        3</div>
                    <div><img src="{{ asset('assets') }}/image/home/airplane-icon.svg" alt=""
                            class="mx-auto shipment-icon">
                    </div>
                    <div class="text-xl leading-tight"><strong>Marketsz ships your orders</strong> to you in
                        the
                        Caribbean within 1-3 days</div>
                </div>
            </div>
        </div>
        <template>
            <footer class="mt-10 text-center">
                <a class="px-6 h-11 inline-flex items-center text-lg font-bold bg-primary text-white rounded-full shadow-primary focus:ring-2 focus:ring-black"
                    href="/how-it-works">
                    Check out our shipping information</a>
            </footer>
        </template>
    </section>
    <div class="relative mt-40 sm:mt-60">
        <template>
            <div class="absolute left-0 right-0 z-10 h-10 overflow-hidden bottom-full">
                <svg viewBox="0 0 1200 26" class="absolute bottom-0 left-0 right-0 z-10 w-full -mx-8 -mb-px text-blue-50"
                    style="width:calc(100% + 4rem)">
                    <path
                        d="M1200 0v26H0V0c0 13.807 11.193 25 25 25S50 13.807 50 0c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25s25-11.193 25-25c0 13.807 11.193 25 25 25 13.669 0 24.776-10.97 24.997-24.587L1200 0z"
                        fill="currentColor" fill-rule="evenodd"></path>
                </svg>
            </div>
        </template>
        <div class="absolute left-0 right-0 z-10 h-10 overflow-hidden top-full">
            <svg viewBox="0 0 1201 13" class="absolute top-0 left-0 right-0 z-10 w-full -mx-8 -mt-px text-rose-50"
                style="width:calc(100% + 4rem)">
                <path
                    d="M.719 0h1200v7c-35.7.659-61.366.659-77 0-52.59-2.215-95.155-6.956-131-7-49.257-.06-198.99 7.27-225 7-49.626-.514-88.678-6.988-117-7-88.461-.039-132.35 7.209-176 7-87.18-.417-158.48 1.407-196 0-26.588-.997-104.583 5.985-122 6-13.85.012-30.055-6.014-42-6-21.827.025-46.777 6-67 6-19.149 0-34.816-2-47-6V0z"
                    fill="currentColor" fill-rule="evenodd"></path>
            </svg>
        </div>
        <div class="absolute right-0 z-30 inline w-auto p-3 -mb-16 overflow-hidden bottom-full" id="whale"
            style="transform: translateX(-20px);">
            <div class="animate-wiggle">
                <img src="{{ asset('assets') }}/image/home/cartoon-plane.svg" alt="Whale illustration"
                    class="h-20 md:h-36 lg:h-40 xl:h-48 transform">
                <img src="{{ asset('assets') }}/image/home/ship.svg" alt="Whale illustration"
                    class="h-20 md:h-36 lg:h-40 xl:h-48 transform">
            </div>
        </div>
        <div class="relative z-10 pt-12 pb-12 bg-rose-50 sm:pb-20 sm:pt-0 on-gray">
            <section class="container space-y-12 pt-12 sm:pt-20">
                <header class="max-w-6xl px-4 mx-auto text-center sm:pt-20">
                    <h2 class="text-3xl font-bold leading-snug sm:text-4xl">Why Choose Marketsz?</h2>
                    <p class="mt-2 text-xl leading-snug sm:text-2xl">
                        If you are looking for a hassle-free worldwide shopping and shipping experience, you
                        have come to the
                        right place. Guaranteed and quick deliveries from the most known couriers like DHL,
                        Fedex, Seafraid and AirCargo.</p>
                </header>
                <div class="max-w-6xl px-5 mx-auto leading-relaxed text-xl text-center">
                    <p>Don’t wait in line at a pickup store! Get your orders delivered right at your door.</p>
                    <p>
                        Experience Marketsz! Receive all of your packages within 1-3 days. Track your order, get
                        high quality
                        support throughout the whole process. Zero Headache, fun shopping and shipping
                        experience!
                    </p>
                </div>
                <div class="grid grid-cols-1 gap-12 px-4 md:grid-cols-3 sm:px-0">
                    <div class="text-center bg-gray-50 p-4 rounded-xl">
                        <div class="flex items-center justify-center"><img
                                src="{{ asset('assets') }}/image/home/shipment.svg" alt="" class="max-h-32">
                        </div>
                        <div class="mt-5 text-2xl font-bold leading-tight">Quick and Guaranteed Shopping and
                            Shipping</div>
                        <div class="pt-2 text-lg text-gray-600">Shop from any online store and get your order
                            within 1-3 days</div>
                    </div>
                    <div class="text-center bg-gray-50 p-4 rounded-xl">
                        <div class="flex items-center justify-center"><img
                                src="{{ asset('assets') }}/image/home/monitor.svg" alt="" class="max-h-32">
                        </div>
                        <div class="mt-5 text-2xl font-bold leading-tight">Easy to Use Dashboard</div>
                        <div class="pt-2 text-lg text-gray-600">From your dashboard you can see all of your
                            orders, upload invoices, combine and track your packages</div>
                    </div>
                    <div class="text-center bg-gray-50 p-4 rounded-xl">
                        <div class="flex items-center justify-center"><img
                                src="{{ asset('assets') }}/image/home/call-center.svg" alt="" class="max-h-32">
                        </div>
                        <div class="mt-5 text-2xl font-bold leading-tight">24/7 Live Support</div>
                        <div class="pt-2 text-lg text-gray-600">
                            Need help with anything? Our friendly staff is there to give you a hand!</div>
                    </div>
                </div>
                <div class="max-w-xl mx-auto text-2xl font-bold text-center px-4">How Marketsz Stacks Up
                    Against
                    Competition</div>
                <div class="py-2 mx-4 bg-white rounded-lg sm:px-10 sm:py-5">
                    <table class="w-full">
                        <thead>
                            <tr class="">
                                <th class="hidden sm:block"></th>
                                <th class="sticky top-0 py-4 text-lg bg-white sm:text-xl sm:static">Marketsz
                                </th>
                                <th class="sticky top-0 py-4 text-lg bg-white sm:text-xl sm:static">Competitors
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr rowspan="2"
                                class="w-full text-lg font-bold text-center border-t border-gray-100 sm:hidden">
                                <th colspan="2" class="pt-4">Free Storage</th>
                            </tr>
                            <tr class="sm:border-t sm:border-gray-100">
                                <th class="hidden py-4 text-xl text-left sm:table-cell">Free Storage</th>
                                <td class="w-1/2 px-3 py-4 leading-snug text-center sm:w-auto">
                                    <div
                                        class="flex items-center justify-center w-10 h-10 mx-auto mb-2 text-green-500 bg-green-100 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-check">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>
                                    Up to 120 days
                                </td>
                                <td class="w-1/2 px-3 py-4 leading-snug text-center sm:w-auto">
                                    <div
                                        class="flex items-center justify-center w-10 h-10 mx-auto mb-2 text-red-500 bg-red-200 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </div>
                                    Additional costs
                                </td>
                            </tr>
                            <tr rowspan="2"
                                class="w-full text-lg font-bold text-center border-t border-gray-100 sm:hidden">
                                <th colspan="2" class="pt-4">Combine Service</th>
                            </tr>
                            <tr class="sm:border-t sm:border-gray-100">
                                <th class="hidden py-4 text-xl text-left sm:table-cell">Combine Service</th>
                                <td class="w-1/2 px-3 py-4 leading-snug text-center sm:w-auto">
                                    <div
                                        class="flex items-center justify-center w-10 h-10 mx-auto mb-2 text-green-500 bg-green-100 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-check">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>
                                    Unlimited Combinations
                                </td>
                                <td class="w-1/2 px-3 py-4 leading-snug text-center sm:w-auto">
                                    <div
                                        class="flex items-center justify-center w-10 h-10 mx-auto mb-2 text-red-500 bg-red-200 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </div>
                                    Additional costs
                                </td>
                            </tr>
                            <tr rowspan="2"
                                class="w-full text-lg font-bold text-center border-t border-gray-100 sm:hidden">
                                <th colspan="2" class="pt-4">Express Shipping</th>
                            </tr>
                            <tr class="sm:border-t sm:border-gray-100">
                                <th class="hidden py-4 text-xl text-left sm:table-cell">Express Shipping</th>
                                <td class="w-1/2 px-3 py-4 leading-snug text-center sm:w-auto">
                                    <div
                                        class="flex items-center justify-center w-10 h-10 mx-auto mb-2 text-green-500 bg-green-100 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-check">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>
                                    Get Your package within 1-3 days
                                </td>
                                <td class="w-1/2 px-3 py-4 leading-snug text-center sm:w-auto">
                                    <div
                                        class="flex items-center justify-center w-10 h-10 mx-auto mb-2 text-red-500 bg-red-200 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </div>
                                    Higher rates
                                </td>
                            </tr>
                            <tr rowspan="2"
                                class="w-full text-lg font-bold text-center border-t border-gray-100 sm:hidden">
                                <th colspan="2" class="pt-4">Doorstep Delivery</th>
                            </tr>
                            <tr class="sm:border-t sm:border-gray-100">
                                <th class="hidden py-4 text-xl text-left sm:table-cell">Doorstep Delivery</th>
                                <td class="w-1/2 px-3 py-4 leading-snug text-center sm:w-auto">
                                    <div
                                        class="flex items-center justify-center w-10 h-10 mx-auto mb-2 text-green-500 bg-green-100 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-check">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>Get it all the way at your door
                                </td>
                                <td class="w-1/2 px-3 py-4 leading-snug text-center sm:w-auto">
                                    <div
                                        class="flex items-center justify-center w-10 h-10 mx-auto mb-2 text-red-500 bg-red-200 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </div>Additional costs
                                </td>
                            </tr>
                            <tr rowspan="2"
                                class="w-full text-lg font-bold text-center border-t border-gray-100 sm:hidden">
                                <th colspan="2" class="pt-4">24/7 Support</th>
                            </tr>
                            <tr class="sm:border-t sm:border-gray-100">
                                <th class="hidden py-4 text-xl text-left sm:table-cell">24/7 Support</th>
                                <td class="w-1/2 px-3 py-4 leading-snug text-center sm:w-auto">
                                    <div
                                        class="flex items-center justify-center w-10 h-10 mx-auto mb-2 text-green-500 bg-green-100 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-check">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>Live Chat to smooth out every step of the process
                                </td>
                                <td class="w-1/2 px-3 py-4 leading-snug text-center sm:w-auto">
                                    <div
                                        class="flex items-center justify-center w-10 h-10 mx-auto mb-2 text-red-500 bg-red-200 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </div>No live chat available
                                </td>
                            </tr>
                            <!-- More Services Row for Mobile -->
                            <tr rowspan="2"
                                class="w-full text-lg font-bold text-center border-t border-gray-100 sm:hidden">
                                <th colspan="2" class="pt-4">More Services</th>
                            </tr>
                            <tr class="sm:border-t sm:border-gray-100">
                                <!-- Desktop Left Column -->
                                <th class="hidden py-4 text-xl text-left sm:table-cell">More Services</th>

                                <!-- Marketsz Column -->
                                <td class="w-1/2 px-3 py-4 leading-snug text-center sm:w-auto">
                                    <div
                                        class="flex items-center justify-center w-10 h-10 mx-auto mb-2 text-green-500 bg-green-100 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-check">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>
                                    Air Cargo & Sea Freight
                                </td>

                                <!-- Competitors Column -->
                                <td class="w-1/2 px-3 py-4 leading-snug text-center sm:w-auto">
                                    <div
                                        class="flex items-center justify-center w-10 h-10 mx-auto mb-2 text-red-500 bg-red-200 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </div>
                                    Only Air Freight
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
    <section class="container space-y-12 pt-12 sm:pt-20 hidden">
        <header class="max-w-2xl px-4 mx-auto text-center sm:pt-20">
            <h2 class="text-3xl font-bold leading-snug sm:text-4xl">Where can you ship to?</h2>
            <p class="mt-2 text-xl leading-snug sm:text-2xl">Marketsz can send your order to all islands within
                the Caribbean and growing!</p>
        </header>
        <div class="max-w-xl mx-auto text-2xl font-bold text-center px-4">
            You can shop from any online store using our international forwarding address and get your order
            quickly and guaranteed. Just use your unique shipping USA address that we provide you at
            Marketsz. Marketsz is your #1 online megamall! Giving you the opportunity to shop from anywhere and
            getting your orders right at your door in record time
        </div>
        <div class="flex w-full px-4 overflow-x-auto sm:px-0 hide-scrollbar sm:w-auto sm:block">
            <div
                class="grid flex-1 grid-flow-col gap-2 mx-auto sm:grid-flow-row sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">
                <div class="p-6 bg-white border-2 border-gray-200 w-80 sm:w-auto rounded-xl">
                    <div class="relative flex items-center justify-center h-52">
                        <div
                            style="display:block;overflow:hidden;position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;margin:0">
                            <noscript>
                                <img srcSet="/_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Fiphone-11-pro-with-screen-protecter-case%2Fphonecase.jpg&amp;w=640&amp;q=75 640w, /_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Fiphone-11-pro-with-screen-protecter-case%2Fphonecase.jpg&amp;w=750&amp;q=75 750w, /_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Fiphone-11-pro-with-screen-protecter-case%2Fphonecase.jpg&amp;w=828&amp;q=75 828w, /_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Fiphone-11-pro-with-screen-protecter-case%2Fphonecase.jpg&amp;w=1080&amp;q=75 1080w, /_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Fiphone-11-pro-with-screen-protecter-case%2Fphonecase.jpg&amp;w=1200&amp;q=75 1200w, /_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Fiphone-11-pro-with-screen-protecter-case%2Fphonecase.jpg&amp;w=1920&amp;q=75 1920w, /_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Fiphone-11-pro-with-screen-protecter-case%2Fphonecase.jpg&amp;w=2048&amp;q=75 2048w, /_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Fiphone-11-pro-with-screen-protecter-case%2Fphonecase.jpg&amp;w=3840&amp;q=75 3840w"
                                    src="images/generic/photos/iphone-11-pro-with-screen-protecter-case/phonecase.jpg"
                                    decoding="async"
                                    style="position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;padding:0;border:none;margin:auto;display:block;width:0;height:0;min-width:100%;max-width:100%;min-height:100%;max-height:100%"
                                    class="absolute inset-0 object-contain" alt="image" />
                            </noscript>
                            <img alt=""
                                src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                decoding="async" class="absolute inset-0 object-contain"
                                style="position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;padding:0;border:none;margin:auto;display:block;width:0;height:0;min-width:100%;max-width:100%;min-height:100%;max-height:100%">
                        </div>
                    </div>
                    <div class="flex items-center h-20 mt-4 text-lg font-bold leading-tight text-center">iPhone
                        11 Pro with Screen Protecter + Case</div>
                    <div class=" ">
                        <div class="flex justify-between px-2 py-1 bg-gray-100 rounded">
                            <div>Shipping</div>
                            <div>29.36 US$</div>
                        </div>
                        <div class="flex justify-between px-2 py-1 bg-white rounded">
                            <div>Handling</div>
                            <div>10.00 US$</div>
                        </div>
                        <div class="flex justify-between px-2 py-1 bg-gray-100 rounded">
                            <div>Insurance</div>
                            <div>FREE</div>
                        </div>
                        <div class="flex justify-between px-2 py-1 font-bold bg-white rounded">
                            <div>Total</div>
                            <div>39.36 US$</div>
                        </div>
                        <div class="mt-2 -mb-2 text-sm text-center text-gray-600">Excluding local import duties
                        </div>
                    </div>
                </div>
                <div class="p-6 bg-white border-2 border-gray-200 w-80 sm:w-auto rounded-xl">
                    <div class="relative flex items-center justify-center h-52">
                        <div
                            style="display:block;overflow:hidden;position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;margin:0">
                            <noscript><img alt=""
                                    srcSet="/_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Fassorted-clothing-from-3-online-stores%2Fproduct-example-3.jpg&amp;w=640&amp;q=75 640w, /_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Fassorted-clothing-from-3-online-stores%2Fproduct-example-3.jpg&amp;w=750&amp;q=75 750w, /_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Fassorted-clothing-from-3-online-stores%2Fproduct-example-3.jpg&amp;w=828&amp;q=75 828w, /_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Fassorted-clothing-from-3-online-stores%2Fproduct-example-3.jpg&amp;w=1080&amp;q=75 1080w, /_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Fassorted-clothing-from-3-online-stores%2Fproduct-example-3.jpg&amp;w=1200&amp;q=75 1200w, /_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Fassorted-clothing-from-3-online-stores%2Fproduct-example-3.jpg&amp;w=1920&amp;q=75 1920w, /_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Fassorted-clothing-from-3-online-stores%2Fproduct-example-3.jpg&amp;w=2048&amp;q=75 2048w, /_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Fassorted-clothing-from-3-online-stores%2Fproduct-example-3.jpg&amp;w=3840&amp;q=75 3840w"
                                    src="images/generic/photos/assorted-clothing-from-3-online-stores/product-example-3.jpg"
                                    decoding="async"
                                    style="position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;padding:0;border:none;margin:auto;display:block;width:0;height:0;min-width:100%;max-width:100%;min-height:100%;max-height:100%"
                                    class="absolute inset-0 object-contain" /></noscript><img alt=""
                                src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                decoding="async" class="absolute inset-0 object-contain"
                                style="position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;padding:0;border:none;margin:auto;display:block;width:0;height:0;min-width:100%;max-width:100%;min-height:100%;max-height:100%">
                        </div>
                    </div>
                    <div class="flex items-center h-20 mt-4 text-lg font-bold leading-tight text-center">
                        Assorted Clothing from 3 different Online Stores</div>
                    <div class=" ">
                        <div class="flex justify-between px-2 py-1 bg-gray-100 rounded">
                            <div>Shipping</div>
                            <div>82.43 US$</div>
                        </div>
                        <div class="flex justify-between px-2 py-1 bg-white rounded">
                            <div>Handling</div>
                            <div>10.00 US$</div>
                        </div>
                        <div class="flex justify-between px-2 py-1 bg-gray-100 rounded">
                            <div>Insurance</div>
                            <div>FREE</div>
                        </div>
                        <div class="flex justify-between px-2 py-1 font-bold bg-white rounded">
                            <div>Total</div>
                            <div>92.43 US$</div>
                        </div>
                        <div class="mt-2 -mb-2 text-sm text-center text-gray-600">Excluding local import duties
                        </div>
                    </div>
                </div>
                <div class="p-6 bg-white border-2 border-gray-200 w-80 sm:w-auto rounded-xl">
                    <div class="relative flex items-center justify-center h-52">
                        <div
                            style="display:block;overflow:hidden;position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;margin:0">
                            <noscript><img alt=""
                                    srcSet="/_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Ffuel-filter-ignition-wire-set-bando-premium-quality-belt%2Fproduct-example-2.jpg&amp;w=640&amp;q=75 640w, /_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Ffuel-filter-ignition-wire-set-bando-premium-quality-belt%2Fproduct-example-2.jpg&amp;w=750&amp;q=75 750w, /_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Ffuel-filter-ignition-wire-set-bando-premium-quality-belt%2Fproduct-example-2.jpg&amp;w=828&amp;q=75 828w, /_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Ffuel-filter-ignition-wire-set-bando-premium-quality-belt%2Fproduct-example-2.jpg&amp;w=1080&amp;q=75 1080w, /_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Ffuel-filter-ignition-wire-set-bando-premium-quality-belt%2Fproduct-example-2.jpg&amp;w=1200&amp;q=75 1200w, /_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Ffuel-filter-ignition-wire-set-bando-premium-quality-belt%2Fproduct-example-2.jpg&amp;w=1920&amp;q=75 1920w, /_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Ffuel-filter-ignition-wire-set-bando-premium-quality-belt%2Fproduct-example-2.jpg&amp;w=2048&amp;q=75 2048w, /_next/image?url=https%3A%2F%2Fcms.mymalls.com%2Fassets%2Fimages%2Fgeneric%2Fphotos%2Ffuel-filter-ignition-wire-set-bando-premium-quality-belt%2Fproduct-example-2.jpg&amp;w=3840&amp;q=75 3840w"
                                    src="images/generic/photos/fuel-filter-ignition-wire-set-bando-premium-quality-belt/product-example-2.jpg"
                                    decoding="async"
                                    style="position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;padding:0;border:none;margin:auto;display:block;width:0;height:0;min-width:100%;max-width:100%;min-height:100%;max-height:100%"
                                    class="absolute inset-0 object-contain" /></noscript><img alt=""
                                src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                decoding="async" class="absolute inset-0 object-contain"
                                style="position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;padding:0;border:none;margin:auto;display:block;width:0;height:0;min-width:100%;max-width:100%;min-height:100%;max-height:100%">
                        </div>
                    </div>
                    <div class="flex items-center h-20 mt-4 text-lg font-bold leading-tight text-center">Fuel
                        Filter + Ignition Wire Set + Bando Premium Quality Belt</div>
                    <div class=" ">
                        <div class="flex justify-between px-2 py-1 bg-gray-100 rounded">
                            <div>Shipping</div>
                            <div>41.60 US$</div>
                        </div>
                        <div class="flex justify-between px-2 py-1 bg-white rounded">
                            <div>Handling</div>
                            <div>10.00 US$</div>
                        </div>
                        <div class="flex justify-between px-2 py-1 bg-gray-100 rounded">
                            <div>Insurance</div>
                            <div>FREE</div>
                        </div>
                        <div class="flex justify-between px-2 py-1 font-bold bg-white rounded">
                            <div>Total</div>
                            <div>51.60 US$</div>
                        </div>
                        <div class="mt-2 -mb-2 text-sm text-center text-gray-600">Excluding local import duties
                        </div>
                    </div>
                </div>
                <div class="w-3 sm:hidden"></div>
            </div>
        </div>
        <div class="px-4 space-y-5 text-center">
            <!-- <div class="text-lg font-bold">There are some items that we’re not allowed to ship, for example:</div> -->
            <div>
                <div class="flex flex-wrap items-center justify-center -mt-5 -ml-8 text-lg">
                    <div class="flex items-center mt-5 ml-8 space-x-2">
                        <div
                            class="flex items-center justify-center w-6 h-6 text-3xl font-bold text-red-500 bg-red-200 rounded-full">
                            ×</div>
                        <div>Alcohol</div>
                    </div>
                    <div class="flex items-center mt-5 ml-8 space-x-2">
                        <div
                            class="flex items-center justify-center w-6 h-6 text-3xl font-bold text-red-500 bg-red-200 rounded-full">
                            ×</div>
                        <div>Lithium batteries</div>
                    </div>
                    <div class="flex items-center mt-5 ml-8 space-x-2">
                        <div
                            class="flex items-center justify-center w-6 h-6 text-3xl font-bold text-red-500 bg-red-200 rounded-full">
                            ×</div>
                        <div>Nicotine</div>
                    </div>
                    <div class="flex items-center mt-5 ml-8 space-x-2">
                        <div
                            class="flex items-center justify-center w-6 h-6 text-3xl font-bold text-red-500 bg-red-200 rounded-full">
                            ×</div>
                        <div>Perfumes</div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="mt-10 text-center">
            <a class="px-6 h-11 inline-flex items-center text-lg font-bold bg-red text-white rounded-full"
                href="/prohibited-items">
                see the list of all prohibited items</a>
        </footer>
    </section>
    <section>
        <div class="relative px-4 pb-24 mt-24 bg-rose-50 sm:pb-40 sm:mt-40 sm:px-0">
            <section class="container space-y-12 pt-12 sm:pt-20" id="shops">
                <header class="max-w-2xl px-4 mx-auto text-center sm:pt-20">
                    <h2 class="text-3xl font-bold leading-snug sm:text-4xl">Where can I shop?</h2>
                    <p class="mt-2 text-xl leading-snug sm:text-2xl">Marketsz works with all online stores that
                        ship to the US. When shopping online simply use your personal US
                        Marketsz address as your shipping address.</p>
                </header>
                <div class="max-w-xl mx-auto text-2xl font-bold text-center px-4">These are some of our
                    members'
                    favorite stores!</div>
                <div class="-mx-4">
                    <div class="flex w-full px-4 overflow-x-auto hide-scrollbar sm:w-auto sm:block">
                        <div
                            class="grid flex-1 grid-flow-row grid-cols-2 gap-2 sm:grid-flow-row auto-cols-max sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/aliexpress">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/AliExpress-logo.png"
                                    decoding="async" class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/amazon">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/Amazon_color.webp"
                                    decoding="async" class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/apple">
                                <img alt="stores"
                                    src="{{ asset('assets') }}/image/home/Apple-logo_2021-03-11-001728.png"
                                    decoding="async" class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/bath-body-works">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/Bath-and-body-works.png"
                                    decoding="async" class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/coach">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/Coach.png" decoding="async"
                                    class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/ebay">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/Ebay-logo.png"
                                    decoding="async" class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/fashion-nova">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/Fashion-nova.jpg"
                                    decoding="async" class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/forever">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/Forever_color.webp"
                                    decoding="async" class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/gap">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/Gap-logo.png" decoding="async"
                                    class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/h-m">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/HM.png" decoding="async"
                                    class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/home-depot">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/Home-Depot.png"
                                    decoding="async" class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/jc-penney">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/JCPenney_color.webp"
                                    decoding="async" class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/macys">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/macys_color.webp"
                                    decoding="async" class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/nike">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/Nike-logo.png"
                                    decoding="async" class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/old-navy">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/Old-navy.png" decoding="async"
                                    class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/pretty-little-thing">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/Pretty-little-thing.png"
                                    decoding="async" class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/shein">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/Shein.png" decoding="async"
                                    class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/tory-burch">
                                <img alt="stores"
                                    src="{{ asset('assets') }}/image/home/Tory-Burch_2021-03-10-223425.png"
                                    decoding="async" class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/victorias-secret">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/VS-logo.webp" decoding="async"
                                    class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/walmart">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/Walmart.webp" decoding="async"
                                    class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/zaful">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/Zaful.webp" decoding="async"
                                    class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/zappos">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/Zappos.webp" decoding="async"
                                    class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/zara">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/Zara.webp " decoding="async"
                                    class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/zulily">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/Zulily.webp" decoding="async"
                                    class="stores" />
                            </a>
                            <a class="relative flex justify-center col-span-1 py-4 md:py-8 px-4 bg-white rounded-tl-3xl rounded-br-3xl snap-center"
                                href="/shops/vans">
                                <img alt="stores" src="{{ asset('assets') }}/image/home/Vans.webp" decoding="async"
                                    class="stores" />
                            </a>
                            <div class="w-4 sm:hidden"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
    <section class="container space-y-12 pt-12 sm:pt-20">
        <header class="max-w-2xl px-4 mx-auto text-center sm:pt-20">
            <h2 class="text-3xl font-bold leading-snug sm:text-4xl">Ready to start shipping with Marketsz?</h2>
            <p class="mt-2 text-xl leading-snug sm:text-2xl">Sign up now and get your instant forwarding
                address
            </p>
        </header>
        <div class="relative flex items-center justify-center">
            <a href="{{ route('register') }}"
                class="flex items-center px-6 py-2 text-xl font-bold text-white bg-primary shadow-primary rounded-full focus:ring-2 focus:ring-black">Get
                started
                now</a>
        </div>
    </section>
    <div class="relative mt-12 overflow-hidden sm:mt-28 pt-60">
        <img src="{{ asset('assets') }}/image/home/box-gray.svg" alt="box"
            class="absolute top-0 w-auto h-10 ml-24 text-blue-100 opacity-75 left-1/2">
        <img src="{{ asset('assets') }}/image/home/box-gray.svg" alt="box"
            class="absolute w-auto h-10 text-blue-100 opacity-50 top-64 left-1/2">
        <img src="{{ asset('assets') }}/image/home/box-gray.svg" alt="box"
            class="absolute w-auto h-12 text-blue-100 opacity-75 top-12 right-8">
        <img src="{{ asset('assets') }}/image/home/box-gray.svg" alt="box"
            class="absolute w-auto h-8 text-blue-100 opacity-50 top-8 left-8">
        <img src="{{ asset('assets') }}/image/home/box-gray.svg" alt="box"
            class="absolute w-auto h-6 -ml-48 text-blue-100 opacity-50 top-24 left-1/2">
        <img src="{{ asset('assets') }}/image/home/box-gray.svg" alt="box"
            class="absolute left-0 w-auto h-8 text-blue-100 opacity-50 top-80 ml-80">
        <div
            class="absolute w-32 h-32 p-3 border-2 border-primary border-dashed rounded-full opacity-50 top-4 right-64 animate-spin-slow">
            <div class="w-full h-full bg-primary rounded-full"></div>
        </div>
        <div class="absolute left-0 z-0 w-auto top-12 h-72 flamingo" style="transform: translateX(20px);">
            <div class="animate-float">
                <img src="{{ asset('assets') }}/image/ballon.svg" alt="Flamingo illustration"
                    class="h-48 sm:52 transform">
                <img src="{{ asset('assets') }}/image/home/ship.svg" alt="ship" class="h-48 sm:52 transform">
            </div>
        </div>
        <section class="container space-y-12 pt-12 sm:pt-20">
            @include('includes.calculator-include')
        </section>
    </div>
    <section class="container space-y-12 pt-12 sm:pt-20">
        <header class="max-w-2xl px-4 mx-auto text-center sm:pt-20">
            <h2 class="text-3xl font-bold leading-snug sm:text-4xl">Free benefits you will love!</h2>
            <p class="mt-2 text-xl leading-snug sm:text-2xl">
                We go the extra mile to give you a seamless shipping experience, hassle free!</p>
        </header>
        <div class="grid gap-8 px-4 sm:grid-cols-2 md:grid-cols-3 sm:px-0">
            <div class="flex flex-col items-center">
                <div class="text-4xl p-4"><i class="fas fa-shipping-fast"></i></div>
                <div class="pl-4 text-lg font-bold leading-tight">Quick Delivery</div>
                <div class="pt-1 pl-4 leading-snug text-center">
                    Get your packages straight at your door of your home or business. You shop and we ship so
                    that you get
                    your packages in 1-3 days!</div>
            </div>
            <div class="flex flex-col items-center">
                <div class="text-4xl p-4"><i class="fas fa-search-location    "></i></div>
                <div class="pl-4 text-lg font-bold leading-tight">Monitor and Track</div>
                <div class="pt-1 pl-4 leading-snug text-center">
                    Get pictures of all of your items and track exactly where they are and how long it’s going
                    to take to get
                    them right at your address.
                </div>
            </div>
            <div class="flex flex-col items-center">
                <div class="text-4xl p-4"><i class="fa fa-database" aria-hidden="true"></i></div>
                <div class="pl-4 text-lg font-bold leading-tight">30 Days of FREE storage</div>
                <div class="pt-1 pl-4 leading-snug text-center">
                    Want to wait before shipping? No problem! We will store your products for you for 30 days
                    completely
                    free of charge.</div>
            </div>
        </div>
    </section>
    <div class="mt-auto">
        <div class="relative pt-8 pb-40 mt-20 bg-rose-50 sm:pt-0 sm:mt-40">
            <img alt="Seahorse illustration" src="{{ asset('assets') }}/image/ballon.svg"
                class="absolute hidden w-auto h-40 md:-left-36 lg:left-2 xl:left-24 top-48 animate-float md:block">
            <img alt="Fish illustration" src="{{ asset('assets') }}/image/home/cartoon-plane.svg"
                class="absolute w-auto h-20 -mr-20 transform -translate-x-1/2 sm:h-24 bottom-8 right-1/2 md:-mr-0 md:right-2 md:top-1/2 md:-translate-y-1/2 lg:right-24 xl:right-32 animate-wiggle">
            <img alt="Fish illustration" src="{{ asset('assets') }}/image/home/ship.svg"
                class="animate-wiggle h-20 absolute right-[50%] left-[50%]">
            <section class="container space-y-12 pt-12 sm:pt-20">
                <header class="max-w-2xl px-4 mx-auto text-center sm:pt-20">
                    <h2 class="text-3xl font-bold leading-snug sm:text-4xl">It’s time to shop till you drop
                        and
                        get your orders in record time!</h2>
                    <p class="mt-2 text-xl leading-snug sm:text-2xl">It’s never been easier to shop from any
                        online store and get your packages delivered right at your door</p>
                </header>
                <div class="relative flex flex-col items-center w-full px-4 mx-auto -mb-2 -top-2 sm:px-0">
                    <div
                        class="flex flex-col flex-wrap items-center justify-center space-y-2 text-lg font-bold text-center sm:flex-row sm:space-y-0 sm:space-x-5 mb-7">
                        <span class="flex items-center space-x-1.5 whitespace-nowrap">
                            <div class="flex items-center justify-center w-5 h-5 rounded-full bg-primary text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div><span>1-3 Day Shipping</span>
                        </span>
                        <span class="flex items-center space-x-1.5 whitespace-nowrap">
                            <div class="flex items-center justify-center w-5 h-5 rounded-full bg-primary text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span>Combine &amp; Track</span>
                        </span>
                        <span class="flex items-center space-x-1.5 whitespace-nowrap">
                            <div class="flex items-center justify-center w-5 h-5 rounded-full bg-primary text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span>Live Support</span>
                        </span>
                    </div>
                    <template>
                        <form action="register.html" method="get" class="relative w-full max-w-sm">
                            <input type="hidden" name="country" value="">
                            <input type="email" name="email" placeholder="someone@mail.com"
                                class="w-full bg-white rounded-full h-14 pl-4 text-gray-900 focus:ring-2 placeholder-gray-500 text-base sm:text-lg border border-gray-300"
                                aria-label="Enter your email address">
                            <button
                                class="absolute flex items-center h-10 px-4 font-bold text-white bg-primary rounded-full top-2 right-2">Get
                                started</button>
                        </form>
                    </template>

                    <form action="https://www.getrevue.co/profile/flowbite/add_subscriber" method="post" id="revue-form"
                        name="revue-form">
                        <div class="flex items-center mb-3">
                            <div class="relative w-full mr-3 revue-form-group">
                                <label for="member_email"
                                    class="hidden block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Email
                                    address</label>
                                <input
                                    class="revue-form-field bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-black focus:border-black block w-72 pl-10 p-2.5"
                                    placeholder="Your email address..." type="email" name="member[email]"
                                    id="member_email" required="">
                            </div>
                            <div class="">
                                <button type="submit"
                                    class="bg-primary focus:ring-2 focus:ring-black font-medium rounded-lg text-white text-sm px-5 py-2.5 text-center"
                                    name="" id="" style="background-color:#9E1D22">Subscribe</button>
                            </div>
                        </div>
                    </form>
                    <div class="flex items-center mt-2 text-sm text-center">Quick • Simple • Free</div>
                </div>
            </section>
        </div>
    </div>
@endsection
