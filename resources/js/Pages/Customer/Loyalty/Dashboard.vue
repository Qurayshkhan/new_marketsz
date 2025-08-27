<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Loyalty Program Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Loyalty Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <StarIcon class="w-6 h-6 text-blue-600" />
                                </div>
                                <div class="ml-4">
                                    <p
                                        class="text-sm font-medium text-gray-600"
                                    >
                                        Current Points
                                    </p>
                                    <p
                                        class="text-2xl font-semibold text-gray-900"
                                    >
                                        {{ summary.current_points }}
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
                                        Total Earned
                                    </p>
                                    <p
                                        class="text-2xl font-semibold text-gray-900"
                                    >
                                        {{ summary.total_earned }}
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
                                        Total Redeemed
                                    </p>
                                    <p
                                        class="text-2xl font-semibold text-gray-900"
                                    >
                                        {{ summary.total_redeemed }}
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
                                        Total Savings
                                    </p>
                                    <p
                                        class="text-2xl font-semibold text-gray-900"
                                    >
                                        ${{
                                            summary.total_discount_earned.toFixed(
                                                2
                                            )
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loyalty Program Info -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8"
                >
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            How It Works
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <h4 class="font-medium text-blue-900 mb-2">
                                    Earning Points
                                </h4>
                                <p class="text-sm text-blue-700">
                                    Earn points on every purchase. The more you
                                    spend, the more points you earn!
                                </p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4">
                                <h4 class="font-medium text-green-900 mb-2">
                                    Redeeming Points
                                </h4>
                                <p class="text-sm text-green-700">
                                    Use your points to get discounts on future
                                    orders. Points never expire!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Recent Transactions
                        </h3>

                        <div
                            v-if="transactions.length === 0"
                            class="text-center py-8"
                        >
                            <DocumentTextIcon
                                class="w-12 h-12 text-gray-400 mx-auto mb-4"
                            />
                            <p class="text-gray-500">
                                No loyalty transactions yet.
                            </p>
                            <p class="text-sm text-gray-400">
                                Make your first purchase to start earning
                                points!
                            </p>
                        </div>

                        <div v-else class="space-y-4">
                            <div
                                v-for="transaction in transactions"
                                :key="transaction.id"
                                class="border border-gray-200 rounded-lg p-4"
                            >
                                <div class="flex items-center justify-between">
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
                                                    transaction.type === 'earn'
                                                "
                                                class="w-4 h-4 text-green-600"
                                            />
                                            <MinusIcon
                                                v-else
                                                class="w-4 h-4 text-orange-600"
                                            />
                                        </div>
                                        <div class="ml-4">
                                            <p
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ transaction.description }}
                                            </p>
                                            <p class="text-xs text-gray-500">
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
                                                'text-lg font-semibold',
                                                transaction.type === 'earn'
                                                    ? 'text-green-600'
                                                    : 'text-orange-600',
                                            ]"
                                        >
                                            {{
                                                transaction.type === "earn"
                                                    ? "+"
                                                    : "-"
                                            }}{{ transaction.points }} points
                                        </p>
                                        <p
                                            v-if="transaction.amount"
                                            class="text-sm text-gray-500"
                                        >
                                            ${{ transaction.amount.toFixed(2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 text-center">
                            <Link
                                :href="route('customer.loyalty.transactions')"
                                class="text-blue-600 hover:text-blue-800 font-medium"
                            >
                                View All Transactions →
                            </Link>
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
    StarIcon,
    PlusIcon,
    MinusIcon,
    CurrencyDollarIcon,
    DocumentTextIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    summary: Object,
    transactions: Array,
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};
</script>
