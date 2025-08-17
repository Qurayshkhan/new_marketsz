<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
const props = defineProps({
    filters: Object,
});
const search = ref(props?.filters?.search);

watch([search], ([searchValue], [oldSearchValue]) => {
    router.get(route("admin.users"), {
        search: searchValue,
    });
});
</script>
<template>
    <div
        class="flex max-lg:flex-col justify-between gap-3 mt-2 p-5 items-center"
    >
        <form>
            <div>
                <InputLabel value="Search" class="text-xl" />
                <TextInput
                    placeholder="Search name, suite,email"
                    v-model="search"
                />
            </div>
        </form>
        <div>
            <Link :href="route('admin.users.createUser')">
                <PrimaryButton>+ Create User</PrimaryButton>
            </Link>
        </div>
    </div>
</template>
