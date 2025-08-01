@extends('layout.master')
@section('title', 'Contact')
@section('content')
    <section class="container space-y-12 pt-12 sm:pt-20" id="meettheteam">
        <header class="max-w-2xl px-4 mx-auto text-center sm:pt-20">
            <h2 class="text-3xl font-bold leading-snug sm:text-4xl">Meet the Marketsz team</h2>
            <p class="mt-2 text-xl leading-snug sm:text-2xl">Marketsz is a diverse team of fun-loving individuals from around
                the world, passionate about the mission of bringing you the best
                shipping experience possible.</p>
        </header>
        <div class="grid grid-cols-2 px-4 pb-20 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 sm:px-0">
            <div
                class="p-4 text-center border border-transparent cursor-pointer rounded-xl hover:border-gray-100 hover:shadow-lg">
                <div class="relative w-24 h-24 mx-auto overflow-hidden bg-gray-100 rounded-full">
                    <div
                        style="display:inline-block;max-width:100%;overflow:hidden;position:relative;box-sizing:border-box;margin:0">
                        <div style="box-sizing:border-box;display:block;max-width:100%">
                            <img style="max-width:100%;display:block;margin:0;border:none;padding:0" alt=""
                                aria-hidden="true" role="presentation"
                                src="{{ asset('assets') }}/image/calculator/Marcela.jpg" />
                        </div>
                        <img alt="Photo of Chris Quast" src="{{ asset('assets') }}/image/calculator/Demi.jpg"
                            decoding="async" class="absolute inset-0 object-cover"
                            style="position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;padding:0;border:none;margin:auto;display:block;width:0;height:0;min-width:100%;max-width:100%;min-height:100%;max-height:100%" />
                    </div>
                </div>
                <div class="mt-4 text-xl font-bold leading-tight">Chris Quast</div>
                <div class="leading-tight text-gray-600">Management</div>
            </div>
            <div
                class="p-4 text-center border border-transparent cursor-pointer rounded-xl hover:border-gray-100 hover:shadow-lg">
                <div class="relative w-24 h-24 mx-auto overflow-hidden bg-gray-100 rounded-full">
                    <div
                        style="display:inline-block;max-width:100%;overflow:hidden;position:relative;box-sizing:border-box;margin:0">
                        <div style="box-sizing:border-box;display:block;max-width:100%">
                            <img style="max-width:100%;display:block;margin:0;border:none;padding:0" alt=""
                                aria-hidden="true" role="presentation"
                                src="{{ asset('assets') }}/image/calculator/Marcela.jpg" />
                        </div>
                        <img alt="Photo of Joshua Suares" src="{{ asset('assets') }}/image/calculator/Marcela.jpg"
                            decoding="async" class="absolute inset-0 object-cover"
                            style="position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;padding:0;border:none;margin:auto;display:block;width:0;height:0;min-width:100%;max-width:100%;min-height:100%;max-height:100%" />
                    </div>
                </div>
                <div class="mt-4 text-xl font-bold leading-tight">Joshua Suares</div>
                <div class="leading-tight text-gray-600">Management</div>
            </div>
            <div
                class="p-4 text-center border border-transparent cursor-pointer rounded-xl hover:border-gray-100 hover:shadow-lg">
                <div class="relative w-24 h-24 mx-auto overflow-hidden bg-gray-100 rounded-full">
                    <div
                        style="display:inline-block;max-width:100%;overflow:hidden;position:relative;box-sizing:border-box;margin:0">
                        <div style="box-sizing:border-box;display:block;max-width:100%">
                            <img style="max-width:100%;display:block;margin:0;border:none;padding:0" alt=""
                                aria-hidden="true" role="presentation"
                                src="{{ asset('assets') }}/image/calculator/Marcela.jpg" />
                        </div>
                        <img alt="Photo of Leonitza Rojer" src="{{ asset('assets') }}/image/calculator/client (2).jpg"
                            decoding="async" class="absolute inset-0 object-cover"
                            style="position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;padding:0;border:none;margin:auto;display:block;width:0;height:0;min-width:100%;max-width:100%;min-height:100%;max-height:100%" />
                    </div>
                </div>
                <div class="mt-4 text-xl font-bold leading-tight">Leonitza Rojer</div>
                <div class="leading-tight text-gray-600">Customer Service</div>
            </div>
            <div
                class="p-4 text-center border border-transparent cursor-pointer rounded-xl hover:border-gray-100 hover:shadow-lg">
                <div class="relative w-24 h-24 mx-auto overflow-hidden bg-gray-100 rounded-full">
                    <div
                        style="display:inline-block;max-width:100%;overflow:hidden;position:relative;box-sizing:border-box;margin:0">
                        <div style="box-sizing:border-box;display:block;max-width:100%">
                            <img style="max-width:100%;display:block;margin:0;border:none;padding:0" alt=""
                                aria-hidden="true" role="presentation"
                                src="{{ asset('assets') }}/image/calculator/Marcela.jpg" />
                        </div>
                        <img alt="Photo of Natasha Delgado" src="{{ asset('assets') }}/image/calculator/Natasha-image.jpg"
                            decoding="async" class="absolute inset-0 object-cover"
                            style="position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;padding:0;border:none;margin:auto;display:block;width:0;height:0;min-width:100%;max-width:100%;min-height:100%;max-height:100%" />
                    </div>
                </div>
                <div class="mt-4 text-xl font-bold leading-tight">Natasha Delgado</div>
                <div class="leading-tight text-gray-600">Customer Service</div>
            </div>
            <div
                class="p-4 text-center border border-transparent cursor-pointer rounded-xl hover:border-gray-100 hover:shadow-lg">
                <div class="relative w-24 h-24 mx-auto overflow-hidden bg-gray-100 rounded-full">
                    <div
                        style="display:inline-block;max-width:100%;overflow:hidden;position:relative;box-sizing:border-box;margin:0">
                        <div style="box-sizing:border-box;display:block;max-width:100%">
                            <img style="max-width:100%;display:block;margin:0;border:none;padding:0" alt=""
                                aria-hidden="true" role="presentation"
                                src="{{ asset('assets') }}/image/calculator/Marcela.jpg" />
                        </div>
                        <img alt="Photo of Nicole Oliviera" src="{{ asset('assets') }}/image/calculator/image01.jpg"
                            decoding="async" class="absolute inset-0 object-cover"
                            style="position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;padding:0;border:none;margin:auto;display:block;width:0;height:0;min-width:100%;max-width:100%;min-height:100%;max-height:100%" />
                    </div>
                </div>
                <div class="mt-4 text-xl font-bold leading-tight">Nicole Oliviera</div>
                <div class="leading-tight text-gray-600">Customer Service</div>
            </div>
            <div
                class="p-4 text-center border border-transparent cursor-pointer rounded-xl hover:border-gray-100 hover:shadow-lg">
                <div class="relative w-24 h-24 mx-auto overflow-hidden bg-gray-100 rounded-full">
                    <div
                        style="display:inline-block;max-width:100%;overflow:hidden;position:relative;box-sizing:border-box;margin:0">
                        <div style="box-sizing:border-box;display:block;max-width:100%">
                            <img style="max-width:100%;display:block;margin:0;border:none;padding:0" alt=""
                                aria-hidden="true" role="presentation"
                                src="{{ asset('assets') }}/image/calculator/Marcela.jpg" />
                        </div>
                        <img alt="Photo of Oriana Berkemeijer" src="{{ asset('assets') }}/image/calculator/client.jpg"
                            decoding="async" class="absolute inset-0 object-cover"
                            style="position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;padding:0;border:none;margin:auto;display:block;width:0;height:0;min-width:100%;max-width:100%;min-height:100%;max-height:100%" />
                    </div>
                </div>
                <div class="mt-4 text-xl font-bold leading-tight">Oriana Berkemeijer</div>
                <div class="leading-tight text-gray-600">Management</div>
            </div>
            <div
                class="p-4 text-center border border-transparent cursor-pointer rounded-xl hover:border-gray-100 hover:shadow-lg">
                <div class="relative w-24 h-24 mx-auto overflow-hidden bg-gray-100 rounded-full">
                    <div
                        style="display:inline-block;max-width:100%;overflow:hidden;position:relative;box-sizing:border-box;margin:0">
                        <div style="box-sizing:border-box;display:block;max-width:100%">
                            <img style="max-width:100%;display:block;margin:0;border:none;padding:0" alt=""
                                aria-hidden="true" role="presentation"
                                src="{{ asset('assets') }}/image/calculator/Marcela.jpg" />
                        </div>
                        <img alt="Photo of Marcela Vieira"
                            src="{{ asset('assets') }}/image/calculator/MyMalls_Thumbnail-EyonielWawoe_100x100px_FINAL.jpg"
                            decoding="async" class="absolute inset-0 object-cover"
                            style="position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;padding:0;border:none;margin:auto;display:block;width:0;height:0;min-width:100%;max-width:100%;min-height:100%;max-height:100%" />
                    </div>
                </div>
                <div class="mt-4 text-xl font-bold leading-tight">Marcela Vieira</div>
                <div class="leading-tight text-gray-600">Marketing</div>
            </div>
            <div
                class="p-4 text-center border border-transparent cursor-pointer rounded-xl hover:border-gray-100 hover:shadow-lg">
                <div class="relative w-24 h-24 mx-auto overflow-hidden bg-gray-100 rounded-full">
                    <div
                        style="display:inline-block;max-width:100%;overflow:hidden;position:relative;box-sizing:border-box;margin:0">
                        <div style="box-sizing:border-box;display:block;max-width:100%">
                            <img style="max-width:100%;display:block;margin:0;border:none;padding:0" alt=""
                                aria-hidden="true" role="presentation"
                                src="{{ asset('assets') }}/image/calculator/Marcela.jpg" />
                        </div>
                        <img alt="Photo of Demi Kiewiet"
                            src="{{ asset('assets') }}/image/calculator/Headshot_190806_182626.jpg" decoding="async"
                            class="absolute inset-0 object-cover"
                            style="position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;padding:0;border:none;margin:auto;display:block;width:0;height:0;min-width:100%;max-width:100%;min-height:100%;max-height:100%" />
                    </div>
                </div>
                <div class="mt-4 text-xl font-bold leading-tight">Demi Kiewiet</div>
                <div class="leading-tight text-gray-600">Design</div>
            </div>
            <div
                class="p-4 text-center border border-transparent cursor-pointer rounded-xl hover:border-gray-100 hover:shadow-lg">
                <div class="relative w-24 h-24 mx-auto overflow-hidden bg-gray-100 rounded-full">
                    <div
                        style="display:inline-block;max-width:100%;overflow:hidden;position:relative;box-sizing:border-box;margin:0">
                        <div style="box-sizing:border-box;display:block;max-width:100%">
                            <img style="max-width:100%;display:block;margin:0;border:none;padding:0" alt=""
                                aria-hidden="true" role="presentation"
                                src="{{ asset('assets') }}/image/calculator/Marcela.jpg" />
                        </div>
                        <img alt="Photo of Johan Perrette" src="{{ asset('assets') }}/image/calculator/Headshot2.png"
                            decoding="async" class="absolute inset-0 object-cover"
                            style="position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;padding:0;border:none;margin:auto;display:block;width:0;height:0;min-width:100%;max-width:100%;min-height:100%;max-height:100%" />
                    </div>
                </div>
                <div class="mt-4 text-xl font-bold leading-tight">Johan Perrette</div>
                <div class="leading-tight text-gray-600">Marketing</div>
            </div>
            <div
                class="p-4 text-center border border-transparent cursor-pointer rounded-xl hover:border-gray-100 hover:shadow-lg">
                <div class="relative w-24 h-24 mx-auto overflow-hidden bg-gray-100 rounded-full">
                    <div
                        style="display:inline-block;max-width:100%;overflow:hidden;position:relative;box-sizing:border-box;margin:0">
                        <div style="box-sizing:border-box;display:block;max-width:100%">

                            <img style="max-width:100%;display:block;margin:0;border:none;padding:0" alt=""
                                aria-hidden="true" role="presentation"
                                src="{{ asset('assets') }}/image/calculator/Marcela.jpg" />
                        </div>
                        <img alt="Photo of Miklós Fazekas" src="{{ asset('assets') }}/image/calculator/untitled-193.jpg"
                            decoding="async" class="absolute inset-0 object-cover"
                            style="position:absolute;top:0;left:0;bottom:0;right:0;box-sizing:border-box;padding:0;border:none;margin:auto;display:block;width:0;height:0;min-width:100%;max-width:100%;min-height:100%;max-height:100%" />
                    </div>
                </div>
                <div class="mt-4 text-xl font-bold leading-tight">Miklós Fazekas</div>
                <div class="leading-tight text-gray-600">Technology</div>
            </div>
        </div>
    </section>
    <div class="mt-auto">
        <div class="relative pt-8 pb-40 mt-20 bg-rose-50 sm:pt-0 sm:mt-40">
            <img alt="Seahorse illustration" src="{{ asset('assets') }}/image/ballon.svg"
                class="absolute hidden w-auto h-40 md:-left-36 lg:left-2 xl:left-24 top-48 animate-float md:block">
            <img alt="Fish illustration" src="{{ asset('assets') }}/image/home/cartoon-plane.svg"
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

    </div>
@endsection
