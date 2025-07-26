<script setup>
import Pagination from "@/Components/Pagination.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
    status: {
        type: String,
    },
    packages: Object,
});
</script>
<template>
    <AuthenticatedLayout>
        <Head title="Packages" />
        <div class="container-fluid">
            <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                {{ status }}
            </div>
            <div class="grid grid-cols-1">
                <div class="w-full">
                    <div class="card">
                        <div class="flex max-lg:flex-col justify-between gap-3">
                            <div class="card-title">Packages</div>
                            <div>
                                <Link :href="route('admin.packages.create')">
                                    <PrimaryButton
                                        >+ Create Package</PrimaryButton
                                    >
                                </Link>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="overflow-x-auto">
                                <table class="table border">
                                    <!-- head -->
                                    <thead class="text-black">
                                        <tr>
                                            <th class="border">From</th>
                                            <th class="border">Package Id</th>
                                            <th class="border">Tracking Id</th>
                                            <th class="border">
                                                Date Received
                                            </th>
                                            <th class="border">Status</th>
                                            <th class="border">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- row 1 -->
                                        <tr
                                            v-for="list in props.packages.data"
                                            :key="list?.id"
                                        >
                                            <td class="border">
                                                {{ list?.from }}
                                            </td>
                                            <td class="border">
                                                {{ list?.package_id }}
                                            </td>
                                            <td class="border">
                                                {{ list?.tracking_id }}
                                            </td>
                                            <td class="border">
                                                {{ list?.date_received }}
                                            </td>
                                            <td class="border">
                                                {{ list?.status_name }}
                                            </td>
                                            <td class="border text-center">
                                                <Link
                                                    :href="
                                                        route(
                                                            'admin.packages.edit',
                                                            list?.id
                                                        )
                                                    "
                                                >
                                                    <i
                                                        class="fa fa-angle-right"
                                                        aria-hidden="true"
                                                    ></i>
                                                </Link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <Pagination
                                :links="props.packages.links"
                                :from="props.packages.from"
                                :to="props.packages.to"
                                :total="props.packages.total"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
