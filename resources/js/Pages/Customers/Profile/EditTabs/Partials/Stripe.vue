<script setup>
import { ref, onMounted } from "vue";
import { loadStripe } from "@stripe/stripe-js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
const props = defineProps({
    stripeKey: String,
});
const user = usePage().props.auth.user;

const stripePublicKey = props?.stripeKey;
const stripe = ref(null);
const elements = ref(null);

const form = useForm({
    token: null,
    card_holder_name: user?.name,
    address_line1: "",
    address_line2: "",
    country: "",
    state: "",
    city: "",
    postal_code: "",
    country_code: "",
    phone_number: "",
});

const token = ref(null);
const cardNumber = ref(null);
const cardExpiry = ref(null);
const cardCvc = ref(null);

const emit = defineEmits(["submitForm"]);
const initStripeElements = async () => {
    stripe.value = await loadStripe(stripePublicKey);
    elements.value = stripe.value.elements();

    cardNumber.value = elements.value.create("cardNumber");
    cardNumber.value.mount("#card-number");

    cardExpiry.value = elements.value.create("cardExpiry");
    cardExpiry.value.mount("#card-expiry");

    cardCvc.value = elements.value.create("cardCvc");
    cardCvc.value.mount("#card-cvc");
};

const createToken = async () => {
    const { token, error } = await stripe.value.createToken(cardNumber.value);

    if (error) {
        document.getElementById("card-error").textContent = error.message;
    } else {
        document.getElementById("card-error").textContent = "";
        form.token = token;
        emit("submitForm", { form: form });
    }
};

onMounted(() => {
    initStripeElements();
});
</script>

<template>
    <div>
        <p>Please enter all information.</p>
        <div>
            <p class="text-gray-500">
                Card Holder Name (First Name and Last Name as appears on the
                card) *
            </p>
            <TextInput v-model="form.card_holder_name" />
        </div>

        <label>Card Number</label>
        <div id="card-number" class="p-2 border rounded mb-2"></div>

        <label>Card Expiry</label>
        <div id="card-expiry" class="p-2 border rounded mb-2"></div>

        <label>Card CVC</label>
        <div id="card-cvc" class="p-2 border rounded mb-2"></div>

        <div id="card-error" class="text-red-500 text-sm mt-2"></div>

        <div>
            <p>Billing Address</p>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <InputLabel value="Full Name*" />
                    <TextInput v-model="form.card_holder_name" required />
                    <InputError
                        :message="form.errors.card_holder_name"
                        class="mt-1"
                    />
                </div>
                <div>
                    <InputLabel value="Address Line 1*" />
                    <TextInput v-model="form.address_line1" required />
                    <InputError
                        :message="form.errors.address_line1"
                        class="mt-1"
                    />
                </div>
                <div>
                    <InputLabel value="Address Line 2" />
                    <TextInput v-model="form.address_line2" />
                    <InputError
                        :message="form.errors.address_line2"
                        class="mt-1"
                    />
                </div>
                <div>
                    <InputLabel value="Country*" />
                    <TextInput v-model="form.country" required />
                    <InputError :message="form.errors.country" class="mt-1" />
                </div>
                <div>
                    <InputLabel value="State or Province*" />
                    <TextInput v-model="form.state" required />
                    <InputError :message="form.errors.state" class="mt-1" />
                </div>
                <div>
                    <InputLabel value="City or Town*" />
                    <TextInput v-model="form.city" required />
                    <InputError :message="form.errors.city" class="mt-1" />
                </div>
                <div>
                    <InputLabel value="Postal Code" />
                    <TextInput v-model="form.postal_code" />
                    <InputError
                        :message="form.errors.postal_code"
                        class="mt-1"
                    />
                </div>
                <div>
                    <InputLabel value="Country Code*" />
                    <TextInput v-model="form.country_code" required />
                    <InputError
                        :message="form.errors.country_code"
                        class="mt-1"
                    />
                </div>
                <div>
                    <InputLabel value="Phone Number*" />
                    <TextInput v-model="form.phone_number" required />
                    <InputError
                        :message="form.errors.phone_number"
                        class="mt-1"
                    />
                </div>
            </div>
        </div>
        <div class="text-end">
            <PrimaryButton
                id="custom-button"
                @click="createToken"
                :disabled="form.processing"
            >
                Save this information
            </PrimaryButton>
        </div>
    </div>
</template>
