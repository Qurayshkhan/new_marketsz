<script setup>
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const props = defineProps({
    userAddresses: Array,
});
const emit = defineEmits(["setSelectedAddress"]);

const showModal = ref(false);
const selectedAddress = ref(null);
const dropdownOpen = ref(false);

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
        Object.assign(form, {
            id: address.id,
            address_name: address.address_name || "",
            full_name: address.full_name || "",
            address_line_1: address.address_line_1 || "",
            address_line_2: address.address_line_2 || "",
            country: address.country || "",
            state: address.state || "",
            city: address.city || "",
            postal_code: address.postal_code || "",
            country_code: address.country_code || "",
            phone_number: address.phone_number || "",
        });
    }

    showModal.value = true;
};

const saveAddress = () => {
    const isEdit = form.id !== null;
    const routeName = isEdit
        ? route("customer.addresses.update", { address: form.id })
        : route("customer.addresses.store");

    const method = isEdit ? form.put : form.post;
    method(routeName, {
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = false;
            resetForm();
        },
    });
};

const getAddressLabel = (address) => {
    return `${address.full_name}, ${address.address_line_1}, ${
        address.city || ""
    }, ${address.country}`;
};
</script>

<template>
    <div class="mb-4 flex justify-between items-center mt-2">
        <div>
            <p>Shipping Address</p>
            <p>Choose your address for shipping</p>
        </div>
        <PrimaryButton @click="openModal()">+ Add New Address</PrimaryButton>
    </div>

    <!-- Dropdown -->
    <div class="relative w-full">
        <div
            class="bg-white border rounded-lg p-4 flex justify-between items-center cursor-pointer shadow-sm"
            @click="dropdownOpen = !dropdownOpen"
        >
            <span>
                {{
                    selectedAddress
                        ? getAddressLabel(
                              props.userAddresses.find(
                                  (a) => a.id === selectedAddress
                              )
                          )
                        : "Select a shipping address"
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

        <!-- Address List -->
        <ul
            v-if="dropdownOpen"
            class="absolute mt-1 bg-white border rounded-lg shadow-lg w-full max-h-60 overflow-y-auto z-50"
        >
            <li
                v-for="address in props.userAddresses"
                :key="address.id"
                @click="
                    selectedAddress = address.id;
                    dropdownOpen = false;
                "
                class="p-3 hover:bg-gray-100 cursor-pointer"
            >
                <p class="font-medium">{{ address.full_name }}</p>
                <p class="text-sm text-gray-500">
                    {{ address.address_line_1 }}
                </p>
                <p class="text-xs text-gray-400">
                    {{ address.country }}
                    {{ address.state ? `, ${address.state}` : "" }}
                    {{ address.city ? `, ${address.city}` : "" }}
                </p>
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
                <TextInput
                    v-model="form.address_name"
                    placeholder="Address Name *"
                />
                <InputError :message="form.errors.address_name" class="mt-1" />

                <TextInput v-model="form.full_name" placeholder="Full Name *" />
                <InputError :message="form.errors.full_name" class="mt-1" />

                <TextInput
                    v-model="form.address_line_1"
                    placeholder="Address Line 1 *"
                />
                <InputError
                    :message="form.errors.address_line_1"
                    class="mt-1"
                />

                <TextInput
                    v-model="form.address_line_2"
                    placeholder="Address Line 2"
                />

                <div class="grid grid-cols-2 gap-2">
                    <TextInput v-model="form.country" placeholder="Country *" />
                    <TextInput v-model="form.state" placeholder="State" />

                    <TextInput v-model="form.city" placeholder="City *" />
                    <InputError :message="form.errors.city" class="mt-1" />

                    <TextInput
                        v-model="form.postal_code"
                        placeholder="Postal Code *"
                    />
                    <TextInput
                        v-model="form.country_code"
                        placeholder="Country Code *"
                    />

                    <TextInput
                        v-model="form.phone_number"
                        placeholder="Phone Number *"
                    />
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <SecondaryButton type="button" @click="showModal = false">
                        Cancel
                    </SecondaryButton>
                    <PrimaryButton type="submit" :disabled="form.processing">
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
