<template>
    <div
        class="package-card bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 cursor-move"
    >
        <div class="p-4">
            <!-- Package ID and Status -->
            <div class="flex justify-between items-start mb-3">
                <div class="flex-1">
                    <h4 class="font-semibold text-gray-900 text-sm truncate">
                        {{ packageData.package_id }}
                    </h4>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ packageData.tracking_id || "No tracking ID" }}
                    </p>
                </div>
                <div class="ml-2">
                    <span
                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                        :class="getStatusBadgeClass(packageData.status)"
                    >
                        {{ packageData.status_name }}
                    </span>
                </div>
            </div>

            <!-- Package Details -->
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">From:</span>
                    <span
                        class="text-gray-900 font-medium truncate max-w-[120px]"
                    >
                        {{ packageData.from || "N/A" }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-600">Date:</span>
                    <span class="text-gray-900">
                        {{ formatDate(packageData.date_received) }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-600">Weight:</span>
                    <span class="text-gray-900">
                        {{
                            packageData.weight
                                ? `${packageData.weight} kg`
                                : "N/A"
                        }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-600">Value:</span>
                    <span class="text-gray-900 font-medium">
                        {{
                            packageData.total_value
                                ? `$${packageData.total_value}`
                                : "N/A"
                        }}
                    </span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-4 pt-3 border-t border-gray-100">
                <div class="flex justify-between items-center">
                    <Link
                        :href="route('admin.packages.edit', packageData.id)"
                        class="text-blue-600 hover:text-blue-800 text-xs font-medium flex items-center"
                    >
                        <i class="fas fa-edit mr-1"></i>
                        Edit
                    </Link>

                    <div class="flex space-x-1">
                        <button
                            v-if="
                                packageData.items &&
                                packageData.items.length > 0
                            "
                            @click="showItems = !showItems"
                            class="text-gray-500 hover:text-gray-700 text-xs"
                            title="View Items"
                        >
                            <i class="fas fa-box"></i>
                            {{ packageData.items.length }}
                        </button>

                        <button
                            v-if="
                                packageData.files &&
                                packageData.files.length > 0
                            "
                            @click="showFiles = !showFiles"
                            class="text-gray-500 hover:text-gray-700 text-xs"
                            title="View Files"
                        >
                            <i class="fas fa-file"></i>
                            {{ packageData.files.length }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Expandable Items Section -->
            <div
                v-if="
                    showItems &&
                    packageData.items &&
                    packageData.items.length > 0
                "
                class="mt-3 pt-3 border-t border-gray-100"
            >
                <h5 class="text-xs font-medium text-gray-700 mb-2">
                    Items ({{ packageData.items.length }})
                </h5>
                <div class="space-y-1 max-h-20 overflow-y-auto">
                    <div
                        v-for="item in packageData.items.slice(0, 3)"
                        :key="item.id"
                        class="text-xs text-gray-600 bg-gray-50 px-2 py-1 rounded"
                    >
                        {{ item.description || "No description" }}
                    </div>
                    <div
                        v-if="packageData.items.length > 3"
                        class="text-xs text-gray-500"
                    >
                        +{{ packageData.items.length - 3 }} more items
                    </div>
                </div>
            </div>

            <!-- Expandable Files Section -->
            <div
                v-if="
                    showFiles &&
                    packageData.files &&
                    packageData.files.length > 0
                "
                class="mt-3 pt-3 border-t border-gray-100"
            >
                <h5 class="text-xs font-medium text-gray-700 mb-2">
                    Files ({{ packageData.files.length }})
                </h5>
                <div class="space-y-1 max-h-20 overflow-y-auto">
                    <div
                        v-for="file in packageData.files.slice(0, 3)"
                        :key="file.id"
                        class="text-xs text-gray-600 bg-gray-50 px-2 py-1 rounded flex items-center"
                    >
                        <i class="fas fa-file mr-1"></i>
                        {{ file.name }}
                    </div>
                    <div
                        v-if="packageData.files.length > 3"
                        class="text-xs text-gray-500"
                    >
                        +{{ packageData.files.length - 3 }} more files
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    packageData: {
        type: Object,
        required: true,
    },
    status: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(["status-updated"]);

const showItems = ref(false);
const showFiles = ref(false);

// Get status badge class based on status
const getStatusBadgeClass = (status) => {
    const statusClasses = {
        1: "bg-red-100 text-red-800", // Action Required
        2: "bg-yellow-100 text-yellow-800", // In Review
        3: "bg-blue-100 text-blue-800", // Ready to Send
        4: "bg-green-100 text-green-800", // Consolidate
    };
    return statusClasses[status] || "bg-gray-100 text-gray-800";
};

// Format date
const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    return new Date(dateString).toLocaleDateString();
};
</script>

<style scoped>
.package-card {
    @apply transition-all duration-200;
}

.package-card:hover {
    @apply transform scale-[1.02];
}

/* Custom scrollbar for expandable sections */
.overflow-y-auto::-webkit-scrollbar {
    width: 4px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 2px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 2px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
