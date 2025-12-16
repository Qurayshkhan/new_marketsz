<template>
    <div class="bg-white border border-gray-200 rounded-lg mb-6">
        <!-- Filter Header with Toggle -->
        <div
            class="flex items-center justify-between p-4 border-b border-gray-200"
        >
            <div class="flex items-center">
                <h3 class="text-lg font-semibold text-gray-900">
                    Filter Packages
                </h3>
                <span
                    v-if="hasActiveFilters"
                    class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                >
                    {{ Object.keys(activeFilters).length }} active
                </span>
            </div>
            <div class="flex items-center space-x-2">
                <button
                    v-if="hasActiveFilters"
                    @click="clearFilters"
                    class="text-sm text-gray-500 hover:text-gray-700 flex items-center"
                >
                    <i class="fas fa-times mr-1"></i>
                    Clear Filters
                </button>
                <button
                    @click="toggleFilters"
                    class="text-sm text-blue-600 hover:text-blue-800 flex items-center"
                >
                    <i
                        :class="
                            showFilters
                                ? 'fas fa-chevron-up'
                                : 'fas fa-chevron-down'
                        "
                        class="mr-1"
                    ></i>
                    {{ showFilters ? "Hide" : "Show" }} Filters
                </button>
            </div>
        </div>

        <!-- Filter Form -->
        <div v-show="showFilters" class="p-6">
            <form @submit.prevent="applyFilters" class="space-y-4">
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                >
                    <!-- Status Filter -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Status
                        </label>
                        <select
                            v-model="filters.status"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">All Statuses</option>
                            <option value="1">Action Required</option>
                            <option value="2">In Review</option>
                            <option value="3">Ready to Send</option>
                            <option value="4">Consolidate</option>
                        </select>
                    </div>

                    <!-- Date Range Filter -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Date From
                        </label>
                        <input
                            type="date"
                            v-model="filters.date_from"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Date To
                        </label>
                        <input
                            type="date"
                            v-model="filters.date_to"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>

                    <!-- Sender Filter -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Sender/Customer
                        </label>
                        <select
                            v-model="filters.sender_id"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">All Senders</option>
                            <option
                                v-for="customer in customers"
                                :key="customer.id"
                                :value="customer.id"
                            >
                                {{ customer.name }} ({{ customer.suite }})
                            </option>
                        </select>
                    </div>

                    <!-- Suite Filter -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Suite
                        </label>
                        <select
                            v-model="filters.suite"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">All Suites</option>
                            <option
                                v-for="suite in suites"
                                :key="suite"
                                :value="suite"
                            >
                                {{ suite }}
                            </option>
                        </select>
                    </div>

                    <!-- Tracking ID Filter -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Tracking ID
                        </label>
                        <input
                            type="text"
                            v-model="filters.tracking_id"
                            placeholder="Search tracking ID..."
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>

                    <!-- Total Value Range -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Min Value
                        </label>
                        <input
                            type="number"
                            v-model="filters.total_value_min"
                            placeholder="0.00"
                            step="0.01"
                            min="0"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Max Value
                        </label>
                        <input
                            type="number"
                            v-model="filters.total_value_max"
                            placeholder="0.00"
                            step="0.01"
                            min="0"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button
                        type="button"
                        @click="clearFilters"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        Clear
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    customers: {
        type: Array,
        default: () => [],
    },
    suites: {
        type: Array,
        default: () => [],
    },
    currentFilters: {
        type: Object,
        default: () => ({}),
    },
});

const showFilters = ref(false);
const filters = ref({
    status: "",
    date_from: "",
    date_to: "",
    sender_id: "",
    suite: "",
    tracking_id: "",
    total_value_min: "",
    total_value_max: "",
});

// Initialize filters with current values
watch(
    () => props.currentFilters,
    (newFilters) => {
        Object.keys(filters.value).forEach((key) => {
            if (newFilters[key] !== undefined) {
                filters.value[key] = newFilters[key];
            }
        });
        // Show filters if there are active filters
        if (Object.values(newFilters).some((value) => value !== "")) {
            showFilters.value = true;
        }
    },
    { immediate: true }
);

const hasActiveFilters = computed(() => {
    return Object.values(filters.value).some((value) => value !== "");
});

const activeFilters = computed(() => {
    const active = {};
    Object.entries(filters.value).forEach(([key, value]) => {
        if (value !== "") {
            active[key] = value;
        }
    });
    return active;
});

const getFilterLabel = (key, value) => {
    const labels = {
        status: `Status: ${getStatusName(value)}`,
        date_from: `From: ${value}`,
        date_to: `To: ${value}`,
        sender_id: `Sender: ${getSenderName(value)}`,
        suite: `Suite: ${value}`,
        tracking_id: `Tracking: ${value}`,
        total_value_min: `Min Value: $${value}`,
        total_value_max: `Max Value: $${value}`,
    };
    return labels[key] || `${key}: ${value}`;
};

const getStatusName = (status) => {
    const statusNames = {
        1: "Action Required",
        2: "In Review",
        3: "Ready to Send",
        4: "Consolidate",
    };
    return statusNames[status] || status;
};

const getSenderName = (senderId) => {
    const customer = props.customers.find((c) => c.id == senderId);
    return customer ? `${customer.name} (${customer.suite})` : senderId;
};

const removeFilter = (key) => {
    filters.value[key] = "";
};

const clearFilters = () => {
    Object.keys(filters.value).forEach((key) => {
        filters.value[key] = "";
    });
};

const toggleFilters = () => {
    showFilters.value = !showFilters.value;
};

const applyFilters = () => {
    // Remove empty filters
    const cleanFilters = {};
    Object.entries(filters.value).forEach(([key, value]) => {
        if (value !== "") {
            cleanFilters[key] = value;
        }
    });

    // Navigate with filters
    router.get(route("admin.packages"), cleanFilters, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};
</script>
