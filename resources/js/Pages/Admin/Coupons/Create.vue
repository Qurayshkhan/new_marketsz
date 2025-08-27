<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Create New Coupon
                </h2>
                <Link
                    :href="route('admin.coupons.index')"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg"
                >
                    Back to Coupons
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Coupon Code -->
                            <div>
                                <label
                                    for="code"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Coupon Code
                                </label>
                                <div class="mt-1 flex gap-2">
                                    <input
                                        id="code"
                                        v-model="form.code"
                                        type="text"
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        :class="{
                                            'border-red-500': form.errors.code,
                                        }"
                                        placeholder="Enter coupon code or leave empty to auto-generate"
                                    />
                                    <button
                                        type="button"
                                        @click="generateCode"
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md"
                                    >
                                        Generate
                                    </button>
                                </div>
                                <p
                                    v-if="form.errors.code"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.code }}
                                </p>
                            </div>

                            <!-- Discount Type -->
                            <div>
                                <label
                                    for="discount_type"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Discount Type
                                </label>
                                <select
                                    id="discount_type"
                                    v-model="form.discount_type"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{
                                        'border-red-500':
                                            form.errors.discount_type,
                                    }"
                                >
                                    <option value="">
                                        Select discount type
                                    </option>
                                    <option value="percentage">
                                        Percentage
                                    </option>
                                    <option value="fixed">Fixed Amount</option>
                                </select>
                                <p
                                    v-if="form.errors.discount_type"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.discount_type }}
                                </p>
                            </div>

                            <!-- Discount Value -->
                            <div>
                                <label
                                    for="discount_value"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Discount Value
                                    <span
                                        v-if="
                                            form.discount_type === 'percentage'
                                        "
                                        >(%)</span
                                    >
                                    <span
                                        v-else-if="
                                            form.discount_type === 'fixed'
                                        "
                                        >($)</span
                                    >
                                </label>
                                <input
                                    id="discount_value"
                                    v-model="form.discount_value"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    :max="
                                        form.discount_type === 'percentage'
                                            ? 100
                                            : null
                                    "
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{
                                        'border-red-500':
                                            form.errors.discount_value,
                                    }"
                                    :placeholder="
                                        form.discount_type === 'percentage'
                                            ? 'e.g., 10'
                                            : 'e.g., 5.00'
                                    "
                                />
                                <p
                                    v-if="form.errors.discount_value"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.discount_value }}
                                </p>
                            </div>

                            <!-- Minimum Order Amount -->
                            <div>
                                <label
                                    for="minimum_order_amount"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Minimum Order Amount ($)
                                </label>
                                <input
                                    id="minimum_order_amount"
                                    v-model="form.minimum_order_amount"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{
                                        'border-red-500':
                                            form.errors.minimum_order_amount,
                                    }"
                                    placeholder="e.g., 50.00"
                                />
                                <p
                                    v-if="form.errors.minimum_order_amount"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.minimum_order_amount }}
                                </p>
                            </div>

                            <!-- Usage Limit -->
                            <div>
                                <label
                                    for="usage_limit"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Usage Limit
                                </label>
                                <input
                                    id="usage_limit"
                                    v-model="form.usage_limit"
                                    type="number"
                                    min="1"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{
                                        'border-red-500':
                                            form.errors.usage_limit,
                                    }"
                                    placeholder="Leave empty for unlimited usage"
                                />
                                <p
                                    v-if="form.errors.usage_limit"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.usage_limit }}
                                </p>
                            </div>

                            <!-- Expiry Date -->
                            <div>
                                <label
                                    for="expiry_date"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Expiry Date
                                </label>
                                <input
                                    id="expiry_date"
                                    v-model="form.expiry_date"
                                    type="date"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{
                                        'border-red-500':
                                            form.errors.expiry_date,
                                    }"
                                />
                                <p
                                    v-if="form.errors.expiry_date"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.expiry_date }}
                                </p>
                            </div>

                            <!-- Description -->
                            <div>
                                <label
                                    for="description"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Description
                                </label>
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    rows="3"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{
                                        'border-red-500':
                                            form.errors.description,
                                    }"
                                    placeholder="Optional description for this coupon"
                                ></textarea>
                                <p
                                    v-if="form.errors.description"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.description }}
                                </p>
                            </div>

                            <!-- Active Status -->
                            <div class="flex items-center">
                                <input
                                    id="is_active"
                                    v-model="form.is_active"
                                    type="checkbox"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                />
                                <label
                                    for="is_active"
                                    class="ml-2 block text-sm text-gray-900"
                                >
                                    Active
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end gap-4">
                                <Link
                                    :href="route('admin.coupons.index')"
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md"
                                >
                                    Cancel
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="processing"
                                    class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white px-4 py-2 rounded-md"
                                >
                                    {{
                                        processing
                                            ? "Creating..."
                                            : "Create Coupon"
                                    }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const form = useForm({
    code: "",
    discount_type: "",
    discount_value: "",
    minimum_order_amount: "",
    usage_limit: "",
    expiry_date: "",
    description: "",
    is_active: true,
});

const processing = ref(false);

const generateCode = async () => {
    try {
        const response = await fetch(route("admin.coupons.generate-code"), {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                "Content-Type": "application/json",
            },
        });

        const data = await response.json();
        if (data.success) {
            form.code = data.code;
        }
    } catch (error) {
        console.error("Failed to generate code:", error);
    }
};

const submit = () => {
    processing.value = true;
    form.post(route("admin.coupons.store"), {
        onFinish: () => {
            processing.value = false;
        },
    });
};
</script>
