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
        <!-- Left: Logo + Sidebar Toggle -->
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
            </div>
        </div>

        <div class="flex items-center space-x-4">
            <div class="flex-1 flex justify-center">
                <div class="relative">
                    <button
                        @click="toggleDropdown('notifications')"
                        class="flex items-center border-none outline-none rounded-full transition-colors"
                    >
                        <i class="fa-solid fa-bell text-primary-500"></i>
                        <span
                            v-if="props?.notifications?.length > 0"
                            class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium leading-none text-white bg-red-500 rounded-full"
                        >
                            {{ props.notifications.length }}
                        </span>
                    </button>

                    <ul
                        v-if="activeDropdown === 'notifications'"
                        class="absolute right-0 mt-2 w-64 bg-white shadow-lg rounded-md overflow-hidden z-50"
                    >
                        <li
                            v-for="(note, index) in props?.notifications"
                            :key="index"
                            class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                        >
                            {{ note?.message }}
                        </li>
                        <li
                            v-if="
                                !props?.notifications ||
                                props.notifications.length === 0
                            "
                            class="px-4 py-2 text-gray-400 cursor-default"
                        >
                            No notifications
                        </li>
                    </ul>
                </div>
            </div>
            <div class="dropdown dropdown-end relative">
                <div tabindex="0" role="button" class="m-1">
                    <img
                        src="https://placehold.co/40x40/cbd5e1/4a5568?text=U"
                        alt="User Avatar"
                        class="w-10 h-10 rounded-full border-2 border-primary-400 shadow-sm hover:shadow-md transition-all"
                    />
                </div>

                <ul
                    tabindex="0"
                    class="dropdown-content menu bg-white rounded-xl z-50 w-60 p-3 shadow-lg border border-gray-100"
                >
                    <li class="px-2 py-3 border-b border-gray-200 mb-1">
                        <div class="flex items-center gap-3">
                            <img
                                src="https://placehold.co/40x40/cbd5e1/4a5568?text=U"
                                class="w-11 h-11 rounded-full border border-gray-300 shadow-sm"
                            />
                            <div class="flex flex-col">
                                <span
                                    class="font-semibold text-gray-900 text-sm"
                                >
                                    {{ $page.props.auth.user.name }}
                                </span>
                                <span class="text-xs text-gray-500 truncate">
                                    {{ $page.props.auth.user.email }}
                                </span>
                            </div>
                        </div>
                    </li>

                    <li
                        v-if="
                            $page.props.auth.user.type == 1 ||
                            $page.props.auth.user.type == 3
                        "
                    >
                        <Link
                            :href="route('profile.edit')"
                            class="hover:bg-gray-100 rounded-lg px-3 py-2 text-sm text-gray-700"
                        >
                            <i
                                class="fa-solid fa-user mr-2 text-primary-600"
                            ></i>
                            Profile
                        </Link>
                    </li>

                    <li v-if="$page.props.auth.user.type == 2">
                        <Link
                            :href="route('customer.account.profile')"
                            class="hover:bg-gray-100 rounded-lg px-3 py-2 text-sm text-gray-700"
                        >
                            <i
                                class="fa-solid fa-gear mr-2 text-primary-600"
                            ></i>
                            Account Setting
                        </Link>
                    </li>

                    <li>
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="hover:bg-red-50 rounded-lg px-3 py-2 text-sm text-red-600 font-medium"
                        >
                            <i class="fa-solid fa-right-from-bracket mr-2"></i>
                            Log Out
                        </Link>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <div class="flex pt-16 h-screen overflow-hidden">
        <CustomerSidebar
            v-if="props?.auth.user?.type == 2"
            :isSidebarOpen="isSidebarOpen"
            :isMobile="isMobile"
            :activeDropdown="activeDropdown"
            :toggleDropdown="toggleDropdown"
            :isActiveDropdown="isActiveDropdown"
        />
        <AdminSidebar
            v-if="props?.auth.user?.type == 1 || props?.auth?.user?.type == 3"
            :isSidebarOpen="isSidebarOpen"
            :isMobile="isMobile"
            :activeDropdown="activeDropdown"
            :toggleDropdown="toggleDropdown"
            :isActiveDropdown="isActiveDropdown"
        />
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
