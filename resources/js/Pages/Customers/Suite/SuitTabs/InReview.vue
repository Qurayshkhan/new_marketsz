<script setup>
import { ref } from "vue";
import Report from "../Report.vue";
import TextInput from "@/Components/TextInput.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import axios from "axios";
import { useToast } from "vue-toastification";

const props = defineProps({
    inReviews: Object,
    specialRequests: Object,
    packageCounts: Array,
});
const toast = useToast();
const inReviews = props.inReviews;

const expandedRows = ref(new Set());
const isShowNote = ref(false);
const isShowUploadInvoiceModal = ref(false);
const isShowPhotosModal = ref(false);
const addNote = ref(null);
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
    if (expandedRows.value.size === inReviews.length) {
        expandedRows.value.clear();
    } else {
        expandedRows.value = new Set(inReviews.map((a) => a.id));
    }
};

const allExpanded = () => expandedRows.value.size === inReviews.length;

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
</script>

<template>
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
                        </tr>
                    </thead>
                    <tbody>
                        <template
                            v-for="in_review in inReviews"
                            :key="in_review.id"
                        >
                            <tr>
                                <td
                                    @click="toggleRow(in_review.id)"
                                    class="cursor-pointer"
                                >
                                    <i
                                        :class="[
                                            'fas',
                                            expandedRows.has(in_review.id)
                                                ? 'fa-chevron-down'
                                                : 'fa-chevron-right',
                                            'text-primary-500',
                                        ]"
                                    ></i>
                                </td>
                                <td>{{ in_review.from }}</td>
                                <td>{{ in_review.package_id }}</td>
                                <td>
                                    {{
                                        __format_date_time(
                                            in_review.date_received
                                        )
                                    }}
                                </td>
                                <td>
                                    {{
                                        __to_fixed_number(in_review.total_value)
                                    }}
                                    USD
                                </td>
                                <td>{{ in_review.weight }} lbs</td>
                            </tr>
                            <transition name="fade">
                                <tr
                                    v-if="expandedRows.has(in_review.id)"
                                    class="bg-gray-50"
                                >
                                    <td colspan="6" class="text-left px-5">
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
                                                We are reviewing your package
                                                and will email you if it is not
                                                ready to send within two
                                                business days.
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
                                                                    in_review
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
                                                                        in_review.id
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
                                                    v-for="item in in_review.items"
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
                                                                    in_review?.weight
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
                                                                    in_review.total_value
                                                                }}
                                                                USD
                                                            </p>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        colspan="6"
                                                        class="text-gray-500"
                                                    >
                                                        **Values shown are
                                                        obtained from the
                                                        merchant invoices, when
                                                        available. Researched
                                                        values based on current
                                                        market prices have been
                                                        provided above for any
                                                        items that arrived
                                                        without invoices. The
                                                        value should be updated
                                                        to reflect the actual
                                                        price paid for each
                                                        item, and must be
                                                        confirmed.
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
                                                                            in_review.id
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
                                                                        : in_review?.note
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
                <p>All values are in United States dollars (USD).</p>
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
