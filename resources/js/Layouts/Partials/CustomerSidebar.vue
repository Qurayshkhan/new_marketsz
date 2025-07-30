<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    isSidebarOpen: Number,
    isMobile: Number,
    activeDropdown: String,
    toggleDropdown: Function,
    isActiveDropdown: Function,
});
</script>
<template>
    <aside
        :class="{
            'w-64': isSidebarOpen,
            'w-0': !isSidebarOpen,
            'hidden md:block': !isSidebarOpen && !isMobile,
        }"
        class="fixed top-16 left-0 h-[calc(100vh-4rem)] bg-primary-800 text-white shadow-lg z-20 overflow-y-auto transition-all duration-300 ease-in-out rounded-tr-lg rounded-br-lg md:relative md:top-0 md:h-full md:pt-0"
    >
        <nav class="p-4 text-gray-400">
            <ul>
                <!-- Dashboard Link -->
                <li class="mb-2">
                    <Link
                        :href="route('customer.dashboard')"
                        class="flex items-center p-3 rounded-md hover:bg-gray-700 transition-colors duration-200"
                        :class="{
                            'text-white bg-gray-700':
                                route().current('customer.dashboard'),
                        }"
                    >
                        <i class="fa-solid fa-house w-5 h-5 mr-3"></i>
                        My Suite
                    </Link>
                </li>
            </ul>
        </nav>
    </aside>
</template>
