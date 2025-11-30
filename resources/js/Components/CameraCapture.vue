<template>
    <div class="w-full">
        <div v-if="showCamera" class="flex flex-col items-center space-y-3">
            <video
                ref="video"
                autoplay
                class="w-64 h-48 bg-black rounded-lg"
            ></video>

            <PrimaryButton type="button" @click="capturePhoto">
                Take Picture
            </PrimaryButton>

            <SecondaryButton type="button" @click="closeCamera">
                Close Camera
            </SecondaryButton>
        </div>

        <PrimaryButton
            v-if="!showCamera"
            @click="openCamera"
            class="w-full h-full text-center"
        >
            <i class="mr-2 fa-solid fa-camera"></i> Take Picture
        </PrimaryButton>

        <div
            v-if="photos.length && isPreview"
            class="grid grid-cols-4 gap-3 mt-4"
        >
            <div v-for="(photo, index) in photos" :key="index" class="relative">
                <img
                    :src="photo"
                    class="object-cover w-full h-full rounded-md"
                />

                <button
                    type="button"
                    @click="removePhoto(index)"
                    class="absolute px-1 text-xs text-white bg-red-600 rounded top-1 right-1"
                >
                    X
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import PrimaryButton from "./PrimaryButton.vue";
import SecondaryButton from "./SecondaryButton.vue";

const props = defineProps({
    isPreview: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["add-photo"]);

const showCamera = ref(false);
const stream = ref(null);
const video = ref(null);
const photos = ref([]);

const openCamera = async () => {
    showCamera.value = true;
    stream.value = await navigator.mediaDevices.getUserMedia({ video: true });
    video.value.srcObject = stream.value;
};

const closeCamera = () => {
    if (stream.value) {
        stream.value.getTracks().forEach((track) => track.stop());
    }
    showCamera.value = false;
};

const capturePhoto = () => {
    const canvas = document.createElement("canvas");
    canvas.width = 640;
    canvas.height = 480;

    const ctx = canvas.getContext("2d");
    ctx.drawImage(video.value, 0, 0, canvas.width, canvas.height);

    const base64 = canvas.toDataURL("image/jpeg");
    photos.value.push(base64);

    const file = base64ToFile(base64, `camera-photo-${Date.now()}.jpg`);

    emit("add-photo", file);
};

const removePhoto = (index) => {
    photos.value.splice(index, 1);
};

function base64ToFile(dataurl, filename) {
    let arr = dataurl.split(",");
    let mime = arr[0].match(/:(.*?);/)[1];
    let bstr = atob(arr[1]);
    let n = bstr.length;
    let u8arr = new Uint8Array(n);

    while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
    }
    return new File([u8arr], filename, { type: mime });
}
</script>
