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
        <div class="text-center">
            <h1 class="mb-1 text-black title-1">Welcome!</h1>
        </div>
        <div
            class="flex flex-col items-center max-w-4xl gap-4 px-4 py-8 mx-auto md:gap-8 md:flex-row rounded-xl"
        >
            <header class="w-full text-center md:w-1/2">
                <div class="flex justify-center">
                    <img src="assets/image/home/ship.svg" alt="fish" />
                </div>
            </header>
            <form
                class="w-full px-3 py-4 border-2 bg-rose-50 border-rose-200 sm:p-6 rounded-xl"
                @submit.prevent="submit"
            >
                <div class="mb-6">
                    <!-- <label for="email" class="block mb-2 text-sm font-medium text-left text-gray-900">Your email</label> -->
                    <TextInput
                        id="email"
                        type="email"
                        class="block w-full mt-1"
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
                        class="block mb-2 text-sm font-medium text-left text-gray-900"
                        >Your password</label
                    > -->

                    <TextInput
                        id="password"
                        type="password"
                        class="block w-full mt-1"
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
                        class="text-sm text-gray-600 underline rounded-md hover:text-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                    >
                        Forgot your password?
                    </Link>
                    <PrimaryButton :disabled="form.processing">
                        Login
                    </PrimaryButton>
                </div>
            </form>
             <header class="w-full text-center md:w-1/2">
                <div class="flex justify-center">
                    <img src="/assets/image/home/cartoon-plane.svg" alt="fish" />
                </div>
            </header>
        </div>
        <div
            class="flex justify-center text-lg text-center text-gray-600 md:text-xl"
        >
            <div class="px-10">

                <p class="mx-auto mb-2 text-lg text-gray-600 md:text-xl">
                    Ready to Shop Some More? Your packages are waiting
                </p>
                <div>Forgot your password?</div>
                <div>
                    Click below to reset your password and get back into your
                    dashboard right away to continue shopping.
                </div>
                <div>
                    Check your password a couple of times, before changing it.
                </div>
                <ul class="px-4">
                    <li class="py-2">
                        Your suite number can be found in your markets address
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
