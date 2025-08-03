<script setup>
import { ref } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TabLink from "@/Components/TabLink.vue";

const currentUrl = usePage().url;
const props = defineProps({
    actionCount: Object,
    inReviewCount: Object,
    readyToSendCount: Object,
    allPackagesCount: Object,
});

const activeIndex = ref(null);

const toggle = (index) => {
    activeIndex.value = activeIndex.value === index ? null : index;
};

const copyText = (text) => {
    navigator.clipboard.writeText(text);
    alert(`Copy text successfully. ${text}`);
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="grid grid-cols-4 gap-4 items-start">
            <!-- Left Side -->
            <div class="">
                <p class="text-lg font-semibold">
                    Packages in Suite {{ $page?.props?.auth?.user?.suite }}
                </p>
            </div>
            <div></div>
            <div></div>
            <!-- Right Side -->
            <div class="space-y-2">
                <div
                    v-for="(address, index) in $page.props.auth.user
                        .default_addresses"
                    :key="index"
                    class="shadow-md border border-gray-200 bg-white relative"
                >
                    <!-- Header -->
                    <div
                        class="cursor-pointer bg-[#f3f3f4] text-[#9e1d22] font-[700] p-2 flex justify-between items-center"
                        @click="toggle(index)"
                    >
                        <div class="flex items-center gap-2">
                            <span class="ml-2 text-sm px-2 py-1">
                                {{ index === 1 ? "UK Address" : "US Address" }}
                            </span>
                        </div>
                        <i
                            class="fas"
                            :class="
                                activeIndex === index
                                    ? 'fa-chevron-up'
                                    : 'fa-chevron-down'
                            "
                        ></i>
                    </div>

                    <!-- Content -->
                    <transition name="fade">
                        <div
                            v-show="activeIndex === index"
                            class="p-4 space-y-2"
                        >
                            <div
                                v-for="(field, label) in {
                                    'Address 1': 'address_line_1',
                                    'Address 2': 'address_line_2',
                                    City: 'city',
                                    State: 'state',
                                    Country: 'country',
                                    'Postal Code': 'postal_code',
                                }"
                                :key="label"
                                v-if="
                                    field !== 'address_line_2' ||
                                    address['address_line_2']
                                "
                                class="flex justify-between items-center"
                            >
                                <span
                                    class="text-sm text-gray-700 font-semibold"
                                    >{{ label }}:</span
                                >
                                <span
                                    class="flex items-center gap-2 text-sm text-gray-700"
                                >
                                    {{ address[field] }}
                                    <i
                                        class="fas fa-copy text-primary-500 cursor-pointer"
                                        @click="copyText(address[field])"
                                    ></i>
                                </span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span
                                    class="text-sm text-gray-700 font-semibold"
                                    >Phone:</span
                                >
                                <span
                                    class="flex items-center gap-2 text-sm text-gray-700"
                                >
                                    +{{ address.country_code }}
                                    {{ address.phone_number }}
                                    <i
                                        class="fas fa-copy text-primary-500 cursor-pointer"
                                        @click="
                                            copyText(
                                                '+' +
                                                    address.country_code +
                                                    ' ' +
                                                    address.phone_number
                                            )
                                        "
                                    ></i>
                                </span>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex gap-2 border-b pb-2 mt-6">
            <TabLink
                label="Action Required"
                :href="route('customer.suiteActionRequired')"
                :count="props?.actionCount"
                color="red"
                :active="currentUrl === '/customer/suite/action-required'"
            />

            <TabLink
                label="In Review"
                :href="route('customer.suite.inReview')"
                :count="props?.inReviewCount"
                color="yellow"
                :active="currentUrl === '/customer/suite/in-review'"
            />
            <TabLink
                label="Ready to Send"
                :href="route('customer.suite.readyToSend')"
                :count="props?.readyToSendCount"
                color="green"
                :active="currentUrl === '/customer/suite/ready-to-send'"
            />
            <TabLink
                label="View All"
                :href="route('customer.suite.viewAll')"
                :count="props?.allPackagesCount"
                color="slate"
                :active="currentUrl === '/customer/suite/view-all'"
            />
        </div>

        <div class="mt-6 w-full">
            <slot />
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: scaleY(0.95);
}
</style>
