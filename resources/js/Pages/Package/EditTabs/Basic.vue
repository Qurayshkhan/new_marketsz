<script setup>
import EditLayout from "../Edit.vue";
import { ref, watch, computed, onMounted } from "vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import SearchableSelect from "vue-select";
import InputError from "@/Components/InputError.vue";
import Delete from "../Delete.vue";
import packageStatus from "@/Data/package_status.json";
const props = defineProps({
    package: Object,
    customers: Object,
});

const editPackage = props.package;

const form = useForm({
    from: editPackage?.from,
    date: new Date(editPackage?.date_received),
    sender_id: editPackage?.sender_id,
    status: editPackage?.status,
    files: [],
    items: editPackage?.items ?? [
        {
            title: "",
            description: "",
            item_note: "",
            quantity: 1,
            value_per_unit: 0,
            total_line_value: 0,
            total_line_weight: 0,
        },
    ],
    totalPrice: 0,
    totalWeight: 0,
    tracking_no: editPackage?.tracking_id,
});

const calculateTotals = () => {
    form.items.forEach((item) => {
        item.total_line_value = parseFloat(
            (item.quantity * item.value_per_unit).toFixed(2)
        );
    });

    form.totalPrice = parseFloat(
        form.items
            .reduce((sum, item) => sum + (item.total_line_value || 0), 0)
            .toFixed(2)
    );

    form.totalWeight = parseFloat(
        form.items
            .reduce((sum, item) => sum + (item.total_line_weight || 0), 0)
            .toFixed(2)
    );
};
watch(
    () => form.items,
    () => {
        calculateTotals();
    },
    { deep: true }
);
const addItem = () => {
    form.items.push({
        title: "",
        description: "",
        item_note: "",
        quantity: 1,
        value_per_unit: 0,
        total_line_value: 0,
        total_line_weight: 0,
    });
};

const removeItem = (index) => {
    if (form.items.length > 1) form.items.splice(index, 1);
};

const handleFileChange = (e) => {
    form.files = Array.from(e.target.files);
};

const submitForm = () => {
    form.date = new Date(form.date).toLocaleString("en-US");
    form.transform((data) => {
        const payload = new FormData();
        payload.append("id", editPackage.id);
        payload.append("from", data.from);
        payload.append("date_received", data.date);
        payload.append("sender_id", data.sender_id);
        payload.append("total_value", data.totalPrice);
        payload.append("weight", data.totalWeight);
        payload.append("tracking_id", data.tracking_no);
        payload.append("status", data.status);

        const mappedItems = data.items.map((item) => ({
            ...item,
            total_line_value: parseFloat(
                (item.quantity * item.value_per_unit).toFixed(2)
            ),
        }));

        payload.append("items", JSON.stringify(mappedItems));

        data.files.forEach((file, index) => {
            payload.append(`files[${index}]`, file);
        });

        return payload;
    }).post(route("admin.packages.store"), {
        preserveScroll: true,
        onSuccess: () => console.log("OK"),
    });
};

onMounted(() => {
    calculateTotals();
});
</script>

