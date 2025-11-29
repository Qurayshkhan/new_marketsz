<template>
    <AuthenticatedLayout>
        <Head title="Package Kanban Board" />

        <div class="container-fluid">
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            Package Status Management
                        </h1>
                        <p class="mt-1 text-gray-600">
                            Drag and drop packages to update their status
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <Link
                            :href="route('admin.packages')"
                            class="btn btn-outline"
                        >
                            <i class="mr-2 fas fa-list"></i>
                            Table View
                        </Link>
                        <!-- <Link
                            :href="route('admin.packages.create')"
                            class="btn btn-primary"
                        >
                            <i class="mr-2 fas fa-plus"></i>
                            Create Package
                        </Link> -->
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3">
                <div class="p-4 border border-red-200 rounded-lg bg-red-50">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i
                                class="text-2xl text-red-600 fas fa-exclamation-triangle"
                            ></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-red-600">
                                Action Required
                            </p>
                            <p class="text-2xl font-bold text-red-900">
                                {{ getPackagesByStatus(1).length }}
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="p-4 border border-yellow-200 rounded-lg bg-yellow-50"
                >
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i
                                class="text-2xl text-yellow-600 fas fa-search"
                            ></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-yellow-600">
                                In Review
                            </p>
                            <p class="text-2xl font-bold text-yellow-900">
                                {{ getPackagesByStatus(2).length }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-4 border border-blue-200 rounded-lg bg-blue-50">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i
                                class="text-2xl text-blue-600 fas fa-shipping-fast"
                            ></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-blue-600">
                                Ready to Send
                            </p>
                            <p class="text-2xl font-bold text-blue-900">
                                {{ getPackagesByStatus(3).length }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- <div class="p-4 border border-green-200 rounded-lg bg-green-50">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="text-2xl text-green-600 fas fa-boxes"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-600">
                                Consolidate
                            </p>
                            <p class="text-2xl font-bold text-green-900">
                                {{ getPackagesByStatus(4).length }}
                            </p>
                        </div>
                    </div>
                </div> -->
            </div>

            <!-- Kanban Board -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6">
                    <KanbanBoard
                        :packages="packages"
                        @status-updated="handleStatusUpdate"
                    />
                </div>
            </div>

            <!-- Pagination -->
            <!-- <div class="mt-6">
                <Pagination
                    :links="packages.links"
                    :from="packages.from"
                    :to="packages.to"
                    :total="packages.total"
                />
            </div> -->
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import KanbanBoard from "@/Components/KanbanBoard.vue";
import Pagination from "@/Components/Pagination.vue";
import { useToast } from "vue-toastification";

const toast = useToast();

const props = defineProps({
    packages: {
        type: Object,
        required: true,
    },
});

// Helper function to get packages by status
const getPackagesByStatus = (status) => {
    return props.packages.filter((p) => p.status === status);
};
const statusNames = {
    1: "Action Required",
    2: "In Review",
    3: "Ready to Send",
    4: "Consolidate",
};

// Handle status updates from KanbanBoard
const handleStatusUpdate = (data) => {
    const packageIndex = props.packages.findIndex(
        (p) => p.id === data.packageId
    );
    if (packageIndex !== -1) {
        props.packages[packageIndex].status = data.newStatus;
        props.packages[packageIndex].status_name = statusNames[data.newStatus]; // 👈 update label
    }

    // toast.success("Package status updated successfully!");
};
</script>

<style scoped>
/* Custom styles for the Kanban page */
.btn {
    @apply px-4 py-2 rounded-lg font-medium transition-colors duration-200;
}

.btn-primary {
    @apply bg-blue-600 text-white hover:bg-blue-700;
}

.btn-outline {
    @apply border border-gray-300 text-gray-700 hover:bg-gray-50;
}
</style>
