<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
    shipments: Array,
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="My Shipments" />

        <div class="p-6">
            <h1 class="mb-6 text-3xl font-bold text-gray-800">My Shipments</h1>

            <!-- Table wrapper without overflow clipping -->
            <div class="bg-white rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead
                            class="text-sm text-gray-700 uppercase bg-gradient-to-r from-gray-100 to-gray-200"
                        >
                            <tr>
                                <th class="p-4 text-left">ID</th>
                                <th class="p-4 text-left">Tracking Number</th>
                                <!-- <th class="p-4 text-left">Total Weight</th> -->
                                <th class="p-4 text-left">Total Price</th>
                                <!-- <th class="p-4 text-left">Ship Payment</th> -->
                                <th class="p-4 text-left">Status</th>
                                <!-- <th class="p-4 text-left">Invoice Status</th> -->
                                <!-- <th class="p-4 text-right">Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="shipment in shipments"
                                :key="shipment.id"
                                class="transition border-b hover:bg-gray-50"
                            >
                                <td class="p-4 font-medium text-gray-700">
                                    {{ shipment.id }}
                                </td>
                                <td class="p-4">
                                    {{ shipment.tracking_number || "N/A" }}
                                </td>
                                <!-- <td class="p-4">
                                    {{ shipment.total_weight }} kg
                                </td> -->
                                <td class="p-4">
                                    ${{ shipment.total_price.toFixed(2) }}
                                </td>
                                <!-- <td class="p-4">
                                    ${{
                                        shipment.estimated_shipping_charges.toFixed(
                                            2
                                        )
                                    }}
                                </td> -->
                                <td class="p-4">
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full"
                                        :class="{
                                            'bg-yellow-100 text-yellow-800':
                                                shipment.status === 'pending',
                                            'bg-blue-100 text-blue-800':
                                                shipment.status === 'shipped',
                                            'bg-green-100 text-green-800':
                                                shipment.status === 'delivered',
                                            'bg-red-100 text-red-800':
                                                shipment.status === 'cancelled',
                                        }"
                                    >
                                        {{ shipment.status }}
                                    </span>
                                </td>
                                <!-- <td class="p-4 capitalize">
                                    {{ shipment.invoice_status }}
                                </td> -->
                                <!-- <td class="relative p-4 text-right">
                                    <div class="dropdown dropdown-end">
                                        <label
                                            tabindex="0"
                                            class="btn btn-ghost btn-circle btn-sm"
                                        >
                                            <i
                                                class="fa-solid fa-ellipsis-vertical"
                                            ></i>
                                        </label>
                                        <ul
                                            tabindex="0"
                                            class="dropdown-content z-[9999] menu p-2 shadow-lg bg-white rounded-box w-40"
                                        >
                                            <li>
                                                <Link
                                                    :href="
                                                        route(
                                                            'customer.shipment.details',
                                                            {
                                                                ship: shipment.id,
                                                            }
                                                        )
                                                    "
                                                    >View</Link
                                                >
                                            </li>
                                        </ul>
                                    </div>
                                </td> -->
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
