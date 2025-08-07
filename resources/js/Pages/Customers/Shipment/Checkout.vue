<script setup>
import DangerButton from "@js/Components/DangerButton.vue";
import SecondaryButton from "@js/Components/SecondaryButton.vue";
import Modal from "@js/Components/Modal.vue";

import { ref, watch } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";
const toast = useToast();
const props = defineProps({
    ship: String,
    selectedShipMethod: [Number, Object],
    selectedPackingOption: [Array, Number],
    selectedShippingPreferenceOption: [Array, Number],
    checkoutAmount: Number,
    selectedCardId: Number,
    selectedAddressId: Number,
});

console.log(props.selectedCardId, props?.selectedAddressId);
const confirmingRecordDeletion = ref(false);

const confirmRecordDeletion = () => {
    confirmingRecordDeletion.value = true;
};

const closeModal = () => {
    confirmingRecordDeletion.value = false;
};

const form = useForm({
    id: props.ship?.id || "",
    international_shipping_option_id: props.selectedShipMethod || "",
    packing_option_id: props.selectedPackingOption || "",
    shipping_preference_option_id: props.selectedShippingPreferenceOption || "",
    estimated_shipping_charges: props.checkoutAmount || 0.0,
    subtotal: props.checkoutAmount || 0.0,
    card_id: props.selectedCardId || null,
    user_address_id: props.selectedAddressId || null,
});
const checkout = (event) => {
    event.preventDefault();
    console.log(form.card_id);
    if (!form.card_id) {
        toast.error("Please select your card.");
        return;
    }

    if (!form.user_address_id) {
        toast.error("Please select your address.");
        return;
    }

    form.post(route("customer.ship.checkout"), {
        onFinish: () => {
            closeModal();
        },
    });
};

watch(
    () => ({
        shipId: props.ship?.id,
        method: props.selectedShipMethod,
        packing: props.selectedPackingOption,
        preference: props.selectedShippingPreferenceOption,
        amount: props.checkoutAmount,
        card: props.selectedCardId,
        address: props.selectedAddressId,
    }),
    (newVals) => {
        form.id = newVals.shipId || "";
        form.international_shipping_option_id = newVals.method || "";
        form.packing_option_id = newVals.packing || "";
        form.shipping_preference_option_id = newVals.preference || "";
        form.estimated_shipping_charges = newVals.amount || 0.0;
        form.subtotal = newVals.amount || 0.0;
        form.card_id = newVals.card || null;
        form.user_address_id = newVals.address || null;
    },
    { deep: true, immediate: true }
);
</script>

<template>
    <div class="">
        <button
            @click="confirmRecordDeletion"
            class="bg-primary-500 text-white w-full py-2 px-4 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-opacity-50"
        >
            Ship Now
        </button>

        <Modal :show="confirmingRecordDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-center text-lg font-medium text-gray-900">
                    Your credit card will be charged ${{ checkoutAmount }} for
                    this transaction.
                </h2>
                <div class="flex mt-6 gap-4 justify-end">
                    <SecondaryButton @click="closeModal"
                        >Cancel</SecondaryButton
                    >
                    <DangerButton @click="checkout">Submit</DangerButton>
                </div>
            </div>
        </Modal>
    </div>
</template>
