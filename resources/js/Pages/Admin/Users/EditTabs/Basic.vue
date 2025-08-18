<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Edit from "../Edit.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import VueDatePicker from "@vuepic/vue-datepicker";
import SearchableSelect from "vue-select";
import Status from "@/Data/status.json";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    user: Object,
});

const authUser = usePage().props.auth.user;

const form = useForm({
    first_name: props?.user?.first_name ?? "",
    last_name: props?.user?.last_name ?? "",
    email: props?.user?.email ?? "",
    phone: props?.user?.phone ?? "",
    country: props?.user?.country ?? "",
    state: props?.user?.state ?? "",
    tax_id: props?.user?.tax_id ?? "",
    address: props?.user?.address ?? "",
    is_active: props?.user?.is_active ?? "",
    date_of_birth:
        new Date(props?.user?.date_of_birth).toLocaleString("en-US") ?? "",
    zip_code: props?.user?.zip_code ?? "",
    suite: props?.user?.suite ?? "",
    // city: props?.user?.city ?? "",
    password: "",
});

const handleUpdate = () => {
    form.put(route("admin.users.userUpdate", { user: props?.user?.id }), {
        onSuccess: () => {
            console.log("Update");
        },
    });
};
</script>
<template>
    <AuthenticatedLayout>
        <Head title="Edit user" />
        <Edit :user="props?.user">
            <div class="card bg-base-100 shadow-sm w-full">
                <div class="card-body">
                    <form @submit.prevent="handleUpdate">
                        <div
                            class="grid lg:grid-cols-2 md:grid-cols-2 sm:grid-cols-1 gap-2"
                        >
                            <div>
                                <InputLabel value="First name" />
                                <TextInput v-model="form.first_name" />
                                <InputError :message="form.errors.first_name" />
                            </div>
                            <div>
                                <InputLabel value="Last name" />
                                <TextInput v-model="form.last_name" />
                                <InputError :message="form.errors.last_name" />
                            </div>
                            <div>
                                <InputLabel value="Email" />
                                <TextInput v-model="form.email" />
                                <InputError :message="form.errors.email" />
                            </div>
                            <div>
                                <InputLabel value="Phone" />
                                <TextInput v-model="form.phone" />
                                <InputError :message="form.errors.phone" />
                            </div>
                            <div>
                                <InputLabel value="Password" />
                                <TextInput v-model="form.password" />
                                <InputError :message="form.errors.password" />
                            </div>
                            <div>
                                <InputLabel value="Country" />
                                <TextInput v-model="form.country" />
                                <InputError :message="form.errors.country" />
                            </div>
                            <div>
                                <InputLabel value="State" />
                                <TextInput v-model="form.state" />
                                <InputError :message="form.errors.state" />
                            </div>
                            <!-- <div>
                                <InputLabel value="City" />
                                <TextInput v-model="form.city" />
                                <InputError :message="form.errors.city" />
                            </div> -->
                            <div>
                                <InputLabel value="suite" />
                                <TextInput v-model="form.suite" />
                                <InputError :message="form.errors.suite" />
                            </div>
                            <div>
                                <InputLabel value="Zip code" />
                                <TextInput v-model="form.zip_code" />
                                <InputError :message="form.errors.zip_code" />
                            </div>
                            <div>
                                <InputLabel value="Address" />
                                <TextInput v-model="form.address" />
                                <InputError :message="form.errors.address" />
                            </div>
                            <div>
                                <InputLabel value="Tax id" />
                                <TextInput v-model="form.tax_id" />
                                <InputError :message="form.errors.tax_id" />
                            </div>
                            <div>
                                <InputLabel value="Status" />
                                <SearchableSelect
                                    v-model="form.is_active"
                                    :options="Status"
                                    :reduce="(option) => option.is_active"
                                    label="name"
                                />
                                <InputError :message="form.errors.is_active" />
                            </div>
                            <div>
                                <InputLabel value="Date of birth" />
                                <VueDatePicker
                                    v-model="form.date_of_birth"
                                    :teleport="true"
                                    :enable-time-picker="false"
                                />
                                <InputError
                                    :message="form.errors.date_of_birth"
                                />
                            </div>
                        </div>
                        <div class="float-right mt-4" v-if="authUser.type == 1">
                            <PrimaryButton :processing="form.processing"
                                >Submit</PrimaryButton
                            >
                        </div>
                    </form>
                </div>
            </div>
        </Edit>
    </AuthenticatedLayout>
</template>
