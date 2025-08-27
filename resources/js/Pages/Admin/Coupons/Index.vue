<template>
    <AuthenticatedLayout>
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Coupon Management
            </h2>
            <Link
                :href="route('admin.coupons.create')"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2"
            >
                <PlusIcon class="w-4 h-4" />
                Create Coupon
            </Link>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <TicketIcon class="w-6 h-6 text-blue-600" />
                                </div>
                                <div class="ml-4">
                                    <p
                                        class="text-sm font-medium text-gray-600"
                                    >
                                        Total Coupons
                                    </p>
                                    <p
                                        class="text-2xl font-semibold text-gray-900"
                                    >
                                        {{ stats.total_coupons }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <CheckCircleIcon
                                        class="w-6 h-6 text-green-600"
                                    />
                                </div>
                                <div class="ml-4">
                                    <p
                                        class="text-sm font-medium text-gray-600"
                                    >
                                        Active Coupons
                                    </p>
                                    <p
                                        class="text-2xl font-semibold text-gray-900"
                                    >
                                        {{ stats.active_coupons }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-2 bg-red-100 rounded-lg">
                                    <XCircleIcon class="w-6 h-6 text-red-600" />
                                </div>
                                <div class="ml-4">
                                    <p
                                        class="text-sm font-medium text-gray-600"
                                    >
                                        Expired Coupons
                                    </p>
                                    <p
                                        class="text-2xl font-semibold text-gray-900"
                                    >
                                        {{ stats.expired_coupons }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-2 bg-purple-100 rounded-lg">
                                    <UsersIcon
                                        class="w-6 h-6 text-purple-600"
                                    />
                                </div>
                                <div class="ml-4">
                                    <p
                                        class="text-sm font-medium text-gray-600"
                                    >
                                        Total Usage
                                    </p>
                                    <p
                                        class="text-2xl font-semibold text-gray-900"
                                    >
                                        {{ stats.total_usage }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filters -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6"
                >
                    <div class="p-6">
                        <form @submit.prevent="search" class="flex gap-4">
                            <div class="flex-1">
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search coupons by code or description..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>
                            <button
                                type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md"
                            >
                                Search
                            </button>
                            <button
                                type="button"
                                @click="clearSearch"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md"
                            >
                                Clear
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Coupons Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Code
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Type
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Value
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Usage
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Status
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Expiry
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="coupon in coupons.data"
                                        :key="coupon.id"
                                    >
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ coupon.code }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ coupon.description }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                :class="[
                                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                    coupon.discount_type ===
                                                    'percentage'
                                                        ? 'bg-blue-100 text-blue-800'
                                                        : 'bg-green-100 text-green-800',
                                                ]"
                                            >
                                                {{ coupon.discount_type }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{
                                                coupon.discount_type ===
                                                "percentage"
                                                    ? coupon.discount_value +
                                                      "%"
                                                    : "$" +
                                                      coupon.discount_value
                                            }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ coupon.used_count }} /
                                            {{ coupon.usage_limit || "∞" }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button
                                                @click="toggleStatus(coupon)"
                                                :class="[
                                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full cursor-pointer',
                                                    coupon.is_active
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-red-100 text-red-800',
                                                ]"
                                            >
                                                {{
                                                    coupon.is_active
                                                        ? "Active"
                                                        : "Inactive"
                                                }}
                                            </button>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{
                                                coupon.expiry_date
                                                    ? formatDate(
                                                          coupon.expiry_date
                                                      )
                                                    : "No expiry"
                                            }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                        >
                                            <div class="flex gap-2">
                                                <Link
                                                    :href="
                                                        route(
                                                            'admin.coupons.edit',
                                                            coupon.id
                                                        )
                                                    "
                                                    class="text-blue-600 hover:text-blue-900"
                                                >
                                                    Edit
                                                </Link>
                                                <button
                                                    @click="
                                                        deleteCoupon(coupon)
                                                    "
                                                    class="text-red-600 hover:text-red-900"
                                                >
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            <Pagination :links="coupons.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import {
    PlusIcon,
    TicketIcon,
    CheckCircleIcon,
    XCircleIcon,
    UsersIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    coupons: Object,
    stats: Object,
    search: String,
});

const searchQuery = ref(props.search || "");

const search = () => {
    router.get(
        route("admin.coupons.index"),
        { search: searchQuery.value },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

const clearSearch = () => {
    searchQuery.value = "";
    router.get(
        route("admin.coupons.index"),
        {},
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

const toggleStatus = (coupon) => {
    router.put(
        route("admin.coupons.toggle-status", coupon.id),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                // Update the coupon status in the local state
                coupon.is_active = !coupon.is_active;
            },
        }
    );
};

const deleteCoupon = (coupon) => {
    if (confirm("Are you sure you want to delete this coupon?")) {
        router.delete(route("admin.coupons.destroy", coupon.id), {
            preserveState: true,
            preserveScroll: true,
        });
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString();
};
</script>
