<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Loyalty Program Dashboard
                </h2>
                <Link
                    :href="route('admin.loyalty.rules')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
                >
                    Manage Rules
                </Link>
            </div>
        </template>

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
                                    <UsersIcon class="w-6 h-6 text-blue-600" />
                                </div>
                                <div class="ml-4">
                                    <p
                                        class="text-sm font-medium text-gray-600"
                                    >
                                        Active Users
                                    </p>
                                    <p
                                        class="text-2xl font-semibold text-gray-900"
                                    >
                                        {{ stats.total_users_with_points }}
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
                                    <PlusIcon class="w-6 h-6 text-green-600" />
                                </div>
                                <div class="ml-4">
                                    <p
                                        class="text-sm font-medium text-gray-600"
                                    >
                                        Points Issued
                                    </p>
                                    <p
                                        class="text-2xl font-semibold text-gray-900"
                                    >
                                        {{ stats.total_points_issued }}
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
                                <div class="p-2 bg-orange-100 rounded-lg">
                                    <MinusIcon
                                        class="w-6 h-6 text-orange-600"
                                    />
                                </div>
                                <div class="ml-4">
                                    <p
                                        class="text-sm font-medium text-gray-600"
                                    >
                                        Points Redeemed
                                    </p>
                                    <p
                                        class="text-2xl font-semibold text-gray-900"
                                    >
                                        {{ stats.total_points_redeemed }}
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
                                    <CurrencyDollarIcon
                                        class="w-6 h-6 text-purple-600"
                                    />
                                </div>
                                <div class="ml-4">
                                    <p
                                        class="text-sm font-medium text-gray-600"
                                    >
                                        Total Discounts
                                    </p>
                                    <p
                                        class="text-2xl font-semibold text-gray-900"
                                    >
                                        ${{
                                            stats.total_discount_given.toFixed(
                                                2
                                            )
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Users and Recent Transactions -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Top Loyalty Users -->
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6">
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Top Loyalty Users
                            </h3>

                            <div
                                v-if="topUsers.length === 0"
                                class="text-center py-8"
                            >
                                <UsersIcon
                                    class="w-12 h-12 text-gray-400 mx-auto mb-4"
                                />
                                <p class="text-gray-500">
                                    No users with loyalty points yet.
                                </p>
                            </div>

                            <div v-else class="space-y-4">
                                <div
                                    v-for="(user, index) in topUsers"
                                    :key="user.id"
                                    class="flex items-center justify-between p-3 border border-gray-200 rounded-lg"
                                >
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center"
                                        >
                                            <span
                                                class="text-sm font-medium text-blue-600"
                                                >{{ index + 1 }}</span
                                            >
                                        </div>
                                        <div class="ml-3">
                                            <p
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ user.name }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ user.email }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p
                                            class="text-lg font-semibold text-blue-600"
                                        >
                                            {{ user.loyalty_points }} points
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 text-center">
                                <Link
                                    :href="route('admin.loyalty.users')"
                                    class="text-blue-600 hover:text-blue-800 font-medium"
                                >
                                    View All Users →
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6">
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Recent Transactions
                            </h3>

                            <div
                                v-if="recentTransactions.length === 0"
                                class="text-center py-8"
                            >
                                <DocumentTextIcon
                                    class="w-12 h-12 text-gray-400 mx-auto mb-4"
                                />
                                <p class="text-gray-500">
                                    No loyalty transactions yet.
                                </p>
                            </div>

                            <div v-else class="space-y-4">
                                <div
                                    v-for="transaction in recentTransactions"
                                    :key="transaction.id"
                                    class="border border-gray-200 rounded-lg p-3"
                                >
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <div class="flex items-center">
                                            <div
                                                :class="[
                                                    'p-2 rounded-lg',
                                                    transaction.type === 'earn'
                                                        ? 'bg-green-100'
                                                        : 'bg-orange-100',
                                                ]"
                                            >
                                                <PlusIcon
                                                    v-if="
                                                        transaction.type ===
                                                        'earn'
                                                    "
                                                    class="w-4 h-4 text-green-600"
                                                />
                                                <MinusIcon
                                                    v-else
                                                    class="w-4 h-4 text-orange-600"
                                                />
                                            </div>
                                            <div class="ml-3">
                                                <p
                                                    class="text-sm font-medium text-gray-900"
                                                >
                                                    {{ transaction.user.name }}
                                                </p>
                                                <p
                                                    class="text-xs text-gray-500"
                                                >
                                                    {{
                                                        formatDate(
                                                            transaction.created_at
                                                        )
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p
                                                :class="[
                                                    'text-sm font-semibold',
                                                    transaction.type === 'earn'
                                                        ? 'text-green-600'
                                                        : 'text-orange-600',
                                                ]"
                                            >
                                                {{
                                                    transaction.type === "earn"
                                                        ? "+"
                                                        : "-"
                                                }}{{ transaction.points }}
                                                points
                                            </p>
                                            <p
                                                v-if="transaction.amount"
                                                class="text-xs text-gray-500"
                                            >
                                                ${{
                                                    transaction.amount.toFixed(
                                                        2
                                                    )
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 text-center">
                                <Link
                                    :href="route('admin.loyalty.transactions')"
                                    class="text-blue-600 hover:text-blue-800 font-medium"
                                >
                                    View All Transactions →
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
    UsersIcon,
    PlusIcon,
    MinusIcon,
    CurrencyDollarIcon,
    DocumentTextIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    stats: Object,
    topUsers: Array,
    recentTransactions: Array,
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};
</script>
