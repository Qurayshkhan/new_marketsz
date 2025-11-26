<script setup>
import { ref } from "vue";
import Report from "../Report.vue";
import TextInput from "@/Components/TextInput.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import axios from "axios";
import { useToast } from "vue-toastification";
import { Head, router } from "@inertiajs/vue3";
import PackageLinks from "@/Components/Packages/PackageLinks.vue";
import CurrencyDollarText from "@/Components/Packages/CurrencyDollarText.vue";

const props = defineProps({
    viewAllPackages: Object,
    specialRequests: Object,
    packageCounts: Array,
});
const toast = useToast();
const viewAllPackages = props.viewAllPackages;

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
const previews = ref([]);
const toggleRow = (id) => {
    if (expandedRows.value.has(id)) {
        expandedRows.value.delete(id);
    } else {
        expandedRows.value.add(id);
    }
};
const toggleAll = () => {
    if (expandedRows.value.size === viewAllPackages.length) {
        expandedRows.value.clear();
    } else {
        expandedRows.value = new Set(viewAllPackages.map((a) => a.id));
    }
};

const allExpanded = () => expandedRows.value.size === viewAllPackages.length;

const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
};

const selectService = (service, id) => {
    selectedService.value = service;
    try {
        const response = axios.post(
            route("customer.packageSetSpecialRequest"),
            {
                package_id: id,
                special_request: service.id,
            }
        );
        toast.success(
            response.message || "Special request added successfully."
        );
    } catch (error) {
        toast.error(error);
    } finally {
        dropdownOpen.value = false;
    }
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
    const selectedFiles = Array.from(e.target.files);
    files.value = selectedFiles;
    previews.value = [];

    selectedFiles.forEach((file) => {
        if (file.type.startsWith("image/")) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previews.value.push(e.target.result);
            };
            reader.readAsDataURL(file);
        } else {
            previews.value.push(null); // for non-images (like PDF)
        }
    });
};
const removeImage = (index) => {
    files.value.splice(index, 1);
    previews.value.splice(index, 1);
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
        router.visit(route("customer.suite.inReview"));
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
    <Head title="Action Required" />
    <Report
        :actionCount="props?.packageCounts.action_required"
        :inReviewCount="props?.packageCounts?.in_review"
        :readyToSendCount="props?.packageCounts?.ready_to_send"
        :allPackagesCount="props?.packageCounts?.all"
    >
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-9">
                <table class="w-full text-sm text-center border data-table">
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
                            <th>Total value</th>
                            <th>Total weight</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template
                            v-for="action in viewAllPackages"
                            :key="action.id"
                        >
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
                                <td>{{ action?.from }}</td>
                                <td>{{ action?.package_id }}</td>
                                <td>
                                    {{
                                        __format_date_time(
                                            action?.date_received
                                        )
                                    }}
                                </td>
                                <td>
                                    {{ __to_fixed_number(action?.total_value) }}
                                    USD
                                </td>
                                <td>{{ action?.weight }} lbs</td>
                                <td>
                                    <p
                                        v-if="
                                            action?.status_name ==
                                            'Ready to Send'
                                        "
                                    >
                                        <i
                                            class="font-extrabold fa-solid fa-check text-primary-500"
                                        ></i
                                        ><br />
                                        <span class="text-red-500">
                                            {{ action?.status_name }}
                                        </span>
                                    </p>
                                    <p
                                        v-if="
                                            action?.status_name ==
                                            'Action Required'
                                        "
                                        class="text-primary-500"
                                    >
                                        <i
                                            class="fa-solid fa-triangle-exclamation"
                                        ></i>
                                        <br />
                                        <span>
                                            {{ action?.status_name }}
                                        </span>
                                    </p>
                                    <p
                                        v-if="
                                            action?.status_name == 'In Review'
                                        "
                                        class="text-primary-500"
                                    >
                                        <i
                                            class="fa-solid fa-magnifying-glass"
                                        ></i>
                                        <br />
                                        <span class="text-red-500">
                                            {{ action?.status_name }}
                                        </span>
                                    </p>
                                </td>
                            </tr>
                            <transition name="fade">
                                <tr
                                    v-if="expandedRows.has(action.id)"
                                    class="bg-gray-50"
                                >
                                    <td colspan="7" class="px-5 text-left">
                                        <div
                                            v-if="
                                                action.status_name ==
                                                'Action Required'
                                            "
                                        >
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
                                        <div
                                            v-if="
                                                action.status_name ==
                                                'In Review'
                                            "
                                        >
                                            <div>
                                                <strong class="bold"
                                                    >Why is this package in
                                                    review?</strong
                                                ><br />
                                                <p
                                                    class="text-sm text-white bg-[#f19445] uppercase px-2 inline-block"
                                                >
                                                    Dangerous Goods
                                                </p>
                                                <p class="py-1">
                                                    We are reviewing your
                                                    package and will email you
                                                    if it is not ready to send
                                                    within two business days.
                                                </p>
                                                <hr />
                                            </div>
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
                                                                class="text-white btn bg-primary-500"
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
                                                            <p
                                                                class="text-gray-600 text-md"
                                                            >
                                                                {{
                                                                    item?.description
                                                                }}
                                                            </p>
                                                            <p
                                                                class="text-sm"
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
                                                    <td colspan="6">
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
                                                    <td colspan="7">
                                                        <div
                                                            class="w-full my-2"
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
                                                                class="flex items-center gap-2 my-2"
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
                                                    <td colspan="7">
                                                        <div
                                                            class="flex items-center justify-between w-full"
                                                        >
                                                            <div class="w-full">
                                                                <label
                                                                    class="block mb-2 text-sm font-medium text-gray-700"
                                                                    >Optional
                                                                    Services</label
                                                                >
                                                                <div
                                                                    class="relative w-full max-w-md"
                                                                >
                                                                    <button
                                                                        type="button"
                                                                        class="w-full py-2 pl-4 pr-10 text-sm text-left bg-white border border-gray-300 rounded-md shadow-sm cursor-pointer focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
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
                                                                                class="text-gray-400 fa fa-chevron-down"
                                                                            ></i>
                                                                        </span>
                                                                    </button>

                                                                    <ul
                                                                        v-if="
                                                                            dropdownOpen
                                                                        "
                                                                        class="absolute z-10 w-full py-1 mt-1 overflow-auto text-sm bg-white rounded-md shadow-lg max-h-60 ring-1 ring-black ring-opacity-5"
                                                                    >
                                                                        <li
                                                                            v-for="(
                                                                                service,
                                                                                index
                                                                            ) in props?.specialRequests"
                                                                            :key="
                                                                                index
                                                                            "
                                                                            class="px-4 py-2 cursor-pointer hover:bg-gray-100"
                                                                            @click="
                                                                                selectService(
                                                                                    service,
                                                                                    action.id
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
                                                                                class="mt-1 text-xs text-gray-500"
                                                                            >
                                                                                {{
                                                                                    service.description
                                                                                }}
                                                                            </p>
                                                                        </li>
                                                                    </ul>
                                                                </div>

                                                                <div
                                                                    class="py-2"
                                                                    v-if="
                                                                        action.special_request
                                                                    "
                                                                >
                                                                    <p
                                                                        class="bold"
                                                                    >
                                                                        Your
                                                                        current
                                                                        special
                                                                        request
                                                                        is:
                                                                        <span
                                                                            class="text-primary-800"
                                                                        >
                                                                            {{
                                                                                action
                                                                                    .special_request
                                                                                    ?.title ??
                                                                                ""
                                                                            }}
                                                                        </span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <button
                                                                v-if="
                                                                    action.status_name ==
                                                                    'Action Required'
                                                                "
                                                                :disabled="
                                                                    isUploadingInvoice
                                                                "
                                                                type="button"
                                                                class="mt-4 text-white btn btn-big bg-primary-600 hover:bg-primary-700 disabled:bg-primary-400"
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
            <div class="col-span-3 p-4 rounded bg-gray-50">
                <CurrencyDollarText />
                <PackageLinks />
            </div>
        </div>
    </Report>
    <Modal :show="isShowUploadInvoiceModal" @close="closeModal">
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between">
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
                <input
                    type="file"
                    multiple
                    @change="onFileChange($event)"
                    accept=".bmp, .jpg, .jpeg, .gif, .tif, .tiff, .pdf"
                />
                <p class="mt-1 text-sm text-gray-600">
                    Accepted File Types: BMP, JPG, JPEG, GIF, TIF, TIFF, PDF
                </p>
                <p class="text-sm text-gray-600">Max File Size: 2MB</p>
            </div>

            <div v-if="files.length" class="mt-4">
                <p class="mb-1 text-sm font-medium">Selected Files:</p>
                <ul class="pl-5 space-y-1 text-sm text-gray-700 list-disc">
                    <li v-for="(file, index) in files" :key="index">
                        {{ file.name }}
                    </li>
                </ul>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4 md:grid-cols-3">
                <template v-for="(preview, index) in previews" :key="index">
                    <div class="relative group">
                        <img
                            v-if="preview"
                            :src="preview"
                            alt="Preview"
                            class="w-full h-auto border rounded shadow"
                        />
                        <button
                            @click="removeImage(index)"
                            class="absolute p-1 text-xs text-red-500 bg-white border border-red-300 rounded-full top-1 right-1 opacity-80 group-hover:opacity-100 hover:bg-red-100"
                            title="Remove"
                        >
                            ❌
                        </button>

                        <div
                            v-if="!preview"
                            class="p-4 text-sm text-gray-500 bg-gray-100 border rounded"
                        >
                            {{ files[index].type }} preview not supported.
                            <button
                                @click="removeImage(index)"
                                class="ml-2 text-xs text-red-500 underline"
                            >
                                Remove
                            </button>
                        </div>
                    </div>
                </template>
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
                    class="px-4 py-2 text-sm text-white bg-primary-600"
                >
                    Upload Document
                </button>
            </div>
        </div>
    </Modal>
    <Modal :show="isShowPhotosModal" @close="closeModal">
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between">
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

            <div
                class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4"
                v-if="packagePhotos.length > 0"
            >
                <img
                    v-for="(photo, index) in packagePhotos"
                    :key="index"
                    :src="photo.file_with_url"
                    alt="Package Photo"
                    class="border rounded shadow"
                />
            </div>
            <div class="text-center text-gray-900" v-else>
                <h3>No photos available</h3>
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
