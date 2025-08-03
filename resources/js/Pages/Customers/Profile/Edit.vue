<script setup>
import NavLink from "@/Components/NavLink.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
</script>
<template>
    <AuthenticatedLayout>
        <div class="space-x-4">
            <NavLink
                :href="route('customer.account.profile')"
                :active="route().current('customer.account.profile')"
                >Contact information</NavLink
            >
        </div>
        <div class="mt-6">
            <slot />
        </div>
    </AuthenticatedLayout>
</template>
