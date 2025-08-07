<template>
    <div class="success-container">
        <div class="card">
            <div class="icon">
                <svg viewBox="0 0 24 24" width="64" height="64">
                    <path
                        fill="green"
                        d="M12 0C5.371 0 0 5.371 0 12s5.371 12 12 12 12-5.371 12-12S18.629 0 12 0zm5.707 9.293l-6.364 6.364a1 1 0 0 1-1.414 0l-3.182-3.182a1 1 0 0 1 1.414-1.414l2.475 2.475 5.657-5.657a1 1 0 0 1 1.414 1.414z"
                    />
                </svg>
            </div>
            <h1>Payment Successful!</h1>
            <p>Your shipment has been processed successfully.</p>

            <div class="shipment-details">
                <h2>Shipment Details</h2>
                <ul>
                    <li>
                        <strong>Name:</strong>
                        {{ shipment?.user?.name }}
                    </li>
                    <li>
                        <strong>Email:</strong>
                        {{ shipment?.user?.email }}
                    </li>
                    <li>
                        <strong>Shipment tracking number:</strong>
                        {{ shipment?.tracking_number }}
                    </li>
                    <li>
                        <strong>Estimated Charges:</strong> ${{
                            shipment?.estimated_shipping_charges
                        }}
                    </li>
                    <li>
                        <strong>Address:</strong>
                        {{ shipment?.user_address?.address_line_1 }}
                    </li>
                    <li>
                        <strong>Shipping Option:</strong>
                        {{ shipment?.international_shipping_option_id }}
                    </li>
                </ul>
            </div>

            <Link
                class="receipt-link cursor-pointer"
                :href="route('customer.dashboard')"
                target="_blank"
                >Home</Link
            >
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    shipment: Object,
    receiptUrl: String,
});

console.log("🚀 ~ props.shipment:", props.shipment);
</script>

<style scoped>
.success-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 90vh;
    background-color: #f5f8fa;
    padding: 2rem;
}
.card {
    background: white;
    border-radius: 16px;
    padding: 2.5rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    text-align: center;
    max-width: 480px;
    width: 100%;
}
.icon {
    margin-bottom: 1.5rem;
    display: flex;
    justify-content: center;
    align-items: center;
}
h1 {
    color: #2dca73;
    margin-bottom: 0.5rem;
}
p {
    margin-bottom: 2rem;
}
.shipment-details {
    text-align: left;
    margin-bottom: 2rem;
}
.shipment-details ul {
    list-style: none;
    padding: 0;
}
.shipment-details li {
    margin-bottom: 0.5rem;
}
.receipt-link {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    background: #2dca73;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
}
.receipt-link:hover {
    background: #25a15c;
}
</style>
