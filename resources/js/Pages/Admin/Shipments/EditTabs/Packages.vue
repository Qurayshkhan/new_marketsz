<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Edit from "../Edit.vue";
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";
import { Head } from "@inertiajs/vue3";
import axios from "axios";

const props = defineProps({
    ship: Object,
});
console.log("🚀 ~ props.ship:", props.ship);
const viewAllPackages = props.ship.packages;
const expandedRows = ref(new Set());
const isShowUploadInvoiceModal = ref(false);
const isShowPhotosModal = ref(false);
const files = ref([]);
const packagePhotos = ref([]);

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

const showPackagePhotos = (packageId) => {
    const selectedPackage = viewAllPackages.find((pkg) => pkg.id === packageId);
    if (selectedPackage && selectedPackage.files) {
        packagePhotos.value = selectedPackage.files;
    } else {
        packagePhotos.value = [];
    }
    isShowPhotosModal.value = true;
};

const closeModal = () => {
    isShowUploadInvoiceModal.value = false;
    isShowPhotosModal.value = false;
    files.value = [];
    packagePhotos.value = [];
};
</script>
<template>
    <AuthenticatedLayout>
        <Head title="Shipment Packages" />
        <Edit :ship="props?.ship">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12">
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
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template
                                v-for="shipPackage in ship.packages"
                                :key="shipPackage.id"
                            >
                                <tr>
                                    <td
                                        @click="toggleRow(shipPackage.id)"
                                        class="cursor-pointer"
                                    >
                                        <i
                                            :class="[
                                                'fas',
                                                expandedRows.has(shipPackage.id)
                                                    ? 'fa-chevron-down'
                                                    : 'fa-chevron-right',
                                                'text-primary-500',
                                            ]"
                                        ></i>
                                    </td>
                                    <td>{{ shipPackage?.from }}</td>
                                    <td>{{ shipPackage?.package_id }}</td>
                                    <td>
                                        {{
                                            __format_date_time(
                                                shipPackage?.date_received
                                            )
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            __to_fixed_number(
                                                shipPackage?.total_value
                                            )
                                        }}
                                        USD
                                    </td>
                                    <td>{{ shipPackage?.weight }} lbs</td>
                                    <td>
                                        <p
                                            v-if="
                                                shipPackage?.status_name ==
                                                'Ready to Send'
                                            "
                                        >
                                            <i
                                                class="fa-solid fa-check text-primary-500 font-extrabold"
                                            ></i
                                            ><br />
                                            <span class="text-red-500">
                                                {{ shipPackage?.status_name }}
                                            </span>
                                        </p>
                                        <p
                                            v-if="
                                                shipPackage?.status_name ==
                                                'Action Required'
                                            "
                                            class="text-primary-500"
                                        >
                                            <i
                                                class="fa-solid fa-triangle-exclamation"
                                            ></i>
                                            <br />
                                            <span>
                                                {{ shipPackage?.status_name }}
                                            </span>
                                        </p>
                                        <p
                                            v-if="
                                                shipPackage?.status_name ==
                                                'In Review'
                                            "
                                            class="text-primary-500"
                                        >
                                            <i
                                                class="fa-solid fa-magnifying-glass"
                                            ></i>
                                            <br />
                                            <span class="text-red-500">
                                                {{ shipPackage?.status_name }}
                                            </span>
                                        </p>
                                    </td>
                                </tr>
                                <transition name="fade">
                                    <tr
                                        v-if="expandedRows.has(shipPackage.id)"
                                        class="bg-gray-50"
                                    >
                                        <td colspan="7" class="text-left px-5">
                                            <div
                                                v-if="
                                                    shipPackage.status_name ==
                                                    'Action Required'
                                                "
                                            >
                                                <strong
                                                    >Upload Merchant
                                                    Invoice</strong
                                                >
                                                <p
                                                    class="text-sm text-gray-600"
                                                >
                                                    Please upload the merchant
                                                    invoice for this package.
                                                    When your invoice is
                                                    successfully uploaded, your
                                                    package will be placed In
                                                    Review until it is verified
                                                    by Marketsz
                                                </p>
                                                <hr />
                                            </div>
                                            <div
                                                v-if="
                                                    shipPackage.status_name ==
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
                                                        package and will email
                                                        you if it is not ready
                                                        to send within two
                                                        business days.
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
                                                                    Package
                                                                    Details
                                                                </p>
                                                                <p>
                                                                    To:
                                                                    {{
                                                                        shipPackage
                                                                            ?.user
                                                                            ?.name
                                                                    }}
                                                                </p>
                                                            </div>

                                                            <div>
                                                                <button
                                                                    class="btn bg-primary-500 text-white"
                                                                    @click="
                                                                        showPackagePhotos(
                                                                            shipPackage.id
                                                                        )
                                                                    "
                                                                >
                                                                    Photo
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th>Qty</th>
                                                    <th>
                                                        Value Per Unit (USD)
                                                    </th>
                                                    <th>
                                                        Total Line Value (USD)
                                                    </th>
                                                </thead>
                                                <tbody>
                                                    <template
                                                        v-for="item in shipPackage.items"
                                                        :key="item.id"
                                                    >
                                                        <tr
                                                            class="border bg-[#e8e7e7]"
                                                        >
                                                            <td>
                                                                <p
                                                                    class="text-lg"
                                                                >
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
                                                                {{
                                                                    item?.quantity
                                                                }}
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
                                                                        shipPackage?.weight
                                                                    }}
                                                                    lbs
                                                                </p>
                                                                <p>
                                                                    <span
                                                                        class="uppercase"
                                                                        >Total
                                                                        value of
                                                                        this
                                                                        package: </span
                                                                    >{{
                                                                        shipPackage.total_value
                                                                    }}
                                                                    USD
                                                                </p>
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
            </div>
        </Edit>
    </AuthenticatedLayout>
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
