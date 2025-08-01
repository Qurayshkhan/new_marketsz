<script setup>
import { ref } from "vue";
import Report from "../Report.vue";
import TextInput from "@/Components/TextInput.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import axios from "axios";
import { useToast } from "vue-toastification";

const props = defineProps({
    actions: Object,
    specialRequests: Object,
});
const toast = useToast();
const actions = props.actions;

const expandedRows = ref(new Set());
const selectedService = ref(null);
const dropdownOpen = ref(false);
const isShowNote = ref(false);
const isShowUploadInvoiceModal = ref(false);
const isShowPhotosModal = ref(false);
const addNote = ref(null);
const files = ref([]);
const packageId = ref(null);
const isUploadingInvoice = ref(false);
const packagePhotos = ref([]);
const toggleRow = (id) => {
    if (expandedRows.value.has(id)) {
        expandedRows.value.delete(id);
    } else {
        expandedRows.value.add(id);
    }
};
const toggleAll = () => {
    if (expandedRows.value.size === actions.length) {
        expandedRows.value.clear();
    } else {
        expandedRows.value = new Set(actions.map((a) => a.id));
    }
};

const allExpanded = () => expandedRows.value.size === actions.length;

const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
};

const selectService = (service) => {
    selectedService.value = service;
    dropdownOpen.value = false;
};
const handleShowNote = () => {
    isShowNote.value = !isShowNote.value;
};
const closeModal = () => {
    isShowUploadInvoiceModal.value = false;
    isShowPhotosModal.value = false;
    files.value = [];
    packagePhotos.value = [];
};
const showUploadInvoiceModal = (id) => {
    packageId.value = id;
    isShowUploadInvoiceModal.value = true;
};

const onFileChange = (e) => {
    files.value = Array.from(e.target.files);
};

const upload = async () => {
    isUploadingInvoice.value = true;
    const formData = new FormData();
    files.value.forEach((file) => formData.append("invoices[]", file));
    formData.append("package_id", packageId.value);
    formData.append("status", 2);

    try {
        const response = await axios.post(
            route("customers.packageUploadInvoices"),
            formData,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            }
        );

        toast.success(
            response.data.message || "Invoices uploaded successfully"
        );
        closeModal();
    } catch (error) {
        toast.error(
            error.response?.data?.message || "Failed to upload invoices"
        );
    } finally {
        isUploadingInvoice.value = false;
    }

    close();
};

const handleAddNote = async (e, id) => {
    e.preventDefault();
    try {
        const response = await axios.post(route("customer.packageAddNote"), {
            note: addNote.value,
            id: id,
        });
        isShowNote.value = false;
        toast.success(response.data.message);
    } catch (error) {
        toast.error(response.data.message);
    }
};
const showPackagePhotos = async (packageId) => {
    try {
        const response = await axios.get(
            route("customers.packageGetPhotos", { package_id: packageId })
        );
        packagePhotos.value = response.data.data || [];
        isShowPhotosModal.value = true;
    } catch (error) {
        toast.error("Failed to fetch photos");
    }
};
</script>

