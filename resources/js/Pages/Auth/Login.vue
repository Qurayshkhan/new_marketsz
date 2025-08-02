<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Login" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <div
            class="max-w-4xl mx-auto flex flex-col gap-4 md:gap-8 md:flex-row items-center py-8 px-4 rounded-xl"
        >
            <header class="w-full md:w-1/2 text-center">
                <div class="flex justify-center">
                    <img src="assets/image/ballon.svg" alt="fish" />
                </div>
                <h1 class="title-1 mb-1 text-black">Welcome!</h1>
                <p class="text-lg md:text-xl text-gray-600 mx-auto">
                    Ready to Shop Some More? Your packages are waiting
                </p>
            </header>
            <form
                class="w-96 bg-rose-50 border-2 border-rose-200 py-4 px-3 sm:p-6 rounded-xl"
                @submit.prevent="submit"
            >
                <div class="mb-6">
                    <!-- <label for="email" class="block text-left mb-2 text-sm font-medium text-gray-900">Your email</label> -->
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="name@flowbite.com"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
                <div class="mb-6">
                    <!-- <label
                        for="password"
                        class="block text-left mb-2 text-sm font-medium text-gray-900"
                        >Your password</label
                    > -->

                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        placeholder="Password"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>
                <div class="flex justify-between">
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="rounded-md text-sm text-gray-600 underline hover:text-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                    >
                        Forgot your password?
                    </Link>
                    <PrimaryButton :disabled="form.processing">
                        Login
                    </PrimaryButton>
                </div>
            </form>
        </div>
        <div class="flex justify-center text-lg md:text-xl text-gray-600">
            <div class="px-10">
                <div>Forgot your password?</div>
                <div>
                    Click below to reset your password and get back into your
                    dashboard right away to continue shopping.
                </div>
                <div>
                    Check your password a couple of times, before changing it.
                </div>
                <ul class="list-disc px-4">
                    <li class="py-2">
                        Your suite number can be found in your MyUS address
                    </li>
                    <li class="py-2">
                        Your password was selected by you during account
                        registratoin
                    </li>
                    <li class="py-2">Passwords are case sensitive</li>
                    <li class="py-2">
                        If you are having trouble loggin in, please clear your
                        browser cookies and try again.
                    </li>
                </ul>
                <div class="py-4">
                    <div class="py-4">
                        Other login Questions?
                        <a
                            class="text-blue-500 hover:text-primary-700"
                            href="FAQ.html"
                            >Please see our FAQ.</a
                        >
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
