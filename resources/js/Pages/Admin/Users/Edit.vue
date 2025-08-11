<script setup>
import NavLink from "@/Components/NavLink.vue";

const props = defineProps({
    user: Object,
});
</script>
<template>
    <div class="space-x-4">
        <NavLink
            :href="route('admin.users.userEdit', props?.user?.id)"
            :active="route().current('admin.users.userEdit')"
        >
            Info
        </NavLink>
        <!-- <NavLink :href="route('admin.leaguesEditFlights', props.league.id)" :active="route().current('admin.leaguesEditFlights')">
                Flights
            </NavLink> -->
    </div>

    <!-- Page Content -->
    <div class="mt-6">
        <slot />
    </div>
</template>