<template>
    <Report :actionCount="props?.actions">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-9">
                <table class="w-full border text-center data-table text-sm">
                    <thead class="uppercase bg-gray-100">
                        <tr>
                            <th>
                                <i
                                    @click="toggleAll"
                                    class="cursor-pointer"
                                    :class="[
                                        'fa-solid',
                                        allExpanded()
                                            ? 'fa-angles-down'
                                            : 'fa-angles-right',
                                        'text-primary-500',
                                    ]"
                                ></i>
                            </th>
                            <th>From</th>
                            <th>Package ID</th>
                            <th>Date Received</th>
                            <th class="bg-primary-500 text-white">
                                Action Required
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="action in actions" :key="action.id">
                            <tr>
                                <td
                                    @click="toggleRow(action.id)"
                                    class="cursor-pointer"
                                >
                                    <i
                                        :class="[
                                            'fas',
                                            expandedRows.has(action.id)
                                                ? 'fa-chevron-down'
                                                : 'fa-chevron-right',
                                            'text-primary-500',
                                        ]"
                                    ></i>
                                </td>
                                <td>{{ action.from }}</td>
                                <td>{{ action.package_id }}</td>
                                <td>
                                    {{
                                        __format_date_time(action.date_received)
                                    }}
                                </td>
                                <td>
                                    <div>
                                        <button
                                            class="text-primary-500"
                                            @click="
                                                showUploadInvoiceModal(
                                                    action.id
                                                )
                                            "
                                        >
                                            Upload Merchant Invoice
                                        </button>
                                        <p>As required by Customs</p>
                                    </div>
                                </td>
                            </tr>
                            <transition name="fade">
                                <tr
                                    v-if="expandedRows.has(action.id)"
                                    class="bg-gray-50"
                                >
                                    <td colspan="5" class="text-left px-5">
                                        <div>
                                            <strong
                                                >Upload Merchant Invoice</strong
                                            >
                                            <p class="text-sm text-gray-600">
                                                Please upload the merchant
                                                invoice for this package. When
                                                your invoice is successfully
                                                uploaded, your package will be
                                                placed In Review until it is
                                                verified by Marketsz
                                            </p>
                                            <hr />
                                        </div>
                                        <table class="w-full my-5">
                                            <thead>
                                                <th>
                                                    <div
                                                        class="flex items-center justify-between"
                                                    >
                                                        <div
                                                            class="flex flex-col items-start"
                                                        >
                                                            <p>
                                                                Package Details
                                                            </p>
                                                            <p>
                                                                To:
                                                                {{
                                                                    action
                                                                        ?.customer
                                                                        ?.name
                                                                }}
                                                            </p>
                                                        </div>

                                                        <div>
                                                            <button
                                                                class="btn bg-white text-black"
                                                                @click="
                                                                    showPackagePhotos(
                                                                        action.id
                                                                    )
                                                                "
                                                            >
                                                                Photo
                                                            </button>
                                                        </div>
                                                    </div>
                                                </th>
                                                <th>Qty</th>
                                                <th>Value Per Unit (USD)</th>
                                                <th>Total Line Value (USD)</th>
                                            </thead>
                                            <tbody>
                                                <template
                                                    v-for="item in action.items"
                                                    :key="item.id"
                                                >
                                                    <tr
                                                        class="border bg-[#e8e7e7]"
                                                    >
                                                        <td>
                                                            <p class="text-lg">
                                                                {{
                                                                    item?.title
                                                                }}
                                                            </p>
                                                            <p>
                                                                {{
                                                                    item?.description
                                                                }}
                                                            </p>
                                                            <p
                                                                v-if="
                                                                    item?.item_note
                                                                "
                                                            >
                                                                {{
                                                                    item?.item_note
                                                                }}
                                                            </p>
                                                        </td>
                                                        <td>
                                                            {{ item?.quantity }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                item?.value_per_unit
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                item?.total_line_value
                                                            }}
                                                        </td>
                                                    </tr>
                                                </template>
                                                <tr>
                                                    <td colspan="5">
                                                        <div
                                                            class="flex items-center justify-between"
                                                        >
                                                            <p>
                                                                <span
                                                                    class="uppercase"
                                                                    >Total
                                                                    weight: </span
                                                                >{{
                                                                    action?.weight
                                                                }}
                                                                lbs
                                                            </p>
                                                            <p>
                                                                <span
                                                                    class="uppercase"
                                                                    >Total value
                                                                    of this
                                                                    package: </span
                                                                >{{
                                                                    action.total_value
                                                                }}
                                                                USD
                                                            </p>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5">
                                                        <div
                                                            class="my-2 w-full"
                                                        >
                                                            <a
                                                                @click="
                                                                    handleShowNote()
                                                                "
                                                                href="#"
                                                                class="text-red-700"
                                                                >Add your
                                                                notes</a
                                                            >
                                                            to this package,
                                                            this is for your use
                                                            only, Marketsz will
                                                            not review this
                                                            area.
                                                        </div>
                                                        <div v-if="isShowNote">
                                                            <TextInput
                                                                class="w-full"
                                                                placeholder="Please add note here"
                                                                v-model="
                                                                    addNote
                                                                "
                                                            />
                                                            <div
                                                                class="my-2 flex gap-2 items-center"
                                                            >
                                                                <DangerButton
                                                                    @click.prevent="
                                                                        handleAddNote(
                                                                            $event,
                                                                            action.id
                                                                        )
                                                                    "
                                                                >
                                                                    Save your
                                                                    note
                                                                </DangerButton>
                                                                <a
                                                                    @click="
                                                                        handleShowNote()
                                                                    "
                                                                    href="javascript:void(0)"
                                                                    >Cancel</a
                                                                >
                                                            </div>
                                                        </div>
                                                        <p class="">
                                                            Note:
                                                            <span
                                                                class="text-red-500"
                                                                >{{
                                                                    addNote
                                                                        ? addNote
                                                                        : action?.note
                                                                }}</span
                                                            >
                                                        </p>
                                                        <hr />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5">
                                                        <div
                                                            class="flex items-center justify-between w-full"
                                                        >
                                                            <div class="w-full">
                                                                <label
                                                                    class="block text-sm font-medium text-gray-700 mb-2"
                                                                    >Optional
                                                                    Services</label
                                                                >
                                                                <div
                                                                    class="relative w-full max-w-md"
                                                                >
                                                                    <button
                                                                        type="button"
                                                                        class="w-full border border-gray-300 bg-white rounded-md shadow-sm pl-4 pr-10 py-2 text-left cursor-pointer focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-sm"
                                                                        @click="
                                                                            toggleDropdown
                                                                        "
                                                                    >
                                                                        <span
                                                                            class="block truncate"
                                                                        >
                                                                            {{
                                                                                selectedService?.title ||
                                                                                "Select Optional Service"
                                                                            }}
                                                                        </span>
                                                                        <span
                                                                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"
                                                                        >
                                                                            <i
                                                                                class="fa fa-chevron-down text-gray-400"
                                                                            ></i>
                                                                        </span>
                                                                    </button>

                                                                    <ul
                                                                        v-if="
                                                                            dropdownOpen
                                                                        "
                                                                        class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-sm ring-1 ring-black ring-opacity-5 overflow-auto"
                                                                    >
                                                                        <li
                                                                            v-for="(
                                                                                service,
                                                                                index
                                                                            ) in props?.specialRequests"
                                                                            :key="
                                                                                index
                                                                            "
                                                                            class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                                                                            @click="
                                                                                selectService(
                                                                                    service
                                                                                )
                                                                            "
                                                                        >
                                                                            <div
                                                                                class="flex justify-between font-medium"
                                                                            >
                                                                                <span
                                                                                    class="text-primary-500 fw-bold"
                                                                                    >{{
                                                                                        service.title
                                                                                    }}</span
                                                                                >
                                                                                <span
                                                                                    class="text-primary-600"
                                                                                    >${{
                                                                                        service.price
                                                                                    }}</span
                                                                                >
                                                                            </div>
                                                                            <p
                                                                                class="text-gray-500 text-xs mt-1"
                                                                            >
                                                                                {{
                                                                                    service.description
                                                                                }}
                                                                            </p>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <button
                                                                :disabled="
                                                                    isUploadingInvoice
                                                                "
                                                                type="button"
                                                                class="btn btn-big mt-4 bg-primary-600 text-white hover:bg-primary-700 disabled:bg-primary-400"
                                                                @click="
                                                                    showUploadInvoiceModal(
                                                                        action.id
                                                                    )
                                                                "
                                                            >
                                                                Upload Invoice
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </transition>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="col-span-3 bg-gray-50 p-4 rounded">
                <p>All values are in United States dollars (USD).</p>
            </div>
        </div>
    </Report>
    <Modal :show="isShowUploadInvoiceModal" @close="closeModal">
        <div class="p-6 space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">
                    Upload Merchant Invoice
                </h2>
                <button
                    @click="closeModal"
                    class="text-gray-500 hover:text-gray-800"
                >
                    Close
                </button>
            </div>

            <div>
                <input type="file" multiple @change="onFileChange($event)" />
                <p class="text-sm text-gray-600 mt-1">
                    Accepted File Types: BMP, JPG, JPEG, GIF, TIF, TIFF, PDF
                </p>
                <p class="text-sm text-gray-600">Max File Size: 2MB</p>
            </div>

            <div v-if="files.length" class="mt-4">
                <p class="font-medium text-sm mb-1">Selected Files:</p>
                <ul class="list-disc pl-5 text-sm text-gray-700 space-y-1">
                    <li v-for="(file, index) in files" :key="index">
                        {{ file.name }}
                    </li>
                </ul>
            </div>

            <div class="flex justify-end gap-2">
                <button
                    @click="closeModal()"
                    class="px-4 py-2 text-sm text-gray-700 border"
                >
                    Cancel
                </button>
                <button
                    @click="upload"
                    class="px-4 py-2 text-sm bg-primary-600 text-white"
                >
                    Upload Document
                </button>
            </div>
        </div>
    </Modal>
    <Modal :show="isShowPhotosModal" @close="closeModal">
        <div class="p-6 space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">
                    Package Photos
                </h2>
                <button
                    @click="closeModal"
                    class="text-gray-500 hover:text-gray-800"
                >
                    Close
                </button>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <img
                    v-for="(photo, index) in packagePhotos"
                    :key="index"
                    :src="photo.file_with_url"
                    alt="Package Photo"
                    class="rounded shadow border"
                />
            </div>
        </div>
    </Modal>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-5px);
}
</style>
