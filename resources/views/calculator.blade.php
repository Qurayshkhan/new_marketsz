@extends('layout.master')
@section('title', 'Calculator')
@section('content')
    <section class="container space-y-12">
        <header class="max-w-2xl px-4 mx-auto text-center sm:pt-20">
            <h2 class="text-3xl font-bold leading-snug sm:text-4xl">How much does it cost?</h2>
            <p class="mt-2 text-xl leading-snug sm:text-2xl">Use our calculator to estimate exactly how much it would cost
                you to ship.</p>
        </header>
        <div class="grid max-w-4xl grid-cols-2 gap-2 px-4 mx-auto sm:px-0">
            <div class="col-span-2 p-8 bg-rose-50 md:col-span-2 rounded-xl w-full">
                <div class="text-2xl font-bold">1. Where are you shopping?</div>
                <div class="text-gray-600"></div>
                <div class="flex flex-wrap pt-4 -mt-2 -ml-2">
                    <button
                        class="mt-2 ml-2 flex-1 text-lg rounded-lg h-14 px-4 flex items-center justify-center whitespace-nowrap border-2 border-black bg-gray-50">
                        <div><img draggable="false" class="flex-shrink-0 w-6 mr-2" alt="🇺🇸"
                                src="assets/image/home/1f1fa-1f1f8.png"></div>
                        <span>United States</span>
                    </button>
                    <button
                        class="mt-2 flex ml-2 flex-1 text-lg rounded-lg h-14 px-4 items-center justify-center whitespace-nowrap border-2 border-gray-300 bg-white">
                        <div><img draggable="false" class="flex-shrink-0 w-6 mr-2" alt="🇪🇺"
                                src="assets/image/home/1f1ea-1f1fa.png"></div>
                        <span>Europe</span>
                    </button>
                </div>
            </div>
            <div class="col-span-2 p-8 bg-rose-50 md:col-span-2 rounded-xl">
                <div class="text-2xl font-bold">2. Where should we send your package?</div>
                <div class="text-gray-600">We forward to the Caribbean, America, and Canada.</div>
                <div class="relative mt-4">
                    <select
                        class="flex items-center justify-between w-full px-4 text-lg border-2 rounded-lg border-black bg-gray-50 h-14"
                        id="headlessui-listbox" aria-labelledby="headlessui-listbox-button-5" role="listbox" tabindex="0"
                        aria-activedescendant="headlessui-listbox-option-64">
                        <option value="">🇨🇼Curaçao</option>
                        <option id="headlessui-listbox-option-50" role="option" tabindex="-1">Anguilla</option>
                        <option id="headlessui-listbox-option-51" role="option" tabindex="-1">Antigua and Barbuda</option>
                        <option id="headlessui-listbox-option-52" role="option" tabindex="-1">Argentina</option>
                        <option id="headlessui-listbox-option-53" role="option" tabindex="-1">Aruba</option>
                        <option id="headlessui-listbox-option-54" role="option" tabindex="-1">Bahamas</option>
                        <option id="headlessui-listbox-option-55" role="option" tabindex="-1">Barbados</option>
                        <option id="headlessui-listbox-option-56" role="option" tabindex="-1">Belize</option>
                        <option id="headlessui-listbox-option-57" role="option" tabindex="-1">Bermuda</option>
                        <option id="headlessui-listbox-option-58" role="option" tabindex="-1">Bonaire</option>
                        <option id="headlessui-listbox-option-59" role="option" tabindex="-1">British Guyana</option>
                        <option id="headlessui-listbox-option-60" role="option" tabindex="-1">Canada</option>
                        <option id="headlessui-listbox-option-61" role="option" tabindex="-1">Cayman Islands</option>
                        <option id="headlessui-listbox-option-62" role="option" tabindex="-1">Chile</option>
                        <option id="headlessui-listbox-option-63" role="option" tabindex="-1">Colombia</option>
                        <option id="headlessui-listbox-option-64" role="option" tabindex="-1" aria-selected="true">Curaçao
                        </option>
                        <option id="headlessui-listbox-option-65" role="option" tabindex="-1">Dominica</option>
                        <option id="headlessui-listbox-option-66" role="option" tabindex="-1">Dominican Republic</option>
                        <option id="headlessui-listbox-option-67" role="option" tabindex="-1">French Guyana</option>
                        <option id="headlessui-listbox-option-68" role="option" tabindex="-1">Grenada</option>
                        <option id="headlessui-listbox-option-69" role="option" tabindex="-1">Guadeloupe</option>
                        <option id="headlessui-listbox-option-70" role="option" tabindex="-1">Guatemala</option>
                        <option id="headlessui-listbox-option-71" role="option" tabindex="-1">Haiti</option>
                        <option id="headlessui-listbox-option-72" role="option" tabindex="-1">Jamaica</option>
                        <option id="headlessui-listbox-option-73" role="option" tabindex="-1">Martinique</option>
                        <option id="headlessui-listbox-option-74" role="option" tabindex="-1">Mexico</option>
                        <option id="headlessui-listbox-option-75" role="option" tabindex="-1">Montserrat</option>
                        <option id="headlessui-listbox-option-76" role="option" tabindex="-1">Nevis</option>
                        <option id="headlessui-listbox-option-77" role="option" tabindex="-1">Panama</option>
                        <option id="headlessui-listbox-option-78" role="option" tabindex="-1">Puerto Rico</option>
                        <option id="headlessui-listbox-option-79" role="option" tabindex="-1">Saba</option>
                        <option id="headlessui-listbox-option-80" role="option" tabindex="-1">Saint Barthelemy</option>
                        <option id="headlessui-listbox-option-81" role="option" tabindex="-1">Saint Kitts and Nevis
                        </option>
                        <option id="headlessui-listbox-option-82" role="option" tabindex="-1">Saint Lucia</option>
                        <option id="headlessui-listbox-option-83" role="option" tabindex="-1">Saint Martin</option>
                        <option id="headlessui-listbox-option-84" role="option" tabindex="-1">Saint Vincent And The
                            Grenadines</option>
                        <option id="headlessui-listbox-option-85" role="option" tabindex="-1">Sint Eustatius</option>
                        <option id="headlessui-listbox-option-86" role="option" tabindex="-1">Sint Maarten</option>
                        <option id="headlessui-listbox-option-87" role="option" tabindex="-1">Suriname</option>
                        <option id="headlessui-listbox-option-88" role="option" tabindex="-1">Trinidad and Tobago
                        </option>
                        <option id="headlessui-listbox-option-89" role="option" tabindex="-1">Turks and Caicos Islands
                        </option>
                        <option id="headlessui-listbox-option-90" role="option" tabindex="-1">Uruguay</option>
                        <option id="headlessui-listbox-option-91" role="option" tabindex="-1">Virgin Islands, British
                        </option>
                        <option id="headlessui-listbox-option-92" role="option" tabindex="-1">Virgin Islands, U.S.
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-span-2 p-6 bg-rose-50 sm:p-8 rounded-xl">
                <div class="md:flex items-center">
                    <div>
                        <div class="text-2xl font-bold">What are you ordering?</div>
                        <div class="text-gray-600">Enter your dimensions for a more accurate quote or choose a product.
                        </div>
                    </div>
                    <div class="flex flex-wrap justify-center gap-2 mt-4">
                        <button class="max-w-12 rounded-lg border-2 p-4 text-center border-gray-300 bg-white flex gap-3">
                            <div><img draggable="false" class="h-12 mx-auto" alt="📱"
                                    src="assets/image/home/1f4f1.png"></div>
                            <div class="mt-3 leading-tight">Phone / Tablet</div>
                        </button>
                        <button class="max-w-12 rounded-lg border-2 p-4 text-center border-gray-300 bg-white flex gap-3">
                            <div><img draggable="false" class="h-12 mx-auto" alt="💻"
                                    src="assets/image/home/1f4bb.png"></div>
                            <div class="mt-3 leading-tight">Laptop</div>
                        </button>
                        <button class="max-w-12 rounded-lg border-2 p-4 text-center border-gray-300 bg-white flex gap-3">
                            <div><img draggable="false" class="h-12 mx-auto" alt="🥾"
                                    src="assets/image/home/1f97e.png"></div>
                            <div class="mt-3 leading-tight">Shoes</div>
                        </button>
                        <button class="max-w-12 rounded-lg border-2 p-4 text-center border-gray-300 bg-white flex gap-3">
                            <div><img draggable="false" class="h-12 mx-auto" alt="👕"
                                    src="assets/image/home/1f455.png"></div>
                            <div class="mt-3 leading-tight">Clothes</div>
                        </button>
                        <button class="max-w-12 rounded-lg border-2 p-4 text-center border-gray-300 bg-white flex gap-3">
                            <div><img draggable="false" class="h-12 mx-auto" alt="💄"
                                    src="assets/image/home/1f484.png"></div>
                            <div class="mt-3 leading-tight">Beauty products</div>
                        </button>
                        <button class="max-w-12 rounded-lg border-2 p-4 text-center border-black bg-gray-50 flex gap-3">
                            <div><img draggable="false" class="h-12 mx-auto" alt="👀"
                                    src="assets/image/home/1f440.png"></div>
                            <div class="mt-3 leading-tight">Other</div>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-4 mt-4">
                    <div class="col-span-12 lg:col-span-6"><label class="block">Dimensions:</label>
                        <div class="flex mt-1">
                            <input type="text" name="length" value=""
                                class="flex-auto w-full -mr-px border-gray-400 rounded-l" placeholder="Length"
                                aria-label="Length">
                            <input type="text" name="width" value="" class="flex-auto w-full border-gray-400"
                                placeholder="Width" aria-label="Width">
                            <input type="text" name="height" value=""
                                class="flex-auto w-full -ml-px border-gray-400" placeholder="Height" aria-label="Height">
                            <select class="flex-none w-20 -ml-px border-gray-400 rounded-r" aria-label="Size unit">
                                <option selected="" value="in">in</option>
                                <option value="cm">cm</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 lg:col-span-3"><label for="costCalculatorWeight"
                            class="block">Weight:</label>
                        <div class="flex mt-1"><input type="text" name="weight" id="costCalculatorWeight"
                                value="" placeholder="0.00"
                                class="flex-auto w-full border-gray-400 rounded-l"><select
                                class="flex-none w-20 -ml-px border-gray-400 rounded-r" aria-label="Weight unit">
                                <option selected="" value="lb">lb</option>
                                <option value="kg">kg</option>
                            </select></div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 lg:col-span-3"><label for="costCalculatorValue"
                            class="block">Value:</label>
                        <div class="flex mt-1"><input type="text" name="value" id="costCalculatorValue"
                                value="" placeholder="0.00"
                                class="flex-auto w-full border-gray-400 rounded-l"><select
                                class="flex-none w-24 -ml-px border-gray-400 rounded-r" aria-label="Currency">
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                            </select></div>
                    </div>
                    <div class="flex justify-center col-span-12 mt-2"><button
                            class="inline-flex items-center h-10 px-4 font-bold text-white rounded-full bg-primary shadow-primary">Get
                            price
                            estimate</button></div>
                </div>
            </div>
        </div>
    </section>
    <template>
        <section class="container space-y-12 pt-12 sm:pt-20">
            <header class="max-w-2xl px-4 mx-auto text-center sm:pt-20">
                <h2 class="text-3xl font-bold leading-snug sm:text-4xl">Transparent pricing</h2>
                <p class="mt-2 text-xl leading-snug sm:text-2xl">Find out about all costs involved.</p>
            </header>
            <div class="max-w-md p-5 mx-5 bg-white border shadow-lg sm:p-10 sm:mx-auto rounded-xl">
                <div class="text-xl font-bold text-center sm:text-2xl">Sample invoice</div>
                <div class="mt-3 sm:mt-6 sm:text-lg">
                    <div class="py-1">
                        <div class="flex items-baseline justify-between leading-snug">
                            <div class="">Shipping Fee</div>
                            <div class="relative pr-5 sm:pr-0">
                                <div class="text-right text-rose-500 font-bold">from 18 US$</div>
                                <div
                                    class="absolute right-0 flex items-center justify-center w-6 h-6 -mt-3 -mr-1 cursor-pointer sm:-mr-6 top-1/2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" class="w-5 h-5 text-rose-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="py-1">
                        <div class="flex items-baseline justify-between leading-snug">
                            <div class="">MarketszHandling Fee</div>
                            <div class="relative  sm:pr-0">
                                <div class="text-right undefined font-bold">10 US$</div>
                            </div>
                        </div>
                    </div>
                    <div class="py-1">
                        <div class="flex items-baseline justify-between leading-snug">
                            <div class="">Duties &amp; Taxes</div>
                            <div class="relative pr-5 sm:pr-0">
                                <div class="text-right text-orange-600 font-bold">Due upon delivery</div>
                                <div
                                    class="absolute right-0 flex items-center justify-center w-6 h-6 -mt-3 -mr-1 cursor-pointer sm:-mr-6 top-1/2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" class="w-5 h-5 text-orange-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pt-3 mt-3 border-t pb-1">
                        <div class="flex items-baseline justify-between leading-snug">
                            <div class="">Insurance</div>
                            <div class="relative pr-5 sm:pr-0">
                                <div class="text-right text-orange-600 font-bold">Free or optional</div>
                                <div
                                    class="absolute right-0 flex items-center justify-center w-6 h-6 -mt-3 -mr-1 cursor-pointer sm:-mr-6 top-1/2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" class="w-5 h-5 text-orange-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="py-1">
                        <div class="flex items-baseline justify-between leading-snug">
                            <div class="">Third-Party Fees</div>
                            <div class="relative pr-5 sm:pr-0">
                                <div class="text-right text-orange-600 font-bold">Depends</div>
                                <div
                                    class="absolute right-0 flex items-center justify-center w-6 h-6 -mt-3 -mr-1 cursor-pointer sm:-mr-6 top-1/2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" class="w-5 h-5 text-orange-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pt-3 mt-3 border-t pb-1">
                        <div class="flex items-baseline justify-between leading-snug">
                            <div class="">Membership Fee</div>
                            <div class="relative  sm:pr-0">
                                <div class="text-right text-rose-500 font-bold">FREE</div>
                            </div>
                        </div>
                    </div>
                    <div class="py-1">
                        <div class="flex items-baseline justify-between leading-snug">
                            <div class="">Repack &amp; Combine Service</div>
                            <div class="relative  sm:pr-0">
                                <div class="text-right text-rose-500 font-bold">FREE</div>
                            </div>
                        </div>
                    </div>
                    <div class="py-1">
                        <div class="flex items-baseline justify-between leading-snug">
                            <div class="">Single Repack Service</div>
                            <div class="relative  sm:pr-0">
                                <div class="text-right text-rose-500 font-bold">FREE</div>
                            </div>
                        </div>
                    </div>
                    <div class="py-1">
                        <div class="flex items-baseline justify-between leading-snug">
                            <div class="">Storage</div>
                            <div class="relative  sm:pr-0">
                                <div class="text-right text-rose-500 font-bold">FREE</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </template>
    <div class="mt-auto">
        <div class="relative pt-8 pb-40 mt-20 bg-rose-50 sm:pt-0 sm:mt-40">
            <img alt="Seahorse illustration" src="assets/image/ballon.svg"
                class="absolute hidden w-auto h-40 md:-left-36 lg:left-2 xl:left-24 top-48 animate-float md:block">
            <img alt="Fish illustration" src="assets/image/home/cartoon-plane.svg"
                class="absolute w-auto h-20 -mr-20 transform -translate-x-1/2 sm:h-24 bottom-8 right-1/2 md:-mr-0 md:right-2 md:top-1/2 md:-translate-y-1/2 lg:right-24 xl:right-32 animate-wiggle">
            <section class="container space-y-12 pt-12 sm:pt-20">
                <header class="max-w-2xl px-4 mx-auto text-center sm:pt-20">
                    <h2 class="text-3xl font-bold leading-snug sm:text-4xl">It’s time to shop till you drop and get your
                        orders in record time!</h2>
                    <p class="mt-2 text-xl leading-snug sm:text-2xl">It’s never been easier to shop from any online store
                        and get your packages delivered right at your door</p>
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
                        <form action="register.html" method="get" class="relative w-full max-w-sm"><input
                                type="hidden" name="country" value="">
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
        <footer class="px-4 text-white bg-primary md:px-0 relative">
            <style>
                .footer-round {
                    border-radius: 100% 100% 0 0;
                }
            </style>
            <div
                class="footer-round absolute left-0 right-0 -top-6 h-6 md:h-10 z-10 overflow-hidden bg-primary rounded-t-full">
            </div>
            <div class="container">
                <nav class="flex flex-col items-center justify-between pt-0 pb-6  text-center md:flex-row md:text-left">
                    <div><a href="/"><img class="logo" src="assets/image/logo.svg" alt="app-logo"></a></div>
                    <ul class="flex flex-col text-lg font-bold md:flex-row">
                        <li class="relative z-20 mx-1 lg:mx-2 xl:mx-6"><a class="border-b-2 border-transparent"
                                href="about.html">About</a></li>
                        <li class="relative z-20 mx-1 lg:mx-2 xl:mx-6"><a class="border-b-2 border-transparent"
                                href="contact.html">Contact US</a></li>
                        <li class="relative z-20 mx-1 lg:mx-2 xl:mx-6"><a class="border-b-2 border-transparent"
                                href="calculator.html">Cost Calculator</a></li>
                    </ul>
                    <div class="flex flex-col items-center sm:flex-row">
                        <ul class="flex">
                            <li class="px-2"><a href="#" target="_blank" rel="noreferrer"
                                    class="hover:scale-105"><i class="text-xl fab fa-facebook"
                                        aria-hidden="true"></i></a></li>
                            <li class="px-2"><a href="#" target="_blank" rel="noreferrer"
                                    class="hover:scale-105"><i class="text-xl fab fa-instagram"
                                        aria-hidden="true"></i></a></li>
                            <li class="px-2"><a href="#" target="_blank" rel="noreferrer"
                                    class="hover:scale-105"><i class="text-xl fab fa-youtube" aria-hidden="true"></i></a>
                            </li>
                            <li class="px-2"><a href="#" target="_blank" rel="noreferrer"
                                    class="hover:scale-105"><i class="text-xl fab fa-telegram"
                                        aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </nav>
                <hr>
                <div class="pt-6 pb-12 text-sm text-center text-white text-opacity-75">
                    <div class="max-w-lg mx-auto"><span class="">© All Rights Reserved - 2022</span>
                        <div class="mt-2 space-x-4">
                            <a class="underline whitespace-nowrap hover:text-white" href="terms.html">Terms of Service</a>
                            <a class="underline whitespace-nowrap hover:text-white" href="privacy.html">Privacy Policy</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script src="{{ asset('assets/js/calculator.js') }}"></script>
@endsection
