<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { onMounted, reactive, watch } from "vue";
import CreditCard from "./partials/CreditCard.vue";
import ShipAddress from "./partials/ShipAddress.vue";
import Delete from "./Delete.vue";
import Radiobox from "@/Components/Radiobox.vue";
import { ref } from "vue";
import Checkbox from "@/Components/Checkbox.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from "axios";
import { useToast } from "vue-toastification";
import NationalId from "./NationalId.vue";
import { Head } from "@inertiajs/vue3";
import Checkout from "./Checkout.vue";

const props = defineProps({
    ship: Object,
    cards: Object,
    publishableKey: String,
    userAddresses: Object,
    internationalShippingMethod: Object,
    packingOptions: Object,
    userPreferences: Object,
    shippingPreferenceOptions: Object,
});
let safeJsonParse = (json, fallback = []) => {
    if (typeof json !== "string" || json.trim() === "") {
        return fallback;
    }

    try {
        return JSON.parse(json);
    } catch {
        return fallback;
    }
};

const toast = useToast();
const openDetails = reactive({});
const selectedShipMethod = ref(
    props?.userPreferences
        ? Number(props?.userPreferences?.international_shipping_option)
        : 0.0
);
const selectedPackingOption = ref(
    safeJsonParse(
        props?.userPreferences?.packing_option
            ? props?.userPreferences?.packing_option
            : []
    )
);
const selectedShippingPreferenceOption = ref(
    safeJsonParse(
        props?.userPreferences?.shipping_preference_option
            ? props?.userPreferences?.shipping_preference_option
            : []
    )
);

const selectedCardId = ref(null);
const setSelectedAddressId = ref(null);

// const isUpdatingRate = ref(false);

const checkoutAmount = ref(props?.ship?.handling_fee || 0.0);
const internationalShippingAmount = ref(0.0);
const packingOptionAmount = ref(0.0);
const shippingPreferenceOptionAmount = ref(0.0);
function toggleDetails(id) {
    openDetails[id] = !openDetails[id];
}

const calculateShippingCost = () => {
    checkoutAmount.value = props?.ship?.handling_fee || 0.0;
    const data = {
        shipMethod: selectedShipMethod.value,
        packingOption: selectedPackingOption.value,
        shippingPreferenceOption: selectedShippingPreferenceOption.value,
        shipWeight: props.ship?.total_weight || 0,
    };

    axios
        .post(route("customer.shipment.calculateShippingCost"), data)
        .then((response) => {
            console.log("Shipping cost calculated:", response.data);
            const { data, success } = response.data;
            if (success) {
                if (data?.international_shipping_amount) {
                    checkoutAmount.value += data?.international_shipping_amount;
                    internationalShippingAmount.value =
                        data?.international_shipping_amount;
                }
                if (data?.packing_option_amount) {
                    checkoutAmount.value += data?.packing_option_amount;
                    packingOptionAmount.value = data?.packing_option_amount;
                }
                if (data?.shipping_preference_option_amount) {
                    checkoutAmount.value +=
                        data?.shipping_preference_option_amount;
                    shippingPreferenceOptionAmount.value =
                        data?.shipping_preference_option_amount;
                }
            } else {
                toast.error(
                    response.data.message || "Error calculating shipping cost"
                );
            }
        })
        .catch((error) => {
            toast.error(
                error.response?.data?.message ||
                    "An error occurred while calculating shipping cost"
            );
        });
};

const selectedCard = (card) => {
    selectedCardId.value = card;
};
const setSelectedAddress = (address) => {
    setSelectedAddressId.value = address;
};

watch(
    [
        selectedShipMethod,
        selectedPackingOption,
        selectedShippingPreferenceOption,
    ],
    () => {
        calculateShippingCost();
    }
);

