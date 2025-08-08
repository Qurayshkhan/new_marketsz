<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    shipDetails: Object,
    packingOptions: Array,
    shippingPreferenceOption: Array,
});

const formatCurrency = (amount) => `$${Number(amount || 0.0).toFixed(2)}`;
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Shipment Detail" />

        <div class="p-6 space-y-8">
            <!-- HEADER -->
            <div class="flex flex-wrap justify-between items-center gap-4">
                <h1 class="text-3xl font-bold flex items-center gap-2">
                    📦 Shipment #{{ shipDetails.id }}
                </h1>
                <div class="flex flex-wrap gap-2">
                    <span
                        class="badge px-4 py-2 text-sm font-semibold"
                        :class="{
                            'badge-warning': shipDetails.status === 'pending',
                            'badge-info': shipDetails.status === 'shipped',
                            'badge-success': shipDetails.status === 'delivered',
                            'badge-error': shipDetails.status === 'cancelled',
                        }"
                    >
                        {{ shipDetails.status }}
                    </span>
                    <span
                        class="badge px-4 py-2 text-sm font-semibold"
                        :class="{
                            'badge-ghost':
                                shipDetails.invoice_status === 'pending',
                            'badge-success':
                                shipDetails.invoice_status === 'paid',
                            'badge-error':
                                shipDetails.invoice_status === 'unpaid',
                        }"
                    >
                        Invoice: {{ shipDetails.invoice_status }}
                    </span>
                </div>
            </div>

            <!-- INFO CARDS -->
            <div class="grid gap-6 md:grid-cols-3">
                <!-- Shipment Info -->
                <div class="card bg-white shadow-md">
                    <div class="card-body">
                        <h2 class="card-title">📦 Shipment Info</h2>
                        <p>
                            <span class="font-semibold">Tracking:</span>
                            {{ shipDetails.tracking_number }}
                        </p>
                        <p>
                            <span class="font-semibold">Weight:</span>
                            {{ shipDetails.total_weight }} kg
                        </p>
                        <p>
                            <span class="font-semibold">Total Price:</span>
                            {{ formatCurrency(shipDetails.total_price) }}
                        </p>
                        <p>
                            <span class="font-semibold">Ship Payment:</span>
                            {{
                                formatCurrency(
                                    shipDetails.estimated_shipping_charges
                                )
                            }}
                        </p>
                        <p>
                            <span class="font-semibold">Handling Fee:</span>
                            {{ formatCurrency(shipDetails.handling_fee) }}
                        </p>
                        <p>
                            <span class="font-semibold"
                                >Estimated Charges:</span
                            >
                            {{
                                formatCurrency(
                                    shipDetails.estimated_shipping_charges
                                )
                            }}
                        </p>
                        <p>
                            <span class="font-semibold">National id:</span>
                            {{ shipDetails.national_id }}
                        </p>
                        <p>
                            <span class="font-semibold">Created:</span>
                            {{
                                new Date(
                                    shipDetails.created_at
                                ).toLocaleString()
                            }}
                        </p>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="card bg-white shadow-md">
                    <div class="card-body">
                        <h2 class="card-title">👤 Customer Info</h2>
                        <p class="font-bold">{{ shipDetails.user.name }}</p>
                        <p>{{ shipDetails.user.email }}</p>
                        <p>{{ shipDetails.user.phone }}</p>
                        <p>Suite: {{ shipDetails.user.suite }}</p>
                        <p>Country: {{ shipDetails.user.country }}</p>
                    </div>
                </div>

                <!-- Address Info -->
                <div class="card bg-white shadow-md">
                    <div class="card-body">
                        <h2 class="card-title">📍 Shipping Address</h2>
                        <p>{{ shipDetails.user_address.full_name }}</p>
                        <p>{{ shipDetails.user_address.address_line_1 }}</p>
                        <p v-if="shipDetails.user_address.address_line_2">
                            {{ shipDetails.user_address.address_line_2 }}
                        </p>
                        <p>
                            {{ shipDetails.user_address.city }},
                            {{ shipDetails.user_address.state }}
                        </p>
                        <p>
                            {{ shipDetails.user_address.country }} -
                            {{ shipDetails.user_address.postal_code }}
                        </p>
                        <p>📞 {{ shipDetails.user_address.phone_number }}</p>
                    </div>
                </div>
            </div>

            <!-- INTERNATIONAL SHIPPING -->
            <div class="card bg-white shadow-md">
                <div class="card-body">
                    <h2 class="card-title">🌍 International Shipping Option</h2>
                    <p class="font-bold">
                        {{ shipDetails.international_shipping.title }}
                    </p>
                    <p class="text-sm text-gray-500">
                        {{ shipDetails.international_shipping.description }}
                    </p>
                </div>
            </div>

            <!-- PACKING OPTIONS -->
            <div class="card bg-white shadow-md">
                <div class="card-body">
                    <h2 class="card-title">📦 Packing Options</h2>
                    <ul class="list-disc pl-6">
                        <li v-for="opt in packingOptions" :key="opt.id">
                            <div class="flex justify-between">
                                <span>
                                    <span class="font-semibold">{{
                                        opt.title
                                    }}</span>
                                    - {{ opt.description }}
                                </span>
                                <span>{{ formatCurrency(opt.price) }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- SHIPPING PREFERENCE -->
            <div class="card bg-white shadow-md">
                <div class="card-body">
                    <h2 class="card-title">🚚 Shipping Preferences</h2>
                    <ul class="list-disc pl-6">
                        <li
                            v-for="opt in shippingPreferenceOption"
                            :key="opt.id"
                        >
                            <div class="flex justify-between">
                                <span>
                                    <span class="font-semibold">{{
                                        opt.title
                                    }}</span>
                                    - {{ opt.description }}
                                </span>
                                <span v-if="opt.price">{{
                                    formatCurrency(opt.price)
                                }}</span>
                                <span v-else>$0.00</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- PACKAGES TABLE -->
            <div class="card bg-white shadow-md">
                <div class="card-body">
                    <h2 class="card-title">📦 Packages</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead
                                class="bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 uppercase text-sm"
                            >
                                <tr>
                                    <th class="p-4 text-left">ID</th>
                                    <th class="p-4 text-left">Package ID</th>
                                    <th class="p-4 text-left">Tracking ID</th>
                                    <th class="p-4 text-left">From</th>
                                    <th class="p-4 text-left">Weight</th>
                                    <th class="p-4 text-left">Value</th>
                                    <th class="p-4 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(pkg, index) in shipDetails.packages"
                                    :key="pkg.id"
                                    class="border-b hover:bg-gray-50 transition"
                                >
                                    <td class="p-4 font-medium text-gray-700">
                                        {{ index + 1 }}
                                    </td>
                                    <td class="p-4">{{ pkg.package_id }}</td>
                                    <td class="p-4">{{ pkg.tracking_id }}</td>
                                    <td class="p-4">{{ pkg.from }}</td>
                                    <td class="p-4">{{ pkg.weight }} kg</td>
                                    <td class="p-4">
                                        {{ formatCurrency(pkg.total_value) }}
                                    </td>
                                    <td class="p-4 text-end">
                                        <span class="badge badge-secondary">{{
                                            pkg.status_name
                                        }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
