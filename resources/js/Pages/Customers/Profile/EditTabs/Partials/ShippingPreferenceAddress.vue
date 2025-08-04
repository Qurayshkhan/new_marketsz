<script setup>
import { ref, watch, computed } from "vue";

const props = defineProps({
    address: Object,
});

const emit = defineEmits(["setAddress"]);

const options = [
    {
        label: "US Shipping Preferences",
        value: 1,
        flag: "https://www.marketsz.com/content/images/flags/us_flag.png",
        type: "is_us",
    },
    {
        label: "UK Shipping Preferences",
        value: 4,
        flag: "https://www.marketsz.com/content/images/flags/gb_flag.png",
        type: "is_uk",
    },
];

const isOpen = ref(false);
const selectedOption = ref(
    props.address?.is_default_uk ? options[1] : options[0]
);
watch(
    () => props.address,
    (newVal) => {
        selectedOption.value = newVal?.is_default_uk ? options[1] : options[0];
    },
    { immediate: true, deep: true }
);

function toggleDropdown() {
    isOpen.value = !isOpen.value;
}

function selectOption(option) {
    selectedOption.value = option;
    isOpen.value = false;
    emit("setAddress", option);
}
</script>

<template>
    <div class="relative w-full max-w-[21.2rem]">
        <button
            @click="toggleDropdown"
            class="w-full flex justify-between items-center px-4 py-3 border border-gray-300 rounded-md bg-white shadow-sm"
            :aria-expanded="isOpen.toString()"
            aria-controls="shipping-options"
        >
            <span class="flex items-center space-x-2">
                <span class="font-semibold">{{ selectedOption.label }}</span>
                <img
                    :src="selectedOption.flag"
                    :alt="selectedOption.label"
                    class="w-6 h-4 object-cover"
                />
            </span>
            <svg
                class="w-4 h-4 text-gray-500"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                />
            </svg>
        </button>

        <div
            v-show="isOpen"
            id="shipping-options"
            class="absolute z-10 w-full mt-2 bg-white border border-gray-200 rounded-md shadow-lg"
        >
            <div
                v-for="option in options"
                :key="option.value"
                class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer"
                @click="selectOption(option)"
            >
                <span class="flex items-center space-x-2">
                    <span class="font-semibold">{{ option.label }}</span>
                    <img
                        :src="option.flag"
                        :alt="option.label"
                        class="w-6 h-4 object-cover"
                    />
                </span>
            </div>
        </div>
    </div>
</template>
