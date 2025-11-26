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
const isOpenAddress = ref(false);

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
        <div class="grid items-start gap-4 md:grid-cols-10">
            <!-- Left Side -->
            <div class="col-span-2">
                <p class="text-lg font-semibold">
                    Suite: {{ $page?.props?.auth?.user?.suite }}
                </p>
            </div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <!-- Right Side -->
            <div class="col-span-3 space-y-2">
                <div class="relative bg-white border border-gray-200 shadow-md">
                    <!-- Header -->
                    <div
                        class="cursor-pointer bg-[#f3f3f4] text-[#9e1d22] font-[700] p-2 flex justify-between items-center"
                   @click="isOpenAddress = !isOpenAddress">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 ml-2 text-sm"
                                >US Address</span
                            >
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-4 space-y-2"  :class="isOpenAddress ? 'block z-10 absolute bg-white w-full shadow-md' : 'hidden'" >
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-700"
                                >Address 1:</span
                            >
                            <span
                                class="flex items-center gap-2 text-sm text-gray-700"
                            >
                                2900 NW 112th Ave
                                <i
                                    class="cursor-pointer fas fa-copy text-primary-500"
                                    @click="copyText('2900 NW 112th Ave')"
                                ></i>
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-700"
                                >Address 2:</span
                            >
                            <span
                                class="flex items-center gap-2 text-sm text-gray-700"
                            >
                                Suite (customer) - Unit 2F
                                <i
                                    class="cursor-pointer fas fa-copy text-primary-500"
                                    @click="
                                        copyText('Suite (customer) - Unit 2F')
                                    "
                                ></i>
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-700"
                                >City:</span
                            >
                            <span
                                class="flex items-center gap-2 text-sm text-gray-700"
                            >
                                Doral
                                <i
                                    class="cursor-pointer fas fa-copy text-primary-500"
                                    @click="copyText('Doral')"
                                ></i>
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-700"
                                >State:</span
                            >
                            <span
                                class="flex items-center gap-2 text-sm text-gray-700"
                            >
                                Florida
                                <i
                                    class="cursor-pointer fas fa-copy text-primary-500"
                                    @click="copyText('Florida')"
                                ></i>
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-700"
                                >Postal Code:</span
                            >
                            <span
                                class="flex items-center gap-2 text-sm text-gray-700"
                            >
                                33172
                                <i
                                    class="cursor-pointer fas fa-copy text-primary-500"
                                    @click="copyText('33172')"
                                ></i>
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-700"
                                >Country:</span
                            >
                            <span
                                class="flex items-center gap-2 text-sm text-gray-700"
                            >
                                United States
                                <i
                                    class="cursor-pointer fas fa-copy text-primary-500"
                                    @click="copyText('United States')"
                                ></i>
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-700"
                                >Phone:</span
                            >
                            <span
                                class="flex items-center gap-2 text-sm text-gray-700"
                            >
                                +1 9414910433
                                <i
                                    class="cursor-pointer fas fa-copy text-primary-500"
                                    @click="copyText('+1 9414910433')"
                                ></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex flex-wrap gap-2 pb-2 mt-6 border-b">
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

        <div class="w-full mt-6">
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
