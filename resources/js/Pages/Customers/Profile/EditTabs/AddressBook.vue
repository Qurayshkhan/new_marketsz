<script setup>
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import Edit from "../Edit.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    userAddresses: Object,
});

const $page = usePage();

const showModal = ref(false);
const showDeleteModal = ref(false);
const addressToDelete = ref(null);

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

const handleSetDefault = (event, addressId) => {
    const type = event.target.value;
    if (!type) return;

    form.clearErrors();

    router.put(
        route("customer.addresses.setDefault", { address: addressId }),
        {
            type,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                event.target.selectedIndex = 0;
            },
        }
    );
};

const confirmDelete = (address) => {
    addressToDelete.value = address;
    showDeleteModal.value = true;
};

const deleteAddress = () => {
    if (!addressToDelete.value) return;

    router.delete(
        route("customer.addresses.destroy", {
            address: addressToDelete.value.id,
        }),
        {
            preserveScroll: true,
            onSuccess: () => {
                showDeleteModal.value = false;
                addressToDelete.value = null;
            },
        }
    );
};
</script>
<template>
    <Edit>
        <Head title="Address Book" />

        <!-- Flash Messages -->
        <div
            v-if="$page.props.flash?.success"
            class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded"
        >
            {{ $page.props.flash.success }}
        </div>
        <div
            v-if="$page.props.flash?.error || $page.props.flash?.message"
            class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded"
        >
            {{ $page.props.flash.error || $page.props.flash.message }}
        </div>

        <div class="mb-4 text-right">
            <PrimaryButton @click="openModal()">
                + Add New Address
            </PrimaryButton>
        </div>

        <!-- Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <template
                v-if="props?.userAddresses && props.userAddresses.length > 0"
            >
                <div
                    v-for="address in props.userAddresses"
                    :key="address.id"
                    class="bg-white rounded-2xl shadow-md p-5 border relative flex flex-col justify-between hover:shadow-lg transition-shadow"
                >
                    <!-- Action Buttons -->
                    <div class="absolute top-2 right-2 flex gap-2">
                        <button
                            @click="openModal(address)"
                            class="text-blue-500 text-sm hover:underline"
                            title="Edit Address"
                        >
                            Edit
                        </button>
                        <button
                            @click="confirmDelete(address)"
                            class="text-red-500 text-sm hover:underline"
                            title="Delete Address"
                        >
                            Delete
                        </button>
                    </div>

                    <!-- Address Info -->
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ address.full_name }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ address.address_name }}
                        </p>
                        <p class="mt-2 text-gray-600">
                            {{ address.address_line_1 }}
                            <br v-if="address.address_line_2" />
                            {{ address.address_line_2 }}
                        </p>
                        <p class="text-gray-600">
                            {{ address.city
                            }}{{ address.state ? ", " + address.state : "" }} -
                            {{ address.postal_code }}
                            <br />
                            {{ address.country }}
                        </p>
                        <p class="mt-1 text-gray-500">
                            Phone: {{ address.country_code }}
                            {{ address.phone_number }}
                        </p>

                        <!-- Default Badges -->
                        <div class="mt-2 space-x-2">
                            <span
                                v-if="address.is_default_us"
                                class="text-xs text-white bg-blue-600 px-2 py-1 rounded-full"
                            >
                                Default US
                            </span>
                            <span
                                v-if="address.is_default_uk"
                                class="text-xs text-white bg-green-600 px-2 py-1 rounded-full"
                            >
                                Default UK
                            </span>
                        </div>
                    </div>

                    <!-- Set Default Dropdown -->
                    <div class="mt-auto pt-3 border-t">
                        <select
                            @change="handleSetDefault($event, address.id)"
                            class="w-full px-3 py-2 rounded-md border text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option disabled selected>
                                Set as Default Address...
                            </option>
                            <option
                                value="us"
                                :disabled="address.is_default_us"
                            >
                                Default for US
                            </option>
                            <option
                                value="uk"
                                :disabled="address.is_default_uk"
                            >
                                Default for UK
                            </option>
                            <option
                                value="both"
                                :disabled="
                                    address.is_default_us &&
                                    address.is_default_uk
                                "
                            >
                                Default for US & UK
                            </option>
                        </select>
                    </div>
                </div>
            </template>
            <div v-else class="col-span-full text-center py-8">
                <div class="text-gray-500">
                    <svg
                        class="mx-auto h-12 w-12 text-gray-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">
                        No addresses
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Get started by creating a new address.
                    </p>
                </div>
            </div>
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
                        <InputError
                            class="mt-2"
                            :message="form.errors.full_name"
                        />
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
                            <TextInput
                                v-model="form.state"
                                placeholder="State"
                            />
                        </div>
                        <div>
                            <TextInput
                                v-model="form.city"
                                placeholder="City *"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.city"
                            />
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
                        <SecondaryButton
                            type="button"
                            @click="showModal = false"
                        >
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

        <!-- Delete Confirmation Modal -->
        <Modal :show="showDeleteModal" @close="showDeleteModal = false">
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4 text-black">
                    Delete Address
                </h2>
                <p class="text-gray-600 mb-4">
                    Are you sure you want to delete the address "{{
                        addressToDelete?.address_name
                    }}"? This action cannot be undone.
                </p>
                <div class="flex justify-end gap-2">
                    <SecondaryButton
                        type="button"
                        @click="showDeleteModal = false"
                    >
                        Cancel
                    </SecondaryButton>
                    <DangerButton type="button" @click="deleteAddress">
                        Delete Address
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </Edit>
</template>
