<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { reactive } from "vue";
import CreditCard from "./partials/CreditCard.vue";
import ShipAddress from "./partials/ShipAddress.vue";

const props = defineProps({
    ship: Object,
    cards: Object,
    publishableKey: String,
    userAddresses: Object,
});

const openDetails = reactive({});

function toggleDetails(id) {
    openDetails[id] = !openDetails[id];
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Shipment" />
        <h1 class="text-2xl">Checkout</h1>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-4">
            <!-- Left Section (spans 2 columns on large screens) -->
            <div class="lg:col-span-2">
                <CreditCard
                    :cards="props?.cards"
                    :publishableKey="publishableKey"
                />
                <ShipAddress :userAddresses="props?.userAddresses" />
                <div class="mt-4 overflow-x-auto">
                    <div class="min-w-full bg-white rounded-lg shadow">
                        <table
                            class="min-w-full divide-y divide-gray-200 border text-center"
                        >
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border"
                                    >
                                        View
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border"
                                    >
                                        Tracking Number
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border"
                                    >
                                        Total Price
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border"
                                    >
                                        Total Weight
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border"
                                    >
                                        Remove
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                v-if="props?.ship"
                                class="bg-white divide-y divide-gray-200"
                            >
                                <template
                                    v-for="shipPackage in props.ship.packages"
                                    :key="shipPackage?.id"
                                >
                                    <tr class="hover:bg-gray-100">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap border"
                                        >
                                            <button
                                                @click="
                                                    toggleDetails(
                                                        shipPackage.id
                                                    )
                                                "
                                            >
                                                <i
                                                    :class="[
                                                        'fas',
                                                        openDetails[
                                                            shipPackage.id
                                                        ]
                                                            ? 'fa-chevron-down'
                                                            : 'fa-chevron-right',
                                                        'text-primary-500',
                                                    ]"
                                                ></i>
                                            </button>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap border"
                                        >
                                            <div class="text-sm text-gray-900">
                                                {{ shipPackage?.from }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ shipPackage?.tracking_id }}
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap border"
                                        >
                                            <div class="text-sm text-gray-900">
                                                ${{ shipPackage?.total_value }}
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap border"
                                        >
                                            <div class="text-sm text-gray-900">
                                                {{ shipPackage?.weight }} lbs
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap border text-center"
                                        >
                                            <button
                                                class="text-red-500 hover:text-red-700 text-2xl"
                                            >
                                                <i
                                                    class="fa-solid fa-xmark"
                                                ></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <tr v-if="openDetails[shipPackage.id]">
                                        <td
                                            colspan="5"
                                            class="px-6 py-4 bg-gray-50"
                                        >
                                            <div class="p-4 rounded-lg">
                                                <h4
                                                    class="text-lg font-semibold mb-2"
                                                >
                                                    Package Details
                                                </h4>
                                                <p>
                                                    <strong>From:</strong>
                                                    {{ shipPackage?.from }}
                                                </p>
                                                <p>
                                                    <strong
                                                        >Tracking
                                                        Number:</strong
                                                    >
                                                    {{
                                                        shipPackage?.tracking_id
                                                    }}
                                                </p>
                                                <p>
                                                    <strong>Value:</strong> ${{
                                                        shipPackage?.total_value
                                                    }}
                                                </p>
                                                <p>
                                                    <strong>Weight:</strong>
                                                    {{ shipPackage?.weight }}
                                                    lbs
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <tr
                                    class="bg-primary-500 text-white text-center uppercase"
                                >
                                    <td></td>
                                    <td class="px-6 py-4">
                                        <strong>Shipment Totals </strong>
                                    </td>
                                    <td>
                                        {{
                                            __to_fixed_number(
                                                props?.ship?.total_price
                                            )
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            __to_fixed_number(
                                                props?.ship?.total_weight
                                            )
                                        }}
                                        <span class="lowercase">lbs</span>
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Section -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="text-xl font-semibold mb-2">
                        Shipment Details & Options
                    </h2>
                    <div>
                        <h3>Shipment Method</h3>
                        <p class="text-gray-600">Coming Soon</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