<template>
    <EditLayout :package="props.package">
        <form @submit.prevent="submitForm" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body grid grid-cols-2 gap-6">
                    <div>
                        <InputLabel for="from" value="From" />
                        <TextInput
                            id="from"
                            v-model="form.from"
                            type="text"
                            placeholder="Enter company name e.g Amazon"
                            class="w-full"
                        />
                        <InputError class="mt-2" :message="form.errors.from" />
                    </div>

                    <div>
                        <InputLabel for="date" value="Date Received" />
                        <VueDatePicker
                            v-model="form.date"
                            class="w-full rounded-md text-black border-gray-300 shadow-sm"
                        />
                        <InputError class="mt-2" :message="form.errors.date" />
                    </div>
                    <div class="">
                        <InputLabel value="Tracking No" />
                        <TextInput
                            type="text"
                            v-model="form.tracking_no"
                            class="w-full"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.tracking_no"
                        />
                    </div>
                    <div class="">
                        <InputLabel id="packageStatus" value="Status" />
                        <SearchableSelect
                            id="packageStatus"
                            :options="packageStatus"
                            :reduce="(option) => option.id"
                            label="name"
                            v-model="form.status"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.tracking_no"
                        />
                    </div>
                    <div class="col-span-2 mt-4">
                        <h2 class="font-semibold text-lg">
                            Sender Information
                        </h2>
                    </div>

                    <div class="col-span-2">
                        <InputLabel for="sender" value="Select Sender" />
                        <SearchableSelect
                            id="sender_id"
                            class="mt-1 w-full"
                            label="name"
                            :options="props.customers"
                            :reduce="(option) => option.id"
                            v-model="form.sender_id"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.sender_id"
                        />
                    </div>

                    <div class="col-span-2">
                        <InputLabel for="files" value="Upload Files" />
                        <TextInput
                            type="file"
                            @change="handleFileChange"
                            class="w-full"
                            multiple
                        />
                        <div
                            class="py-4 grid sm:text-center sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3"
                        >
                            <div
                                class="w-40"
                                v-for="file in editPackage.files"
                                :key="file?.id"
                            >
                                <img :src="file.file_with_url" width="100%" />
                            </div>
                        </div>
                    </div>
                    <div class="col-span-2 mt-4">
                        <h2 class="font-semibold text-lg">Add package items</h2>
                        <div class="mt-2 text-end">
                            <PrimaryButton
                                type="button"
                                @click="addItem"
                                class="bg-green-600 hover:bg-green-700"
                            >
                                + Add More Items
                            </PrimaryButton>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <div
                            v-for="(item, index) in form.items"
                            :key="index"
                            class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-4 border p-4 rounded relative"
                        >
                            <div class="col-span-full text-right">
                                <button
                                    v-if="form.items.length > 1"
                                    type="button"
                                    @click="removeItem(index)"
                                    class="text-red-600 text-sm absolute top-2 right-2"
                                >
                                    Remove
                                </button>
                            </div>

                            <div>
                                <InputLabel
                                    :for="'title' + index"
                                    value="Title"
                                />
                                <TextInput
                                    v-model="item.title"
                                    type="text"
                                    class="w-full"
                                />
                            </div>
                            <div>
                                <InputLabel
                                    :for="'description' + index"
                                    value="Description"
                                />
                                <TextInput
                                    v-model="item.description"
                                    type="text"
                                    class="w-full"
                                />
                            </div>
                            <div>
                                <InputLabel
                                    :for="'note' + index"
                                    value="Note"
                                />
                                <TextInput
                                    v-model="item.item_note"
                                    type="text"
                                    class="w-full"
                                />
                            </div>
                            <div>
                                <InputLabel
                                    :for="'quantity' + index"
                                    value="Quantity"
                                />
                                <TextInput
                                    v-model.number="item.quantity"
                                    type="number"
                                    class="w-full"
                                />
                            </div>
                            <div>
                                <InputLabel
                                    :for="'valuePerUnit' + index"
                                    value="Value per unit"
                                />
                                <TextInput
                                    v-model.number="item.value_per_unit"
                                    type="number"
                                    class="w-full"
                                    step="any"
                                />
                            </div>
                            <div>
                                <InputLabel
                                    :for="'totalLineValue' + index"
                                    value="Total line value"
                                />
                                <TextInput
                                    v-model="item.total_line_value"
                                    readonly
                                    step="any"
                                    class="w-full bg-gray-200"
                                />
                            </div>
                            <div>
                                <InputLabel
                                    :for="'weight' + index"
                                    value="Weight in lbs"
                                />
                                <TextInput
                                    v-model.number="item.total_line_weight"
                                    type="number"
                                    class="w-full"
                                    step="any"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="">
                        <InputLabel value="Total Price" />
                        <TextInput
                            :value="form.totalPrice"
                            readonly
                            class="w-full bg-gray-200"
                            step="any"
                        />
                    </div>
                    <div class="">
                        <InputLabel value="Total Weight" />
                        <TextInput
                            :value="form.totalWeight"
                            readonly
                            class="w-full bg-gray-200"
                            step="any"
                        />
                    </div>

                    <div
                        class="col-span-2 text-end flex items-center justify-end gap-2"
                    >
                        <Delete @click.prevent.stop :id="editPackage.id">
                            Delete
                        </Delete>
                        <PrimaryButton
                            type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded"
                            :disabled="form.processing"
                        >
                            Update package
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </form>
    </EditLayout>
</template>