onMounted(() => {
    calculateShippingCost();
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Shipment Checkout" />
        <h1 class="text-2xl">Checkout</h1>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-4">
            <div class="lg:col-span-2">
                <div class="mb-4 text-end">
                    <div class="w-60 ml-auto">
                        <p
                            class="flex flex-col items-center justify-between text-gray-700 mb-2"
                        >
                            Estimated shipping cost: ${{ checkoutAmount }}
                        </p>
                    </div>
                </div>
                <CreditCard
                    :cards="props?.cards"
                    :publishableKey="publishableKey"
                    @selectedCard="selectedCard"
                />
                <ShipAddress
                    :userAddresses="props?.userAddresses"
                    @setSelectedAddress="setSelectedAddress"
                />
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
                                            <Delete
                                                :id="props?.ship?.id"
                                                :packageId="shipPackage?.id"
                                            />
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
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3>Shipment Method</h3>
                            <p class="text-gray-600">
                                ${{ internationalShippingAmount }}
                            </p>
                        </div>
                        <div>
                            <ul>
                                <li
                                    v-for="method in props.internationalShippingMethod"
                                    :key="method.id"
                                >
                                    <div>
                                        <label
                                            :for="`international_${method?.id}`"
                                        >
                                            <Radiobox
                                                name="shipping_method"
                                                v-model="selectedShipMethod"
                                                :value="Number(method?.id)"
                                                :id="`international_${method?.id}`"
                                            />
                                            <span class="ml-2">{{
                                                method?.title
                                            }}</span>
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3>Packing Option</h3>
                            <p class="text-gray-600">
                                ${{ packingOptionAmount }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-2">
                                (Based on 8lbs weight)
                            </p>
                        </div>
                        <div>
                            <ul>
                                <li
                                    v-for="packingOption in props?.packingOptions"
                                    :key="packingOption?.id"
                                >
                                    <div>
                                        <label
                                            :for="`packing_option_${packingOption?.id}`"
                                        >
                                            <Checkbox
                                                name="packing_option"
                                                v-model="selectedPackingOption"
                                                :value="
                                                    Number(packingOption?.id)
                                                "
                                                :id="`packing_option_${packingOption?.id}`"
                                            />
                                            <span class="ml-2">{{
                                                packingOption?.title
                                            }}</span>
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3>Shipping Preferences</h3>
                            <p class="text-gray-600">
                                ${{ shippingPreferenceOptionAmount }}
                            </p>
                        </div>

                        <div>
                            <ul>
                                <li
                                    v-for="shippingPreferenceOption in props?.shippingPreferenceOptions"
                                    :key="shippingPreferenceOption?.id"
                                >
                                    <div>
                                        <label
                                            :for="`shipping_preference_options${shippingPreferenceOption?.id}`"
                                        >
                                            <Checkbox
                                                name="shipping_preference_option"
                                                v-model="
                                                    selectedShippingPreferenceOption
                                                "
                                                :value="
                                                    Number(
                                                        shippingPreferenceOption?.id
                                                    )
                                                "
                                                :id="`shipping_preference_option${shippingPreferenceOption?.id}`"
                                            />
                                            <span class="ml-2">{{
                                                shippingPreferenceOption?.title
                                            }}</span>
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3>Export Documentation</h3>
                            <p class="text-gray-600">$0.00</p>
                        </div>
                        <div>
                            <ul>
                                <li>
                                    <div>
                                        <NationalId :ship="props?.ship" />
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3>Handling Fee</h3>
                            <p class="text-gray-600">$10.00</p>
                        </div>
                    </div>
                    <hr />
                    <div
                        class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-md mx-auto"
                    >
                        <div class="border-b pb-4 mb-4">
                            <div
                                class="flex items-center justify-between text-base font-semibold text-gray-700 mb-3"
                            >
                                <h3>Subtotal</h3>
                                <p class="text-gray-800">
                                    ${{ __to_fixed_number(checkoutAmount) }}
                                </p>
                            </div>
                            <div
                                class="flex items-center justify-between text-sm text-gray-600"
                            >
                                <p>Package Level Charges</p>
                                <p>$0.00</p>
                            </div>
                        </div>

                        <div class="text-center mb-4">
                            <h3 class="text-lg font-medium text-gray-800">
                                Estimated Shipping Charges
                            </h3>
                            <p class="text-2xl font-bold text-primary-600">
                                ${{ __to_fixed_number(checkoutAmount) }}
                            </p>
                        </div>

                        <div class="text-sm text-gray-600 mb-4 text-center">
                            Your selected card will be used for this
                            transaction.
                        </div>

                        <div class="text-center">
                            <Checkout
                                :ship="props?.ship"
                                :selectedShipMethod="selectedShipMethod"
                                :selectedPackingOption="selectedPackingOption"
                                :selectedShippingPreferenceOption="
                                    selectedShippingPreferenceOption
                                "
                                :checkoutAmount="checkoutAmount"
                                :selectedCardId="selectedCardId"
                                :selectedAddressId="setSelectedAddressId"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
