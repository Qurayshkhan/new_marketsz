<script setup>
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
</script>

<template>
    <!-- <Head title="My Suite" /> -->

    <AuthenticatedLayout>
        <div class="flex gap-2 border-b pb-2">
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
