<script setup>
import { onMounted, ref, watch } from "vue";
import Report from "../Report.vue";
import TextInput from "@/Components/TextInput.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import axios from "axios";
import { useToast } from "vue-toastification";
import Checkbox from "@/Components/Checkbox.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import PackageLinks from "@/Components/Packages/PackageLinks.vue";
import CurrencyDollarText from "@/Components/Packages/CurrencyDollarText.vue";
import { Link, router } from "@inertiajs/vue3";

const props = defineProps({
    readyToSends: Object,
    specialRequests: Object,
    packageCounts: Array,
});
const toast = useToast();
const readyToSends = props.readyToSends;

const expandedRows = ref(new Set());
const isShowNote = ref(false);
const isShowUploadInvoiceModal = ref(false);
const isShowPhotosModal = ref(false);
const addNote = ref(null);
const files = ref([]);
const packagePhotos = ref([]);
const selectedIds = ref([]);
const bulkCheckbox = ref(false);
const selectedService = ref(null);
const dropdownOpen = ref(false);
watch(selectedIds, () => {
    bulkCheckbox.value = selectedIds.value.length === readyToSends.length;
});

const selectAll = (e) => {
    selectedIds.value = e.target.checked
        ? readyToSends.map((item) => item.id)
        : [];
};
const resetSelection = () => {
    bulkCheckbox.value = false;
    selectedIds.value = [];
};

const toggleRow = (id) => {
    if (expandedRows.value.has(id)) {
        expandedRows.value.delete(id);
    } else {
        expandedRows.value.add(id);
    }
};
const toggleAll = () => {
    if (expandedRows.value.size === readyToSends.length) {
        expandedRows.value.clear();
    } else {
        expandedRows.value = new Set(readyToSends.map((a) => a.id));
    }
};

const allExpanded = () => expandedRows.value.size === readyToSends.length;
const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
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

onMounted(() => {
    selectedIds.value = readyToSends.map((item) => item.id);
});

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

const handleCreateShipRequest = async () => {
    if (selectedIds.value.length === 0) {
        toast.error(
            "Please select at least one package to create a ship request."
        );
        return;
    }
    try {
        router.post(
            route("customer.shipment.create"),
            {
                package_ids: selectedIds.value,
            },
            {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success("Ship request created successfully.");
                    resetSelection();
                },
                onError: (error) => {
                    toast.error(
                        error.response.data.message ||
                            "Failed to create ship request."
                    );
                },
            }
        );
    } catch (error) {
        toast.error(
            error.response.data.message || "Failed to create ship request."
        );
    }
};
</script>

