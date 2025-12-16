<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Checkbox from "@/Components/Checkbox.vue";
import { computed, ref } from "vue";
import Countries from "@js/Data/Countries.json";
const form = useForm({
    first_name: "",
    last_name: "",
    email: "",
    phone: "",
    password: "",
    country: "",
    address: "",
    state: "",
    city: "",
    zip_code: "",
    tax_id: "",
    year: "",
    month: "",
    day: "",
});

const months = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
];

const days = computed(() => Array.from({ length: 31 }, (_, i) => i + 1));

const years = computed(() => {
    const startYear = 1922;
    const currentYear = new Date().getFullYear();
    return Array.from(
        { length: currentYear - startYear + 1 },
        (_, i) => currentYear - i
    );
});
const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />
        <main
            class="container flex items-center justify-center flex-1 pt-4 pb-16 md:pt-12 md:pb-24 md:-mt-20"
            id="auth-form"
        >
            <div class="w-full max-w-md text-center">
                <header class="mb-6 md:mb-8">
                    <div class="flex justify-center py-8">
                        <img
                            src="/assets/image/home/cartoon-plane.svg"
                            alt="whale"
                            width="72%"
                        />
                        <img
                            src="/assets/image/home/ship.svg"
                            alt="whale"
                            width="72%"
                        />
                    </div>
                    <h1 class="mb-1 text-black title-1">
                        Get Your TAX-FREE USA Shipping Address
                    </h1>
                    <p class="mx-auto text-lg text-gray-600 md:text-xl">
                        As soon as your register, you can instantly get your
                        addresses and start shopping and shipping
                    </p>
                </header>
                <form
                    id="signup_form"
                    class="p-4 bg-red-50 rounded-xl"
                    @submit.prevent="submit"
                >
                    <div class="mb-2 text-black label-input">
                        <select
                            required="required"
                            class="bg-white input"
                            v-model="form.country"
                        >
                            <option value="">Choose…</option>
                            <option
                                :value="country.name"
                                v-for="country in Countries"
                                :key="country.id"
                            >
                                {{ country.name }}
                            </option>
                        </select>
                        <label>Country</label>
                    </div>
                    <div>
                        <div class="d-flex">
                            <div class="mb-2 label-input">
                                <TextInput
                                    placeholder="State"
                                    required="required"
                                    autocapitalize="sentences"
                                    class="rounded"
                                    type="text"
                                    v-model="form.state"
                                />
                                <label>State</label>
                            </div>
                            <div class="mb-2 label-input">
                                <TextInput
                                    placeholder="City"
                                    required="required"
                                    autocapitalize="sentences"
                                    class="rounded"
                                    type="text"
                                    v-model="form.city"
                                />
                                <label>City</label>
                            </div>
                            <div class="mb-2 label-input">
                                <TextInput
                                    placeholder="Zip code"
                                    required="required"
                                    autocapitalize="sentences"
                                    class="rounded"
                                    type="text"
                                    v-model="form.zip_code"
                                />
                                <label>Zipcode</label>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between mb-2">
                        <div class="">
                            <div class="flex-1 label-input">
                                <TextInput
                                    v-model="form.first_name"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    placeholder="First name"
                                    autocapitalize="sentences"
                                    class="rounded"
                                    type="text"
                                    id="customer_first_name"
                                />
                                <label>First name</label>
                            </div>
                            <InputError
                                class="mt-2"
                                :message="form.errors.first_name"
                            />
                        </div>
                        <div class="">
                            <div class="flex-1 ml-2 label-input">
                                <TextInput
                                    v-model="form.last_name"
                                    required
                                    autofocus
                                    autocomplete="last_name"
                                    placeholder="Last name"
                                    class="rounded"
                                    type="text"
                                />
                                <label>Last name</label>
                            </div>
                            <InputError
                                class="mt-2"
                                :message="form.errors.last_name"
                            />
                        </div>
                    </div>
                    <div>
                        <div class="mb-2 label-input">
                            <TextInput
                                placeholder="Mobile phone number"
                                required="required"
                                class="rounded"
                                type="tel"
                                id="customer_phone"
                                v-model="form.phone"
                            />
                            <label>Phone</label>
                        </div>
                        <InputError class="mt-2" :message="form.errors.phone" />
                    </div>
                    <div>
                        <div class="mb-2 label-input">
                            <TextInput
                                placeholder="Email address"
                                required="required"
                                class="rounded"
                                type="email"
                                v-model="form.email"
                            />
                            <label>Email</label>
                        </div>
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>
                    <div>
                        <div class="mb-2 label-input">
                            <TextInput
                                placeholder="Password"
                                required="required"
                                class="rounded"
                                type="password"
                                v-model="form.password"
                            />
                            <label>Password</label>
                        </div>
                        <InputError
                            class="mt-2"
                            :message="form.errors.password"
                        />
                    </div>
                    <div class="mb-2 label-input">
                        <TextInput
                            placeholder="Tax ID Optional"
                            class="rounded"
                            type="text"
                            v-model="form.tax_id"
                        />
                        <label>Tax id</label>
                    </div>
                    <!-- <label
                        class="flex items-start mt-4 text-sm leading-snug text-left text-black checkbox"
                    >
                        <span class="mt-px checkbox-label"></span>
                    </label> -->
                    <p class="text-left text-black">
                        You must be over 18 years in order to create an account
                    </p>
                    <div class="flex mb-3 text-black bg-white">
                        <select
                            required="required"
                            class="flex-1 bg-white input"
                            v-model="form.year"
                        >
                            <option value="">Year</option>
                            <option
                                :value="year"
                                v-for="year in years"
                                :key="year"
                            >
                                {{ year }}
                            </option>
                        </select>
                        <select
                            required="required"
                            class="flex-1 ml-2 bg-white input"
                            v-model="form.month"
                        >
                            <option value="">Month</option>
                            <option
                                :value="month"
                                v-for="month in months"
                                :key="month"
                            >
                                {{ month }}
                            </option>
                        </select>
                        <select
                            required="required"
                            class="flex-1 ml-2 bg-white input"
                            v-model="form.day"
                        >
                            <option value="">Day</option>
                            <option :value="day" v-for="day in days" :key="day">
                                {{ day }}
                            </option>
                        </select>
                    </div>
                    <div class="flex items-center text-black">
                        <Checkbox class="mx-2" required />
                        <p>
                            <span class="mt-px checkbox-label">
                                By signing up I agree to the
                                <a
                                    class="underline"
                                    target="_blank"
                                    href="https://www.mymalls.com/en/terms"
                                    >terms of service</a
                                >
                            </span>
                        </p>
                    </div>
                    <div class="mt-6">
                        <PrimaryButton
                            type="submit"
                            class="ms-4"
                            :disabled="form.processing"
                        >
                            Register
                        </PrimaryButton>
                        <div
                            class="max-w-xs mx-auto mt-5 text-xs text-center text-gray-600"
                        >
                            By submitting this form you agree to receive
                            occasional emails from us. You can unsubscribe at
                            any time.
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </GuestLayout>
</template>
