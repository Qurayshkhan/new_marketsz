<script setup>
import { Link, usePage } from "@inertiajs/vue3";

const props = defineProps({
    isSidebarOpen: Number,
    isMobile: Number,
    activeDropdown: String,
    toggleDropdown: Function,
    isActiveDropdown: Function,
});

const authUser = usePage().props.auth.user;
</script>
<template>
    <aside
        :class="{
            'w-80': isSidebarOpen,
            'w-0': !isSidebarOpen,
            'hidden md:block': !isSidebarOpen && !isMobile,
        }"
        class="fixed top-16 left-0 h-[calc(100vh-4rem)] bg-primary-500 text-white shadow-lg z-20 overflow-y-auto transition-all duration-300 ease-in-out rounded-tr-lg rounded-br-lg md:relative md:top-0 md:h-full md:pt-0"
    >
        <nav class="p-4 text-gray-400">
            <ul>
                <li class="mb-2">
                    <Link
                        :href="route('dashboard')"
                        class="flex items-center p-3 text-white transition-colors duration-200 rounded-md hover:bg-primary-700"
                        :class="{
                            'text-white bg-primary-700':
                                route().current('dashboard'),
                        }"
                    >
                        <i class="w-5 h-5 mr-3 fa-solid fa-house"></i>
                        Dashboard
                    </Link>
                </li>
                <!-- <li class="mb-2">
                    <Link
                        :href="route('admin.shipments')"
                        class="flex items-center p-3 text-white transition-colors duration-200 rounded-md hover:bg-primary-700"
                        :class="{
                            'text-white bg-primary-700':
                                $page.url.startsWith('/shipments'),
                        }"
                    >
                        <i class="w-5 h-5 mr-3 fa-solid fa-ship"></i>
                        Shipment
                    </Link>
                </li> -->

                <li class="mb-2">
                    <button
                        @click="toggleDropdown('shipments')"
                        class="flex items-center justify-between w-full p-3 text-white transition-colors duration-200 rounded-md hover:bg-primary-700 focus:outline-none"
                        :class="{
                            'bg-primary-700 text-white':
                                isActiveDropdown('admin.shipments'),
                        }"
                    >
                        <span class="flex items-center">
                            <i class="w-5 h-5 mr-3 fa-solid fa-cube"></i>
                            Shipment
                        </span>
                        <svg
                            :class="{
                                'rotate-90': activeDropdown === 'shipments',
                            }"
                            class="w-4 h-4 transition-transform duration-200 transform"
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
                        v-if="activeDropdown === 'shipments'"
                        class="pl-8 mt-1 space-y-1"
                    >
                        <li>
                            <Link
                                :href="route('admin.shipments')"
                                class="block p-2 text-white transition-colors duration-200 rounded-md hover:bg-primary-700"
                                :class="{
                                    'text-white bg-primary-700':
                                        route().current('admin.shipments'),
                                }"
                                >Shipment Requests</Link
                            >
                        </li>
                        <li>
                            <Link
                                :href="
                                    route('admin.shipments.outbond', {
                                        status: 'shipped',
                                    })
                                "
                                class="block p-2 text-white transition-colors duration-200 rounded-md hover:bg-primary-700"
                                :class="{
                                    'text-white bg-primary-700':
                                        route().current(
                                            'admin.shipments.outbond'
                                        ),
                                }"
                            >
                                Outbond Requests</Link
                            >
                        </li>
                    </ul>
                </li>
                <li class="mb-2">
                    <button
                        @click="toggleDropdown('products')"
                        class="flex items-center justify-between w-full p-3 text-white transition-colors duration-200 rounded-md hover:bg-primary-700 focus:outline-none"
                        :class="{
                            'bg-primary-700 text-white':
                                isActiveDropdown('admin.packages'),
                        }"
                    >
                        <span class="flex items-center">
                            <i class="w-5 h-5 mr-3 fa-solid fa-cube"></i>
                            Packages
                        </span>
                        <svg
                            :class="{
                                'rotate-90': activeDropdown === 'products',
                            }"
                            class="w-4 h-4 transition-transform duration-200 transform"
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
                                :href="route('admin.packages.create')"
                                class="block p-2 text-white transition-colors duration-200 rounded-md hover:bg-primary-700"
                                :class="{
                                    'text-white bg-primary-700':
                                        route().current(
                                            'admin.packages.create'
                                        ),
                                }"
                                >Create Packages</Link
                            >
                        </li>
                        <li>
                            <Link
                                :href="route('admin.packages')"
                                class="block p-2 text-white transition-colors duration-200 rounded-md hover:bg-primary-700"
                                :class="{
                                    'text-white bg-primary-700':
                                        route().current('admin.packages'),
                                }"
                                >All Packages</Link
                            >
                        </li>
                        <li>
                            <Link
                                :href="route('admin.packages.kanban')"
                                class="block p-2 text-white transition-colors duration-200 rounded-md hover:bg-primary-700"
                                :class="{
                                    'text-white bg-primary-700':
                                        route().current(
                                            'admin.packages.kanban'
                                        ),
                                }"
                            >
                                Status Management</Link
                            >
                        </li>
                    </ul>
                </li>

                <li class="mb-2">
                    <button
                        @click="toggleDropdown('users')"
                        class="flex items-center justify-between w-full p-3 text-white transition-colors duration-200 rounded-md hover:bg-primary-700 focus:outline-none"
                        :class="{
                            'bg-primary-700 text-white':
                                isActiveDropdown('admin.users'),
                        }"
                    >
                        <span class="flex items-center">
                            <i
                                class="w-5 h-5 mr-3 fa fa-user"
                                aria-hidden="true"
                            ></i>
                            Users
                        </span>
                        <svg
                            :class="{
                                'rotate-90': activeDropdown === 'users',
                            }"
                            class="w-4 h-4 transition-transform duration-200 transform"
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
                            <Link
                                :href="route('admin.users')"
                                class="block p-2 text-white transition-colors duration-200 rounded-md hover:bg-primary-700"
                                :class="{
                                    ' bg-primary-700':
                                        route().current('admin.users'),
                                }"
                                >All Users</Link
                            >
                        </li>
                        <!-- <li>
                            <Link
                                :href="route('admin.customers')"
                                class="block p-2 text-white transition-colors duration-200 rounded-md hover:bg-primary-700"
                                :class="{
                                    ' bg-primary-700':
                                        route().current('admin.customers'),
                                }"
                                >Customers</Link
                            >
                        </li> -->
                    </ul>
                </li>
                <li class="mb-2" v-if="authUser.type == 1">
                    <Link
                        :href="route('admin.transactions.allTransactions')"
                        class="flex items-center p-3 text-white transition-colors duration-200 rounded-md hover:bg-primary-700"
                        :class="{
                            'text-white bg-primary-700': route().current(
                                'admin.transactions.allTransactions'
                            ),
                        }"
                    >
                        <i class="w-5 h-5 mr-3 fa-solid fa-dollar-sign"></i>
                        Transaction
                    </Link>
                </li>

                <!-- <li class="mb-2" v-if="authUser.type == 1">
                    <Link
                        :href="route('admin.import')"
                        class="flex items-center p-3 text-white transition-colors duration-200 rounded-md hover:bg-primary-700"
                        :class="{
                            'text-white bg-primary-700':
                                route().current('admin.import'),
                        }"
                    >
                        <i
                            class="w-5 h-5 mr-3 fa fa-cloud-upload"
                            aria-hidden="true"
                        ></i>
                        Imports
                    </Link>
                </li> -->
                <li class="mb-2" v-if="authUser.type == 1">
                    <Link
                        :href="route('admin.coupons.index')"
                        class="flex items-center p-3 text-white transition-colors duration-200 rounded-md hover:bg-primary-700"
                        :class="{
                            'text-white bg-primary-700':
                                route().current('admin.coupons'),
                        }"
                    >
                        <i class="w-5 h-5 mr-3 fa-solid fa-ticket"></i>
                        Coupons
                    </Link>
                </li>

                <li class="mb-2" v-if="authUser.type == 1">
                    <Link
                        :href="route('admin.loyalty.index')"
                        class="flex items-center p-3 text-white transition-colors duration-200 rounded-md hover:bg-primary-700"
                        :class="{
                            'text-white bg-primary-700':
                                route().current('admin.loyalty'),
                        }"
                    >
                        <i class="w-5 h-5 mr-3 fa-solid fa-star"></i>
                        Loyalty Program
                    </Link>
                </li>
                <li class="mb-2">
                    <Link
                        :href="route('logout')"
                        method="post"
                        class="flex items-center p-3 text-white transition-colors duration-200 rounded-md hover:bg-primary-700 hover:w-full"
                    >
                        <i
                            class="w-5 h-5 mr-3 fa-solid fa-right-from-bracket"
                        ></i>
                        Logout
                    </Link>
                </li>
            </ul>
        </nav>
    </aside>
</template>
