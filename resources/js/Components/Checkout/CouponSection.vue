<template>
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Apply Coupon</h3>

        <!-- Coupon Input -->
        <div class="flex gap-2 mb-4">
            <input
                v-model="couponCode"
                type="text"
                placeholder="Enter coupon code"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                :disabled="appliedCoupon"
            />
            <button
                @click="applyCoupon"
                :disabled="!couponCode || loading || appliedCoupon"
                class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white px-4 py-2 rounded-md"
            >
                {{ loading ? "Applying..." : "Apply" }}
            </button>
            <button
                v-if="appliedCoupon"
                @click="removeCoupon"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md"
            >
                Remove
            </button>
        </div>

        <!-- Error/Success Messages -->
        <div v-if="message" class="mb-4">
            <div
                :class="[
                    'p-3 rounded-md text-sm',
                    messageType === 'success'
                        ? 'bg-green-100 text-green-800'
                        : 'bg-red-100 text-red-800',
                ]"
            >
                {{ message }}
            </div>
        </div>

        <!-- Applied Coupon Display -->
        <div
            v-if="appliedCoupon"
            class="bg-green-50 border border-green-200 rounded-md p-4"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-800">
                        Coupon: {{ appliedCoupon.code }}
                    </p>
                    <p class="text-sm text-green-600">
                        {{
                            appliedCoupon.discount_type === "percentage"
                                ? `${appliedCoupon.discount_value}% off`
                                : `$${appliedCoupon.discount_value} off`
                        }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-green-800">
                        -${{ appliedDiscount.toFixed(2) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";

const props = defineProps({
    orderAmount: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(["coupon-applied", "coupon-removed"]);

const couponCode = ref("");
const loading = ref(false);
const message = ref("");
const messageType = ref("");
const appliedCoupon = ref(null);
const appliedDiscount = ref(0);

const applyCoupon = async () => {
    if (!couponCode.value.trim()) return;

    loading.value = true;
    message.value = "";
    messageType.value = "";

    try {
        const response = await fetch(route("customer.coupons.validate"), {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({
                code: couponCode.value.trim().toUpperCase(),
                order_amount: props.orderAmount,
            }),
        });

        const result = await response.json();

        if (result.success) {
            appliedCoupon.value = result.coupon;
            appliedDiscount.value = result.discount;
            message.value = result.message;
            messageType.value = "success";
            emit("coupon-applied", {
                coupon: result.coupon,
                discount: result.discount,
            });
        } else {
            message.value = result.message;
            messageType.value = "error";
        }
    } catch (error) {
        console.log("🚀 ~ applyCoupon ~ error:", error);
        message.value = "An error occurred while applying the coupon.";
        messageType.value = "error";
    } finally {
        loading.value = false;
    }
};

const removeCoupon = () => {
    appliedCoupon.value = null;
    appliedDiscount.value = 0;
    couponCode.value = "";
    message.value = "";
    messageType.value = "";
    emit("coupon-removed");
};

// Clear message after 5 seconds
watch(message, (newMessage) => {
    if (newMessage) {
        setTimeout(() => {
            message.value = "";
            messageType.value = "";
        }, 5000);
    }
});
</script>
