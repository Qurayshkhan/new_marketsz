<!-- CardList.vue -->
<script setup>
import AddCardModal from "@/Components/Payment/AddCardModal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Radiobox from "@/Components/Radiobox.vue";
import { ref, watch } from "vue";

const props = defineProps({
    cards: {
        type: Array, // should be Array, not Object
        required: true,
    },
    publishableKey: String,
});

const isShowCardModal = ref(false);
const showCardModal = () => {
    isShowCardModal.value = true;
};
const closeCardModal = () => {
    isShowCardModal.value = false;
};

const emit = defineEmits(["update:selected"]);

const selectedCard = ref(null);

watch(selectedCard, (val) => {
    emit("update:selected", val);
});

const submitForm = (stripeResponse) => {
    try {
        const { form } = stripeResponse;
        console.log("🚀 ~ submitForm ~ form:", form);
        form.post(route("customer.card.add"), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
        closeCardModal();
    } catch (error) {
        toast.error(error);
    }
};
</script>

<template>
    <div class="flex justify-between items-center mb-4">
        <div>
            <p>Debit & Credit Cards</p>
            <p>Chose your card for checkout</p>
        </div>

        <PrimaryButton class="bg-primary-500" @click="showCardModal"
            >+Add Card</PrimaryButton
        >
    </div>
    <ul>
        <li
            v-for="card in cards"
            :key="card.id"
            class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow transition mb-4"
        >
            <div class="flex items-start gap-4">
                <Radiobox
                    name="payment_card"
                    v-model="selectedCard"
                    :value="card.id"
                    :id="card.id"
                />
                <label :for="card.id" class="cursor-pointer">
                    <div class="flex flex-col">
                        <div
                            class="flex items-center gap-2 text-sm font-medium text-gray-700"
                        >
                            <p class="text-gray-900">{{ card.brand }}</p>
                            <p class="text-gray-500 tracking-widest">
                                **** **** **** {{ card.last4 }}
                            </p>
                        </div>
                        <p class="text-sm text-gray-500">
                            {{ card.address_line1 }}
                        </p>
                    </div>
                </label>
            </div>
        </li>
    </ul>

    <AddCardModal
        :publishableKey="publishableKey"
        :isShowCardModal="isShowCardModal"
        :showCardModal="showCardModal"
        :closeCardModal="closeCardModal"
        @submitForm="submitForm"
    />
</template>
