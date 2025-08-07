<script setup>
import DangerButton from "@js/Components/DangerButton.vue";
import SecondaryButton from "@js/Components/SecondaryButton.vue";
import Modal from "@js/Components/Modal.vue";

import { ref } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    ship: String,
});

const confirmingRecordDeletion = ref(false);

const confirmRecordDeletion = () => {
    confirmingRecordDeletion.value = true;
};

const closeModal = () => {
    confirmingRecordDeletion.value = false;
};

const form = useForm({
    national_id: props.ship?.national_id || "",
});
const addNationalId = (event) => {
    event.target.disabled = true;

    form.post(
        route("customer.ship.packages.nationalId", { id: props.ship?.id }),
        {
            onFinish: () => {
                closeModal();
            },
        }
    );
};
</script>

<template>
    <div class="">
        <p>
            National ID:
            <a href="#" class="text-primary-500" @click="confirmRecordDeletion"
                >Add</a
            >
        </p>

        <Modal :show="confirmingRecordDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-start text-lg font-medium text-gray-900">
                    Add National Id
                </h2>
                <div>
                    <TextInput v-model="form.national_id" required />
                    <InputError :message="form?.errors.national_id" />
                </div>

                <div class="flex mt-6 gap-4 justify-end">
                    <SecondaryButton @click="closeModal"
                        >Cancel</SecondaryButton
                    >
                    <DangerButton @click="addNationalId"
                        >Add National Id</DangerButton
                    >
                </div>
            </div>
        </Modal>
    </div>
</template>
