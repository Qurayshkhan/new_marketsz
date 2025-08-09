<script setup>
import Alert from "@/Components/Alert.vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";
import AdminSidebar from "./Partials/AdminSidebar.vue";
import CustomerSidebar from "./Partials/CustomerSidebar.vue";

const props = usePage().props;
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
        <div>
            <ul class="menu bg-base-200 lg:menu-horizontal rounded-box">
                <li>
                    <a>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                            />
                        </svg>
                        Help
                    </a>
                </li>
                <li>
                    <a>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        Updates
                        <span class="badge badge-xs badge-warning">NEW</span>
                    </a>
                </li>
                <li>
                    <a>
                        Contact us
                        <span class="badge badge-xs badge-info"></span>
                    </a>
                </li>
                <li>
                    <a>
                        Notifications
                        <span class="badge badge-xs">99+</span>
                    </a>
                </li>
            </ul>
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
                    <li v-if="$page.props.auth.user.type == 1">
                        <Link :href="route('profile.edit')">Profile</Link>
                    </li>
                    <li v-if="$page.props.auth.user.type == 2">
                        <Link :href="route('customer.account.profile')"
                            >Account Setting</Link
                        >
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
        <CustomerSidebar
            v-if="props?.auth.user?.type == 2"
            :isSidebarOpen="isSidebarOpen"
            :isMobile="isMobile"
            :activeDropdown="activeDropdown"
            :toggleDropdown="toggleDropdown"
            :isActiveDropdown="isActiveDropdown"
        />
        <AdminSidebar
            v-if="props?.auth.user?.type == 1"
            :isSidebarOpen="isSidebarOpen"
            :isMobile="isMobile"
            :activeDropdown="activeDropdown"
            :toggleDropdown="toggleDropdown"
            :isActiveDropdown="isActiveDropdown"
        />
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
@import "../../css/custom.css";
@import "@vuepic/vue-datepicker/dist/main.css";
@import "vue-select/dist/vue-select.css";
</style>
