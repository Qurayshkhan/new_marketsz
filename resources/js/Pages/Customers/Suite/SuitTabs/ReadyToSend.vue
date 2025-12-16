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
import { Head, Link, router } from "@inertiajs/vue3";

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
const estimatedAmount = ref(0);

const totalValue = ref(0);
const totalWeight = ref(0);
const totalPackages = ref(0);

watch(selectedIds, () => {
    bulkCheckbox.value = selectedIds.value.length === readyToSends.length;
    calculateEstimatedShipment();
    calculateTotals();
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
                    // toast.success("Ship request created successfully.");
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

const calculateEstimatedShipment = async () => {
    const response = await axios.post(
        route("admin.packages.calculateEstimatedShipment"),
        {
            package_id: selectedIds.value,
        }
    );
    const { amount } = response.data;
    console.log("🚀 ~ calculateEstimatedShipment ~ amount:", amount);
    estimatedAmount.value = amount;
};

const calculateTotals = () => {
    const selected = readyToSends.filter((item) =>
        selectedIds.value.includes(item.id)
    );

    totalPackages.value = selected.length;
    totalValue.value = selected.reduce(
        (sum, p) => sum + Number(p.total_value),
        0
    );
    totalWeight.value = selected.reduce((sum, p) => sum + Number(p.weight), 0);
};
onMounted(() => {
    selectedIds.value = readyToSends.map((item) => item.id);
    calculateEstimatedShipment();
    calculateTotals();
});
</script>

<template>
    <Head title="Ready to send" />
    <Report
        :actionCount="props?.packageCounts.action_required"
        :inReviewCount="props?.packageCounts?.in_review"
        :readyToSendCount="props?.packageCounts?.ready_to_send"
        :allPackagesCount="props?.packageCounts?.all"
    >
        <div class="grid md:grid-cols-12 gap-2">
            <div class="col-span-12 md:col-span-9">
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
                            <th>
                                <input
                                    class="border-gray-300 shadow-sm text-primary-600 focus:ring-primary-500"
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
                                        __currency_format(
                                            readyToSend.total_value
                                        )
                                    }}
                                    USD
                                </td>
                                <td>{{ readyToSend.weight }} lbs</td>
                                <td class="whitespace-nowrap !text-center">
                                    <input
                                        class="border-gray-300 shadow-sm text-primary-600 focus:ring-primary-500"
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
                                    <td colspan="6" class="px-5 text-left">
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
                                                                class="text-white btn bg-primary-500"
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
                                                        <td class="text-center">
                                                            {{ item?.quantity }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{
                                                                __currency_format(
                                                                    item?.value_per_unit
                                                                )
                                                            }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{
                                                                __currency_format(
                                                                    item?.total_line_value
                                                                )
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
                                                                    __currency_format(
                                                                        readyToSend.total_value
                                                                    )
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
                                                                                class="mt-1 text-xs text-gray-500"
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
            <div class="p-4 rounded md:col-span-3 bg-gray-50">
                <CurrencyDollarText />
                <div class="col-span-3 p-4 mt-4 bg-white rounded shadow">
                    <div class="flex flex-wrap items-center justify-between">
                        <h3 class="mb-2 text-lg font-semibold">
                            Estimated Shipping:
                        </h3>
                        <div
                            class="flex flex-wrap items-center justify-between w-full"
                        >
                            <p>Total Values</p>
                            <p>{{ __currency_format(totalValue) }}</p>
                        </div>
                        <div
                            class="flex flex-wrap items-center justify-between w-full"
                        >
                            <p>Total Weight</p>
                            <p>{{ totalWeight }} lbs</p>
                        </div>
                        <div
                            class="flex flex-wrap items-center justify-between w-full"
                        >
                            <p>Packages</p>
                            <p>{{ totalPackages }}</p>
                        </div>
                        <p class="mt-2 font-medium text-red-600">
                            {{ __currency_format(estimatedAmount) }}
                        </p>
                    </div>
                    <p class="text-sm text-gray-700">How is this calculated?</p>
                    <p class="mt-2 text-sm text-gray-700">
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
                    <p class="mt-2 text-sm text-gray-600">
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
