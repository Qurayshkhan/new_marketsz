<script setup>
import ApplicationLogo from "@/Components/Images/Icons/ApplicationLogo.vue";
import Logo from "@/Components/Images/Icons/Logo.vue";
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();
const currentPath = page.url;
</script>

<template>
    <div class="flex flex-col bg-white">
        <!-- Navbar -->
        <nav
            class="flex justify-between items-center h-20 px-6 text-md xl:text-lg font-bold bg-white border-b"
        >
            <a href="/">
                <img
                    src="/assets/image/logo-original.svg"
                    alt="logo"
                    class="logo"
                    width="160"
                />
            </a>
            <div class="text-gray-600 flex items-center">
                <Link
                    v-if="currentPath === '/login'"
                    class="py-2 text-white px-4 rounded-full font-bold text-sm md:text-base flex whitespace-nowrap items-center justify-center bg-primary-500"
                    :href="route('register')"
                >
                    Register
                </Link>
                <Link
                    v-else
                    class="py-2 text-white px-4 rounded-full font-bold text-sm md:text-base flex whitespace-nowrap items-center justify-center bg-primary-500"
                    :href="route('login')"
                >
                    Login
                </Link>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 w-full px-4 py-12">
            <slot />
        </main>

        <!-- Footer -->
        <footer
            class="text-xs text-gray-600 text-center px-4 py-8 bg-gray-100"
            style="background: linear-gradient(#f8f8f8, #fff)"
        >
            <div class="mb-6">
                <p>
                    Marketsz Impressum: Global Malls Access Ltd., 21 Priory
                    Office Park, Stillorgan, County Dublin, A94F660 Ireland
                    <br />
                    Registered in Dublin, Ireland, under registration number
                    685442
                </p>
            </div>
            <div>
                <a class="underline px-2" href="terms.html">Terms of Service</a>
                &middot;
                <a class="underline px-2" href="privacy.html">Privacy Policy</a>
            </div>
        </footer>
    </div>
</template>
