   <header class="relative w-full overflow-hidden text-white">
       <img class="header-bg" src="{{ asset('assets/image/home/background.svg') }}" alt="background" width="100%">

       <nav class="text-md xl:text-lg font-bold">
           <div class="flex items-center px-2 lg:px-4 pt-4 lg:p-8">
               <div class="justify-start flex-auto md:flex md:flex-grow mr-0 md:-mr-20 lg:-mr-20 xl:mr-0">
                   <a href="{{ route('web.home') }}"><img class="logo" src="{{ asset('assets/image/logo.svg') }}"
                           alt="app-logo"></a>
               </div>
               <ul class="items-center justify-between hidden ml-2 xl:ml-10 h-full md:flex lg:flex">
                   <li class="relative z-20 mx-1 lg:mx-2 xl:mx-6"><a class="border-b-2 border-transparent"
                           href="{{ route('web.calculator') }}">Cost Calculator</a></li>
                   <li class="relative z-20 mx-1 lg:mx-2 xl:mx-6"><a class="border-b-2 border-transparent"
                           href="{{ route('web.about') }}">About</a></li>
                   <li class="relative z-20 mx-1 lg:mx-2 xl:mx-6"><a class="border-b-2 border-transparent"
                           href="{{ route('web.contact') }}">Contact US</a></li>
                   <template>
                       <li class="relative z-20 mx-1 lg:mx-2 xl:mx-6">
                           <button class="flex items-center font-bold focus:outline-none" id="headlessui-menu-button-1"
                               type="button" aria-haspopup="true">
                               <span>More</span>
                               <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                   class="w-4 h-4 ml-0.5" stroke="currentColor">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                       d="M19 9l-7 7-7-7"></path>
                               </svg>
                           </button>
                       </li>
                       <li class="relative z-20 mx-1 lg:mx-2 xl:mx-6">
                           <div>
                               <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                   stroke-linecap="round" stroke-linejoin="round" class="w-4">
                                   <circle cx="11" cy="11" r="8"></circle>
                                   <path d="M21 21l-4.35-4.35"></path>
                               </svg>
                           </div>
                       </li>
                   </template>
               </ul>
               <div class="justify-end items-center hidden md:space-x-1 lg:space-x-2 md:flex md:flex-grow ml-0 lg:ml-4">
                   <a href="{{ route('login') }}"
                       class="h-9 border-2 rounded-full border-opacity-25 md:px-1 lg:px-4 flex items-center whitespace-nowrap justify-center hover:border-opacity-100 text-sm lg:text-base border-white">Login</a>
                   <a href="{{ route('register') }}"
                       class="h-9 md:px-1 lg:px-4 rounded-full font-bold text-sm md:text-base flex whitespace-nowrap items-center justify-center bg-white text-primary">Get
                       started</a>
               </div>
               <div class="block md:hidden">
                   <button aria-label="Open menu" class="p-2">
                       <svg class="stroke-current w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none"
                           viewBox="0 0 24 24" aria-hidden="true">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                               d="M4 6h16M4 12h16M4 18h16"></path>
                       </svg>
                   </button>
               </div>
           </div>
       </nav>
   </header>
