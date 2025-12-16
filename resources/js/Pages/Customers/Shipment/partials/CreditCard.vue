<!-- CardList.vue -->
<script setup>
import AddCardModal from "@/Components/Payment/AddCardModal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref, watch } from "vue";
import StripeIcon from "@/Components/Icons/StripeIcon.vue";
import MasterCard from "@/Components/Icons/MasterCard.vue";
import Paypal from "@/Components/Icons/Paypal.vue";
import AmericaExpress from "@/Components/Icons/AmericaExpress.vue";
import Visa from "@/Components/Icons/Visa.vue";
import IdealPayment from "@/Components/Icons/IdealPayment.vue";

const props = defineProps({
    cards: {
        type: Array,
        required: true,
    },
    publishableKey: String,
});

const isShowCardModal = ref(false);
const showCardModal = () => (isShowCardModal.value = true);
const closeCardModal = () => (isShowCardModal.value = false);

const emit = defineEmits(["selectedCard"]);
const selectedCard = ref(null);
const dropdownOpen = ref(false);

watch(selectedCard, (val) => {
    emit("selectedCard", val);
});

const submitForm = (stripeResponse) => {
    try {
        const { form } = stripeResponse;
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

const getCardLabel = (card) => `${card.brand} **** **** **** ${card.last4}`;
</script>

<template>
    <div class="flex items-center justify-between mb-4">
        <div>
            <p>Debit & Credit Cards</p>
            <p>Choose your card for checkout</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <!-- <div class="w-10 h-10"><StripeIcon class="w-full h-full" /></div> -->
            <div class="w-10 h-10"><MasterCard class="w-full h-full" /></div>
            <div class="w-10 h-10"><Paypal class="w-full h-full" /></div>
            <div class="w-10 h-10">
                <AmericaExpress class="w-full h-full" />
            </div>
            <div class="w-10 h-10"><Visa class="w-full h-full" /></div>
            <!-- <div class="w-10 h-10"><IdealPayment class="w-full h-full" /></div> -->
        </div>
        <PrimaryButton class="bg-primary-500" @click="showCardModal">
            +Add Card
        </PrimaryButton>
    </div>

    <!-- Dropdown -->
    <div class="relative w-full">
        <div
            class="flex items-center justify-between w-full p-4 bg-white border rounded-lg shadow-sm cursor-pointer"
            @click="dropdownOpen = !dropdownOpen"
        >
            <span>
                {{
                    selectedCard
                        ? getCardLabel(cards.find((c) => c.id === selectedCard))
                        : "Select a card"
                }}
            </span>
            <svg
                class="w-4 h-4 text-gray-500"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                />
            </svg>
        </div>

        <!-- Dropdown List -->
        <ul
            v-if="dropdownOpen"
            class="absolute z-50 w-full mt-1 overflow-y-auto bg-white border rounded-lg shadow-lg max-h-60"
        >
            <li
                v-for="card in cards"
                :key="card.id"
                @click="
                    selectedCard = card.id;
                    dropdownOpen = false;
                "
                class="p-3 cursor-pointer hover:bg-gray-100"
            >
                <p class="font-medium">{{ card.brand }}</p>
                <p class="text-sm tracking-widest text-gray-500">
                    **** **** **** {{ card.last4 }}
                </p>
                <p class="text-xs text-gray-400">{{ card.address_line1 }}</p>
            </li>
        </ul>
    </div>

    <AddCardModal
        :publishableKey="publishableKey"
        :isShowCardModal="isShowCardModal"
        :showCardModal="showCardModal"
        :closeCardModal="closeCardModal"
        @submitForm="submitForm"
    />
</template>
