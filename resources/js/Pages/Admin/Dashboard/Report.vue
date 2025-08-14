<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    saleAmount: Number,
    totalCustomers: Number,
});
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <div class="w-full">
            <div class="stats stats-vertical lg:stats-horizontal shadow w-full">
                <div class="stat">
                    <div class="stat-title">Sales</div>
                    <div class="stat-value">
                        {{ __currency_format(saleAmount) }}
                    </div>
                </div>

                <div class="stat">
                    <div class="stat-title">New Customers</div>
                    <div class="stat-value">
                        {{ __number_format(totalCustomers) }}
                    </div>
                </div>

                <div class="stat">
                    <div class="stat-title">New Registers</div>
                    <div class="stat-value">1,200</div>
                    <!-- <div class="stat-desc">↘︎ 90 (14%)</div> -->
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
