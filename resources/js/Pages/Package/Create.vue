<script setup>
import { ref, watch } from "vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import SearchableSelect from "vue-select";

import { computed } from "vue";
import InputError from "@/Components/InputError.vue";
import CameraCapture from "@/Components/CameraCapture.vue";

const props = defineProps({
    users: Array,
    status: {
        type: String,
    },
});
const photos = ref([]);
const form = useForm({
    from: "",
    date: null,
    sender_id: null,
    items: [
        {
            title: "",
            description: "",
            item_note: "",
            quantity: 1,
            value_per_unit: 0,
            total_line_value: 0,
            total_line_weight: 0,
            files: [],
            preview: [],
        },
    ],
    totalPrice: 0,
    totalWeight: 0,
    tracking_no: null,
});

const addItem = () => {
    form.items.push({
        title: "",
        description: "",
        item_note: "",
        quantity: 1,
        value_per_unit: 0,
        total_line_value: 0,
        total_line_weight: 0,
        files: [],
        preview: [],
    });
};

const removeItem = (index) => {
    form.items.splice(index, 1);
};
watch(
    () => form.items,
    () => {
        form.totalPrice = totalPrice.value;
        form.totalWeight = totalWeight.value;
    },
    { deep: true }
);

const totalPrice = computed(() =>
    parseFloat(
        form.items
            .reduce((sum, item) => sum + item.quantity * item.value_per_unit, 0)
            .toFixed(2)
    )
);

const totalWeight = computed(() =>
    parseFloat(
        form.items
            .reduce((sum, item) => sum + Number(item.total_line_weight || 0), 0)
            .toFixed(2)
    )
);

const handleFileChange = (e, index) => {
    const files = Array.from(e.target.files);

    files.forEach((file) => {
        form.items[index].files.push(file);
        form.items[index].preview.push(URL.createObjectURL(file));
    });
};

const submitForm = () => {
    form.date = new Date(form.date).toLocaleString("en-US");

    form.transform((data) => {
        const payload = new FormData();

        payload.append("from", data.from);
        payload.append("date_received", data.date);
        payload.append("sender_id", data.sender_id);
        payload.append("total_value", data.totalPrice);
        payload.append("weight", data.totalWeight);
        payload.append("tracking_id", data.tracking_no);

        data.items.forEach((item, itemIndex) => {
            payload.append(`items[${itemIndex}][title]`, item.title);
            payload.append(
                `items[${itemIndex}][description]`,
                item.description
            );
            payload.append(`items[${itemIndex}][item_note]`, item.item_note);
            payload.append(`items[${itemIndex}][quantity]`, item.quantity);
            payload.append(
                `items[${itemIndex}][value_per_unit]`,
                item.value_per_unit
            );
            payload.append(
                `items[${itemIndex}][total_line_value]`,
                item.quantity * item.value_per_unit
            );
            payload.append(
                `items[${itemIndex}][total_line_weight]`,
                item.total_line_weight
            );

            if (item.files && item.files.length > 0) {
                Array.from(item.files).forEach((file, fileIndex) => {
                    payload.append(
                        `items[${itemIndex}][files][${fileIndex}]`,
                        file
                    );
                });
            }
        });

        return payload;
    }).post(route("admin.packages.store"), {
        preserveScroll: true,
        onSuccess: () => console.log("Package created successfully 🚀"),
    });
};

const addCameraPhoto = (index, file) => {
    form.items[index].files.push(file);
    form.items[index].preview.push(URL.createObjectURL(file));
};

const removeImage = (itemIndex, imgIndex) => {
    form.items[itemIndex].preview.splice(imgIndex, 1);
    form.items[itemIndex].files.splice(imgIndex, 1);
};
</script>

