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
                        :href="route('dashboard')"
                        class="flex items-center p-3 rounded-md hover:bg-gray-700 transition-colors duration-200"
                        :class="{
                            'text-white bg-gray-700':
                                route().current('dashboard'),
                        }"
                    >
                        <i class="fa-solid fa-house w-5 h-5 mr-3"></i>
                        Dashboard
                    </Link>
                </li>

                <!-- Dropdown Menu 1 -->
                <li class="mb-2">
                    <button
                        @click="toggleDropdown('products')"
                        class="flex items-center justify-between w-full p-3 rounded-md hover:bg-gray-700 transition-colors duration-200 focus:outline-none"
                        :class="{
                            'bg-gray-700 text-white':
                                isActiveDropdown('admin.packages'),
                        }"
                    >
                        <span class="flex items-center">
                            <i class="fa-solid fa-cube w-5 h-5 mr-3"></i>
                            Packages
                        </span>
                        <svg
                            :class="{
                                'rotate-90': activeDropdown === 'products',
                            }"
                            class="w-4 h-4 transform transition-transform duration-200"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            ></path>
                        </svg>
                    </button>
                    <ul
                        v-if="activeDropdown === 'products'"
                        class="pl-8 mt-1 space-y-1"
                    >
                        <li>
                            <Link
                                :href="route('admin.packages')"
                                class="block p-2 rounded-md hover:bg-gray-600 transition-colors duration-200"
                                :class="{
                                    'text-white bg-gray-700':
                                        route().current('admin.packages'),
                                }"
                                >All Packages</Link
                            >
                        </li>
                    </ul>
                </li>

                <!-- Dropdown Menu 2 -->
                <li class="mb-2">
                    <button
                        @click="toggleDropdown('users')"
                        class="flex items-center justify-between w-full p-3 rounded-md hover:bg-gray-700 transition-colors duration-200 focus:outline-none"
                    >
                        <span class="flex items-center">
                            <i
                                class="fa fa-user w-5 h-5 mr-3"
                                aria-hidden="true"
                            ></i>
                            Users
                        </span>
                        <svg
                            :class="{
                                'rotate-90': activeDropdown === 'users',
                            }"
                            class="w-4 h-4 transform transition-transform duration-200"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            ></path>
                        </svg>
                    </button>
                    <ul
                        v-if="activeDropdown === 'users'"
                        class="pl-8 mt-1 space-y-1"
                    >
                        <li>
                            <a
                                href="#"
                                class="block p-2 rounded-md hover:bg-gray-600 transition-colors duration-200"
                                >All Users</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="block p-2 rounded-md hover:bg-gray-600 transition-colors duration-200"
                                >Roles & Permissions</a
                            >
                        </li>
                    </ul>
                </li>

                <!-- Settings Link -->
                <li class="mb-2">
                    <a
                        href="#"
                        class="flex items-center p-3 rounded-md hover:bg-gray-700 transition-colors duration-200"
                    >
                        <i
                            class="fa fa-cog w-5 h-5 mr-3"
                            aria-hidden="true"
                        ></i>
                        Settings
                    </a>
                </li>
                <li class="mb-2">
                    <Link
                        :href="route('admin.import')"
                        class="flex items-center p-3 rounded-md hover:bg-gray-700 transition-colors duration-200"
                        :class="{
                            'text-white bg-gray-700':
                                route().current('admin.import'),
                        }"
                    >
                        <i
                            class="fa fa-cloud-upload w-5 h-5 mr-3"
                            aria-hidden="true"
                        ></i>
                        Imports
                    </Link>
                </li>
            </ul>
        </nav>
    </aside>
</template>
