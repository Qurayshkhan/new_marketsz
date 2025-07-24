<script setup>
import Alert from "@/Components/Alert.vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";

const props = usePage();
const isSidebarOpen = ref(window.innerWidth >= 768);
const activeDropdown = ref(null);
const isMobile = ref(window.innerWidth < 768);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const toggleDropdown = (dropdownName) => {
    activeDropdown.value =
        activeDropdown.value === dropdownName ? null : dropdownName;
};

const handleResize = () => {
    isMobile.value = window.innerWidth < 768;
    if (!isMobile.value) {
        isSidebarOpen.value = true;
    } else {
        isSidebarOpen.value = false;
    }
};

onMounted(() => {
    window.addEventListener("resize", handleResize);
    handleResize();
});

onUnmounted(() => {
    window.removeEventListener("resize", handleResize);
});

const isActiveDropdown = (prefix) => {
    return route().current().startsWith(prefix);
};
</script>

<template>
    <header
        class="fixed top-0 left-0 right-0 bg-white shadow-md z-30 flex items-center justify-between px-4 py-3 rounded-b-lg"
    >
        <!-- Logo and Sidebar Toggle -->
        <div class="flex items-center">
            <!-- Sidebar Toggle Button -->
            <button
                @click="toggleSidebar"
                class="p-2 mr-4 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-200"
            >
                <svg
                    class="w-6 h-6 text-gray-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    ></path>
                </svg>
            </button>
            <!-- Logo -->
            <div class="w-40">
                <a href="/">
                    <img
                        src="/assets/image/logo-original.svg"
                        class="Logo"
                        width="100%"
                    />
                </a>
                <!-- <Link :href="route('dashboard')" class="h-8">
                </Link> -->
            </div>
        </div>

        <!-- User/Right-side content can go here -->
        <div class="flex items-center space-x-4">
            <!-- <span class="text-primary-700"
                >Welcome,
                {{
                    $page.props.auth.user.first_name +
                    " " +
                    $page.props.auth.user.last_name
                }}!</span
            > -->

            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="m-1">
                    <img
                        src="https://placehold.co/40x40/cbd5e1/4a5568?text=U"
                        alt="User Avatar"
                        class="w-10 h-10 rounded-full border-2 border-primary-300"
                    />
                </div>
                <ul
                    tabindex="0"
                    class="dropdown-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm bg-white"
                >
                    <li>
                        <Link :href="route('profile.edit')">Profile</Link>
                    </li>
                    <li>
                        <Link :href="route('logout')" method="post" as="button"
                            >Log Out</Link
                        >
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <div class="flex pt-16 h-screen overflow-hidden">
        <!-- pt-16 to offset fixed header height -->
        <!-- Fixed Sidebar -->
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
                            <li>
                                <Link
                                    href="#"
                                    class="block p-2 rounded-md hover:bg-gray-600 transition-colors duration-200"
                                    >Add New Product</Link
                                >
                            </li>
                            <li>
                                <Link
                                    href="#"
                                    class="block p-2 rounded-md hover:bg-gray-600 transition-colors duration-200"
                                    >Categories</Link
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

        <!-- Content Area -->
        <main
            :class="{ 'ml-0': isSidebarOpen, 'ml-0 md:ml-0': !isSidebarOpen }"
            class="flex-1 p-6 transition-all duration-300 ease-in-out overflow-y-auto scrollable-content h-[calc(100vh-4rem)] bg-white"
        >
            <slot />
        </main>
    </div>
    <Alert :pageProps="$page.props" />
</template>

<style>
@import "@vuepic/vue-datepicker/dist/main.css";
@import "vue-select/dist/vue-select.css";
</style>