<template>
    <Head title="Create Package" />
    <AuthenticatedLayout>
        <div
            v-if="status"
            class="p-3 mb-4 text-sm font-medium text-green-700 border border-green-200 rounded-md bg-green-50"
        >
            {{ status }}
        </div>

        <div class="max-w-6xl py-8 mx-auto">
            <div class="flex items-center gap-2 mb-6">
                <i class="text-2xl text-primary-600 fa-solid fa-cube"></i>
                <h1 class="text-2xl font-bold">Create Shipment Package</h1>
            </div>

            <form @submit.prevent="submitForm" enctype="multipart/form-data">
                <div class="p-6 mb-8 bg-white border shadow-sm rounded-xl">
                    <h2 class="mb-4 text-lg font-semibold text-gray-700">
                        Package Information
                    </h2>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <InputLabel for="from" value="From" />
                            <TextInput
                                id="from"
                                v-model="form.from"
                                class="w-full mt-1"
                                placeholder="Amazon, eBay, Walmart etc."
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.from"
                            />
                        </div>

                        <div>
                            <InputLabel for="date" value="Date Received" />
                            <VueDatePicker
                                v-model="form.date"
                                class="w-full mt-1 text-black border-gray-300 rounded-md"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.date"
                            />
                        </div>

                        <div class="hidden">
                            <InputLabel value="Tracking No" />
                            <TextInput
                                v-model="form.tracking_no"
                                class="w-full"
                            />
                        </div>
                    </div>
                </div>

                <div class="p-6 mb-8 bg-white border shadow-sm rounded-xl">
                    <h2 class="mb-4 text-lg font-semibold text-gray-700">
                        Sender Information
                    </h2>

                    <div>
                        <InputLabel for="sender" value="Select Sender" />
                        <SearchableSelect
                            id="sender_id"
                            class="w-full mt-1"
                            label="suite"
                            :options="props.users"
                            :reduce="(option) => option.id"
                            v-model="form.sender_id"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.sender_id"
                        />
                    </div>
                </div>
                <div class="p-6 mb-8 bg-white border shadow-sm rounded-xl">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">
                            Package Items
                        </h2>
                        <PrimaryButton
                            type="button"
                            @click="addItem"
                            class="bg-green-600 hover:bg-green-700"
                        >
                            + Add Item
                        </PrimaryButton>
                    </div>
                    <div class="space-y-6">
                        <div
                            v-for="(item, index) in form.items"
                            :key="index"
                            class="relative p-5 border shadow-sm rounded-xl bg-gray-50"
                        >
                            <button
                                v-if="form.items.length > 1"
                                @click="removeItem(index)"
                                type="button"
                                class="absolute text-sm text-red-500 top-3 right-3 hover:text-red-600"
                            >
                                ✕ Remove
                            </button>
                            <div
                                class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4"
                            >
                                <div>
                                    <InputLabel value="Title" />
                                    <TextInput
                                        v-model="item.title"
                                        class="w-full"
                                    />
                                </div>
                                <div>
                                    <InputLabel value="Description" />
                                    <TextInput
                                        v-model="item.description"
                                        class="w-full"
                                    />
                                </div>
                                <div>
                                    <InputLabel value="Note" />
                                    <TextInput
                                        v-model="item.item_note"
                                        class="w-full"
                                    />
                                </div>
                                <div>
                                    <InputLabel value="Quantity" />
                                    <TextInput
                                        v-model.number="item.quantity"
                                        type="number"
                                        class="w-full"
                                    />
                                </div>
                                <div>
                                    <InputLabel value="Value / Unit" />
                                    <TextInput
                                        v-model.number="item.value_per_unit"
                                        type="number"
                                        step="any"
                                        class="w-full"
                                    />
                                </div>
                                <div>
                                    <InputLabel value="Total Value" />
                                    <TextInput
                                        :value="
                                            item.quantity * item.value_per_unit
                                        "
                                        readonly
                                        class="w-full bg-gray-200"
                                    />
                                </div>

                                <div>
                                    <InputLabel value="Weight (lbs)" />
                                    <TextInput
                                        v-model.number="item.total_line_weight"
                                        type="number"
                                        class="w-full"
                                        step="any"
                                    />
                                </div>
                            </div>

                            <div class="mt-4">
                                <InputLabel value="Photos" />

                                <div class="flex gap-3 mt-2">
                                    <label
                                        class="w-full px-3 py-2 bg-white border rounded-lg shadow-sm cursor-pointer hover:bg-gray-100"
                                    >
                                        Browse Photo
                                        <input
                                            type="file"
                                            class="hidden"
                                            multiple
                                            @change="
                                                handleFileChange($event, index)
                                            "
                                        />
                                    </label>

                                    <CameraCapture
                                        button-text="Take Picture"
                                        @add-photo="
                                            (file) =>
                                                addCameraPhoto(index, file)
                                        "
                                    />
                                </div>

                                <div
                                    v-if="item.preview.length"
                                    class="flex flex-wrap gap-2 mt-3"
                                >
                                    <div
                                        v-for="(img, i) in item.preview"
                                        :key="i"
                                        class="relative w-20 h-20 overflow-hidden border rounded-lg"
                                    >
                                        <img
                                            :src="img"
                                            class="object-cover w-full h-full"
                                        />

                                        <button
                                            type="button"
                                            @click="removeImage(index, i)"
                                            class="absolute flex items-center justify-center w-4 h-4 text-xs text-white bg-red-500 rounded-full top-1 right-1"
                                        >
                                            ✕
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6 mb-8 bg-white border shadow-sm rounded-xl">
                    <h2 class="mb-4 text-lg font-semibold text-gray-700">
                        Summary
                    </h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <InputLabel value="Total Price" />
                            <TextInput
                                :value="totalPrice"
                                readonly
                                class="w-full bg-gray-200"
                            />
                        </div>
                        <div>
                            <InputLabel value="Total Weight" />
                            <TextInput
                                :value="totalWeight"
                                readonly
                                class="w-full bg-gray-200"
                            />
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <PrimaryButton
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700"
                    >
                        Save Package
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