<template>
    <Head title="Ready to send" />
    <Report
        :actionCount="props?.packageCounts.action_required"
        :inReviewCount="props?.packageCounts?.in_review"
        :readyToSendCount="props?.packageCounts?.ready_to_send"
        :allPackagesCount="props?.packageCounts?.all"
    >
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
                            <th>Total value</th>
                            <th>Total weight</th>
                            <th>
                                <input
                                    class="border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500"
                                    type="checkbox"
                                    v-model="bulkCheckbox"
                                    @change="selectAll"
                                />
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template
                            v-for="readyToSend in readyToSends"
                            :key="readyToSend.id"
                        >
                            <tr>
                                <td
                                    @click="toggleRow(readyToSend.id)"
                                    class="cursor-pointer"
                                >
                                    <i
                                        :class="[
                                            'fas',
                                            expandedRows.has(readyToSend.id)
                                                ? 'fa-chevron-down'
                                                : 'fa-chevron-right',
                                            'text-primary-500',
                                        ]"
                                    ></i>
                                </td>
                                <td>{{ readyToSend.from }}</td>
                                <td>{{ readyToSend.package_id }}</td>
                                <td>
                                    {{
                                        __format_date_time(
                                            readyToSend.date_received
                                        )
                                    }}
                                </td>
                                <td>
                                    {{
                                        __to_fixed_number(
                                            readyToSend.total_value
                                        )
                                    }}
                                    USD
                                </td>
                                <td>{{ readyToSend.weight }} lbs</td>
                                <td class="whitespace-nowrap !text-center">
                                    <input
                                        class="border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500"
                                        type="checkbox"
                                        :value="readyToSend.id"
                                        v-model="selectedIds"
                                    />
                                </td>
                            </tr>
                            <transition name="fade">
                                <tr
                                    v-if="expandedRows.has(readyToSend.id)"
                                    class="bg-gray-50"
                                >
                                    <td colspan="6" class="text-left px-5">
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
                                                                    readyToSend
                                                                        ?.customer
                                                                        ?.name
                                                                }}
                                                            </p>
                                                        </div>

                                                        <div>
                                                            <button
                                                                class="btn bg-primary-500 text-white"
                                                                @click="
                                                                    showPackagePhotos(
                                                                        readyToSend.id
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
                                                    v-for="item in readyToSend.items"
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
                                                                class="text-md text-gray-600"
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
                                                                    readyToSend?.weight
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
                                                                    readyToSend.total_value
                                                                }}
                                                                USD
                                                            </p>
                                                        </div>
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
                                                                                    service,
                                                                                    readyToSend.id
                                                                                )
                                                                            "
                                                                        >
                                                                            <div
                                                                                class="flex justify-between font-medium"
                                                                            >
                                                                                <span
                                                                                    class="text-primary-500 fw-bold"
                                                                                    >{{
                                                                                        service?.title
                                                                                    }}</span
                                                                                >
                                                                                <span
                                                                                    class="text-primary-600"
                                                                                    >${{
                                                                                        service?.price
                                                                                    }}</span
                                                                                >
                                                                            </div>
                                                                            <p
                                                                                class="text-gray-500 text-xs mt-1"
                                                                            >
                                                                                {{
                                                                                    service?.description
                                                                                }}
                                                                            </p>
                                                                        </li>
                                                                    </ul>
                                                                </div>

                                                                <div
                                                                    class="py-2"
                                                                    v-if="
                                                                        readyToSend?.special_request
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
                                                                                readyToSend
                                                                                    .special_request
                                                                                    ?.title ??
                                                                                ""
                                                                            }}
                                                                        </span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6">
                                                        <p
                                                            class="text-gray-500"
                                                        >
                                                            **Values shown are
                                                            obtained from the
                                                            merchant invoices,
                                                            when available.
                                                            Researched values
                                                            based on current
                                                            market prices have
                                                            been provided above
                                                            for any items that
                                                            arrived without
                                                            invoices. The value
                                                            should be updated to
                                                            reflect the actual
                                                            price paid for each
                                                            item, and must be
                                                            confirmed.
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6">
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
                                                                            readyToSend.id
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
                                                                        : readyToSend?.note
                                                                }}</span
                                                            >
                                                        </p>
                                                        <hr />
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
                <CurrencyDollarText />
                <div class="col-span-3 bg-white p-4 mt-4 rounded shadow">
                    <div class="flex items-center justify-between md:flex-wrap">
                        <h3 class="text-lg font-semibold mb-2">
                            Estimated Shipping:
                        </h3>
                        <p class="text-red-600 font-medium mt-2">N/A</p>
                    </div>
                    <p class="text-sm text-gray-700">How is this calculated?</p>
                    <p class="text-sm text-gray-700 mt-2">
                        One or more packages in this ship request cannot be
                        delivered. Please contact customer service for more
                        information.
                    </p>
                    <div class="text-center">
                        <PrimaryButton
                            class="mt-4 font-medium"
                            @click="handleCreateShipRequest"
                        >
                            Create ship request
                        </PrimaryButton>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">
                        All items are subject to a customs duty upon receipt of
                        package. Payment will be due when your package is
                        delivered.
                    </p>
                </div>

                <PackageLinks />
            </div>
        </div>
    </Report>

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

            <div
                class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
                v-if="packagePhotos.length > 0"
            >
                <img
                    v-for="(photo, index) in packagePhotos"
                    :key="index"
                    :src="photo.file_with_url"
                    alt="Package Photo"
                    class="rounded shadow border"
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
