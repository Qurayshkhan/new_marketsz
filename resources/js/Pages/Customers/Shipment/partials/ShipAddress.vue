<script setup>
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Radiobox from "@/Components/Radiobox.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const props = defineProps({
    userAddresses: Object,
});
const $page = usePage();
const emit = defineEmits(["setSelectedAddress"]);
const showModal = ref(false);
const showDeleteModal = ref(false);
const addressToDelete = ref(null);
const selectedAddress = ref(null);
watch(selectedAddress, (val) => {
    emit("setSelectedAddress", val);
});
const form = useForm({
    id: null,
    address_name: "",
    full_name: "",
    address_line_1: "",
    address_line_2: "",
    country: "",
    state: "",
    city: "",
    postal_code: "",
    country_code: "",
    phone_number: "",
});
const resetForm = () => {
    form.reset();
    form.clearErrors();
    form.id = null;
};
const openModal = (address = null) => {
    resetForm();

    if (address) {
        // Set form data for editing
        form.id = address.id;
        form.address_name = address.address_name || "";
        form.full_name = address.full_name || "";
        form.address_line_1 = address.address_line_1 || "";
        form.address_line_2 = address.address_line_2 || "";
        form.country = address.country || "";
        form.state = address.state || "";
        form.city = address.city || "";
        form.postal_code = address.postal_code || "";
        form.country_code = address.country_code || "";
        form.phone_number = address.phone_number || "";
    }

    showModal.value = true;
};

const saveAddress = () => {
    const isEdit = form.id !== null;
    const routeName = isEdit
        ? route("customer.addresses.update", { address: form.id })
        : route("customer.addresses.store");

    if (isEdit) {
        form.put(routeName, {
            preserveScroll: true,
            onSuccess: () => {
                showModal.value = false;
                resetForm();
            },
            onError: () => {
                // Keep modal open to show validation errors
            },
        });
    } else {
        form.post(routeName, {
            preserveScroll: true,
            onSuccess: () => {
                showModal.value = false;
                resetForm();
            },
            onError: () => {
                // Keep modal open to show validation errors
            },
        });
    }
};
</script>

<template>
    <div class="mb-4 flex justify-between items-center">
        <div>
            <p>Shipping Address</p>
            <p>Chose your address for shipping</p>
        </div>
        <PrimaryButton @click="openModal()"> + Add New Address </PrimaryButton>
    </div>
    <div>
        <ul>
            <li
                v-for="address in props?.userAddresses"
                :key="address.id"
                class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow transition mb-4"
            >
                <div class="flex items-start gap-4">
                    <Radiobox
                        name="user_address_id"
                        v-model="selectedAddress"
                        :value="address.id"
                        :id="address.id"
                    />
                    <label :for="address.id" class="cursor-pointer">
                        <div class="flex flex-col">
                            <div class="">
                                <p class="text-gray-900">
                                    {{ address?.full_name }}
                                </p>
                                <p class="text-gray-900">
                                    {{ address?.address_line_1 }}
                                </p>
                                <p class="text-gray-900">
                                    {{ address?.country }}
                                    {{
                                        address?.state
                                            ? `, ${address?.state}`
                                            : ""
                                    }}
                                    {{
                                        address?.city
                                            ? `, ${address?.city}`
                                            : ""
                                    }}
                                </p>
                            </div>
                            <p class="text-sm text-gray-500"></p>
                        </div>
                    </label>
                </div>
            </li>
        </ul>
    </div>
    <!-- Add/Edit Modal -->
    <Modal :show="showModal" @close="showModal = false">
        <div class="p-6">
            <h2 class="text-xl font-semibold mb-4 text-black">
                {{ form.id ? "Edit Address" : "Add New Address" }}
            </h2>
            <form @submit.prevent="saveAddress" class="grid gap-3">
                <div>
                    <TextInput
                        v-model="form.address_name"
                        placeholder="Address Name *"
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors.address_name"
                    />
                </div>
                <div>
                    <TextInput
                        v-model="form.full_name"
                        placeholder="Full Name *"
                    />
                    <InputError class="mt-2" :message="form.errors.full_name" />
                </div>
                <div>
                    <TextInput
                        v-model="form.address_line_1"
                        placeholder="Address Line 1 *"
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors.address_line_1"
                    />
                </div>
                <div>
                    <TextInput
                        v-model="form.address_line_2"
                        placeholder="Address Line 2"
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors.address_line_2"
                    />
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <TextInput
                            v-model="form.country"
                            placeholder="Country *"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.country"
                        />
                    </div>
                    <div>
                        <TextInput v-model="form.state" placeholder="State" />
                    </div>
                    <div>
                        <TextInput v-model="form.city" placeholder="City *" />
                        <InputError class="mt-2" :message="form.errors.city" />
                    </div>
                    <div>
                        <TextInput
                            v-model="form.postal_code"
                            placeholder="Postal Code *"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.postal_code"
                        />
                    </div>
                    <div>
                        <TextInput
                            v-model="form.country_code"
                            placeholder="Country Code *"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.country_code"
                        />
                    </div>
                    <div>
                        <TextInput
                            v-model="form.phone_number"
                            placeholder="Phone Number *"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.phone_number"
                        />
                    </div>
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <SecondaryButton type="button" @click="showModal = false">
                        Cancel
                    </SecondaryButton>
                    <PrimaryButton
                        type="submit"
                        :disabled="form.processing"
                        class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50"
                    >
                        {{
                            form.processing
                                ? "Saving..."
                                : form.id
                                ? "Update"
                                : "Save"
                        }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
