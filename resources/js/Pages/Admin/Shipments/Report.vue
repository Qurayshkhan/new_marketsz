<script setup>
import Pagination from "@/Components/Pagination.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    shipments: Object,
});

const expandedRows = ref({});

const toggleRow = (shipmentId) => {
    expandedRows.value[shipmentId] = !expandedRows.value[shipmentId];
};

const updateStatus = (shipId, status) => {
    router.post(
        route("admin.shipments.update-status", shipId),
        { status },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                // Status updated successfully
            },
        }
    );
};

const formatCurrency = (amount) => {
    return `$${parseFloat(amount || 0).toFixed(2)}`;
};

const getFileUrl = (filePath) => {
    if (!filePath) return "";

    // If already a full URL, return as is
    if (filePath.startsWith("http")) {
        return filePath;
    }

    // If path contains 'storage/app/public/', extract the part after 'public/'
    if (filePath.includes("storage/app/public/")) {
        const relativePath = filePath.split("storage/app/public/")[1];
        return `/storage/${relativePath}`;
    }

    // If path already starts with 'package_files/' or 'package_items/', use it directly
    if (
        filePath.startsWith("package_files/") ||
        filePath.startsWith("package_items/")
    ) {
        return `/storage/${filePath}`;
    }

    // Default: assume it's a relative path and add /storage/ prefix
    return `/storage/${filePath}`;
};
</script>
<template>
    <AuthenticatedLayout>
        <Head title="Shipments" />
        <div class="grid grid-cols-1">
            <div class="w-full">
                <div class="card">
                    <h1 class="text-2xl mb-4">Shipments</h1>

                    <div class="card-body">
                        <div class="overflow-x-auto">
                            <table class="table border text-center">
                                <!-- head -->
                                <thead class="text-black">
                                    <tr>
                                        <th class="border"></th>
                                        <th class="border">Ship Request #</th>
                                        <th class="border">Suite</th>
                                        <th class="border">Customer</th>
                                        <th class="border">Total Weight</th>
                                        <th class="border">Total Price</th>
                                        <th class="border">Status</th>
                                        <th class="border">Invoice Status</th>
                                        <th class="border">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- row 1 -->
                                    <template
                                        v-for="shipment in props.shipments.data"
                                        :key="shipment?.id"
                                    >
                                        <tr class="hover:bg-gray-50">
                                            <td class="border">
                                                <button
                                                    @click="
                                                        toggleRow(shipment.id)
                                                    "
                                                    class="text-[rgb(158,29,34)] hover:text-[rgb(120,20,25)]"
                                                >
                                                    <i
                                                        :class="
                                                            expandedRows[
                                                                shipment.id
                                                            ]
                                                                ? 'fas fa-chevron-down'
                                                                : 'fas fa-chevron-right'
                                                        "
                                                    ></i>
                                                </button>
                                            </td>
                                            <td class="border">
                                                {{
                                                    shipment?.tracking_number ||
                                                    `#${shipment?.id}`
                                                }}
                                            </td>
                                            <td class="border">
                                                {{
                                                    shipment?.user?.suite ??
                                                    "N/A"
                                                }}
                                            </td>
                                            <td class="border">
                                                {{
                                                    shipment?.user?.name ??
                                                    "N/A"
                                                }}
                                            </td>
                                            <td class="border">
                                                {{
                                                    shipment?.total_weight ??
                                                    "0"
                                                }}
                                                kg
                                            </td>
                                            <td class="border">
                                                {{
                                                    formatCurrency(
                                                        shipment?.total_price
                                                    )
                                                }}
                                            </td>
                                            <td class="border">
                                                <span
                                                    :class="{
                                                        'bg-yellow-100 text-yellow-800':
                                                            shipment?.status ===
                                                            'pending',
                                                        'bg-blue-100 text-blue-800':
                                                            shipment?.status ===
                                                            'shipped',
                                                        'bg-green-100 text-green-800':
                                                            shipment?.status ===
                                                            'delivered',
                                                        'bg-red-100 text-red-800':
                                                            shipment?.status ===
                                                            'cancelled',
                                                    }"
                                                    class="px-2 py-1 rounded-full text-xs font-medium"
                                                >
                                                    {{
                                                        shipment?.status
                                                            ?.charAt(0)
                                                            .toUpperCase() +
                                                        shipment?.status?.slice(
                                                            1
                                                        )
                                                    }}
                                                </span>
                                            </td>
                                            <td class="border">
                                                <span
                                                    :class="{
                                                        'bg-yellow-100 text-yellow-800':
                                                            shipment?.invoice_status ===
                                                            'pending',
                                                        'bg-green-100 text-green-800':
                                                            shipment?.invoice_status ===
                                                            'paid',
                                                        'bg-red-100 text-red-800':
                                                            shipment?.invoice_status ===
                                                            'unpaid',
                                                    }"
                                                    class="px-2 py-1 rounded-full text-xs font-medium"
                                                >
                                                    {{
                                                        shipment?.invoice_status
                                                            ?.charAt(0)
                                                            .toUpperCase() +
                                                        shipment?.invoice_status?.slice(
                                                            1
                                                        )
                                                    }}
                                                </span>
                                            </td>
                                            <td class="text-center border">
                                                <div
                                                    class="flex items-center justify-center gap-2"
                                                >
                                                    <button
                                                        v-if="
                                                            shipment?.status ===
                                                            'pending'
                                                        "
                                                        @click="
                                                            updateStatus(
                                                                shipment.id,
                                                                'shipped'
                                                            )
                                                        "
                                                        class="px-3 py-1 bg-[rgb(158,29,34)] text-white rounded hover:bg-[rgb(120,20,25)] text-sm"
                                                    >
                                                        Ready to Ship
                                                    </button>
                                                    <Link
                                                        :href="
                                                            route(
                                                                'admin.shipments.edit',
                                                                {
                                                                    ship: shipment?.id,
                                                                }
                                                            )
                                                        "
                                                        class="text-[rgb(158,29,34)] hover:text-[rgb(120,20,25)]"
                                                    >
                                                        <i
                                                            class="fa fa-angle-right"
                                                            aria-hidden="true"
                                                        ></i>
                                                    </Link>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Expanded Details Row -->
                                        <tr
                                            v-if="expandedRows[shipment.id]"
                                            class="bg-gray-50"
                                        >
                                            <td colspan="9" class="border p-4">
                                                <div
                                                    class="text-left space-y-4"
                                                >
                                                    <!-- Ship Request Information -->
                                                    <div
                                                        class="grid grid-cols-1 md:grid-cols-2 gap-4"
                                                    >
                                                        <div>
                                                            <h3
                                                                class="font-semibold text-lg mb-2"
                                                            >
                                                                Ship Request
                                                                Information
                                                            </h3>
                                                            <div
                                                                class="space-y-1 text-sm"
                                                            >
                                                                <p>
                                                                    <strong
                                                                        >Ship
                                                                        Request
                                                                        #:</strong
                                                                    >
                                                                    {{
                                                                        shipment?.tracking_number ||
                                                                        `#${shipment?.id}`
                                                                    }}
                                                                </p>
                                                                <p>
                                                                    <strong
                                                                        >Suite
                                                                        Number:</strong
                                                                    >
                                                                    {{
                                                                        shipment
                                                                            ?.user
                                                                            ?.suite ??
                                                                        "N/A"
                                                                    }}
                                                                </p>
                                                                <p>
                                                                    <strong
                                                                        >Customer:</strong
                                                                    >
                                                                    {{
                                                                        shipment
                                                                            ?.user
                                                                            ?.name ??
                                                                        "N/A"
                                                                    }}
                                                                </p>
                                                                <p>
                                                                    <strong
                                                                        >Email:</strong
                                                                    >
                                                                    {{
                                                                        shipment
                                                                            ?.user
                                                                            ?.email ??
                                                                        "N/A"
                                                                    }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <!-- Shipment Details -->
                                                        <div>
                                                            <h3
                                                                class="font-semibold text-lg mb-2"
                                                            >
                                                                Shipment Details
                                                            </h3>
                                                            <div
                                                                class="space-y-1 text-sm"
                                                            >
                                                                <p>
                                                                    <strong
                                                                        >Total
                                                                        Weight:</strong
                                                                    >
                                                                    {{
                                                                        shipment?.total_weight ??
                                                                        "0"
                                                                    }}
                                                                    kg
                                                                </p>
                                                                <p>
                                                                    <strong
                                                                        >Total
                                                                        Dimensions:</strong
                                                                    >
                                                                    N/A
                                                                </p>
                                                                <p>
                                                                    <strong
                                                                        >Total
                                                                        Price:</strong
                                                                    >
                                                                    {{
                                                                        formatCurrency(
                                                                            shipment?.total_price
                                                                        )
                                                                    }}
                                                                </p>
                                                                <p>
                                                                    <strong
                                                                        >Status:</strong
                                                                    >
                                                                    <span
                                                                        :class="{
                                                                            'text-yellow-600':
                                                                                shipment?.status ===
                                                                                'pending',
                                                                            'text-blue-600':
                                                                                shipment?.status ===
                                                                                'shipped',
                                                                            'text-green-600':
                                                                                shipment?.status ===
                                                                                'delivered',
                                                                            'text-red-600':
                                                                                shipment?.status ===
                                                                                'cancelled',
                                                                        }"
                                                                        class="font-semibold ml-2"
                                                                    >
                                                                        {{
                                                                            shipment?.status
                                                                                ?.charAt(
                                                                                    0
                                                                                )
                                                                                .toUpperCase() +
                                                                            shipment?.status?.slice(
                                                                                1
                                                                            )
                                                                        }}
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Shipment Method & Address -->
                                                    <div>
                                                        <h3
                                                            class="font-semibold text-lg mb-2"
                                                        >
                                                            Shipment Method &
                                                            Address
                                                        </h3>
                                                        <div
                                                            class="bg-white p-3 rounded border"
                                                        >
                                                            <p
                                                                v-if="
                                                                    shipment?.internationalShipping
                                                                "
                                                                class="text-sm mb-2"
                                                            >
                                                                <strong
                                                                    >Shipping
                                                                    Method:</strong
                                                                >
                                                                {{
                                                                    shipment
                                                                        ?.internationalShipping
                                                                        ?.title ??
                                                                    "N/A"
                                                                }}
                                                            </p>
                                                            <div
                                                                v-if="
                                                                    shipment?.userAddress
                                                                "
                                                                class="text-sm"
                                                            >
                                                                <strong
                                                                    >Delivery
                                                                    Address:</strong
                                                                >
                                                                <div
                                                                    class="mt-1 pl-4"
                                                                >
                                                                    <p>
                                                                        {{
                                                                            shipment
                                                                                ?.userAddress
                                                                                ?.full_name
                                                                        }}
                                                                    </p>
                                                                    <p>
                                                                        {{
                                                                            shipment
                                                                                ?.userAddress
                                                                                ?.address_line_1
                                                                        }}
                                                                    </p>
                                                                    <p
                                                                        v-if="
                                                                            shipment
                                                                                ?.userAddress
                                                                                ?.address_line_2
                                                                        "
                                                                    >
                                                                        {{
                                                                            shipment
                                                                                ?.userAddress
                                                                                ?.address_line_2
                                                                        }}
                                                                    </p>
                                                                    <p>
                                                                        {{
                                                                            shipment
                                                                                ?.userAddress
                                                                                ?.city
                                                                        }},
                                                                        {{
                                                                            shipment
                                                                                ?.userAddress
                                                                                ?.state
                                                                        }}
                                                                        {{
                                                                            shipment
                                                                                ?.userAddress
                                                                                ?.postal_code
                                                                        }}
                                                                    </p>
                                                                    <p>
                                                                        {{
                                                                            shipment
                                                                                ?.userAddress
                                                                                ?.country
                                                                        }}
                                                                    </p>
                                                                    <p>
                                                                        Phone:
                                                                        {{
                                                                            shipment
                                                                                ?.userAddress
                                                                                ?.phone_number
                                                                        }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <p
                                                                v-else
                                                                class="text-sm text-gray-500"
                                                            >
                                                                No address
                                                                available
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <!-- Additional Services -->
                                                    <div>
                                                        <h3
                                                            class="font-semibold text-lg mb-2"
                                                        >
                                                            Additional Services
                                                        </h3>
                                                        <div
                                                            class="bg-white p-3 rounded border"
                                                        >
                                                            <div
                                                                v-if="
                                                                    shipment
                                                                        ?.packing_options
                                                                        ?.length >
                                                                    0
                                                                "
                                                                class="mb-3"
                                                            >
                                                                <strong
                                                                    class="text-sm"
                                                                    >Packing
                                                                    Options:</strong
                                                                >
                                                                <ul
                                                                    class="list-disc pl-6 mt-1"
                                                                >
                                                                    <li
                                                                        v-for="option in shipment?.packing_options"
                                                                        :key="
                                                                            option.id
                                                                        "
                                                                        class="text-sm"
                                                                    >
                                                                        {{
                                                                            option.title
                                                                        }}
                                                                        -
                                                                        {{
                                                                            option.description
                                                                        }}
                                                                        <span
                                                                            v-if="
                                                                                option.price
                                                                            "
                                                                            class="text-green-600 font-semibold"
                                                                        >
                                                                            ({{
                                                                                formatCurrency(
                                                                                    option.price
                                                                                )
                                                                            }})
                                                                        </span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div
                                                                v-if="
                                                                    shipment
                                                                        ?.shipping_preference_options
                                                                        ?.length >
                                                                    0
                                                                "
                                                            >
                                                                <strong
                                                                    class="text-sm"
                                                                    >Shipping
                                                                    Preference
                                                                    Options:</strong
                                                                >
                                                                <ul
                                                                    class="list-disc pl-6 mt-1"
                                                                >
                                                                    <li
                                                                        v-for="option in shipment?.shipping_preference_options"
                                                                        :key="
                                                                            option.id
                                                                        "
                                                                        class="text-sm"
                                                                    >
                                                                        {{
                                                                            option.title
                                                                        }}
                                                                        -
                                                                        {{
                                                                            option.description
                                                                        }}
                                                                        <span
                                                                            v-if="
                                                                                option.price
                                                                            "
                                                                            class="text-green-600 font-semibold"
                                                                        >
                                                                            ({{
                                                                                formatCurrency(
                                                                                    option.price
                                                                                )
                                                                            }})
                                                                        </span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <p
                                                                v-if="
                                                                    (!shipment?.packing_options ||
                                                                        shipment
                                                                            ?.packing_options
                                                                            ?.length ===
                                                                            0) &&
                                                                    (!shipment?.shipping_preference_options ||
                                                                        shipment
                                                                            ?.shipping_preference_options
                                                                            ?.length ===
                                                                            0)
                                                                "
                                                                class="text-sm text-gray-500"
                                                            >
                                                                No additional
                                                                services
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <!-- Packages Information -->
                                                    <div>
                                                        <h3
                                                            class="font-semibold text-lg mb-2"
                                                        >
                                                            Consolidated
                                                            Packages ({{
                                                                shipment
                                                                    ?.packages
                                                                    ?.length ||
                                                                0
                                                            }})
                                                        </h3>
                                                        <div
                                                            v-if="
                                                                shipment
                                                                    ?.packages
                                                                    ?.length > 0
                                                            "
                                                            class="space-y-3"
                                                        >
                                                            <div
                                                                v-for="pkg in shipment?.packages"
                                                                :key="pkg.id"
                                                                class="bg-white p-4 rounded border"
                                                            >
                                                                <div
                                                                    class="flex justify-between items-start mb-2"
                                                                >
                                                                    <div>
                                                                        <p
                                                                            class="font-semibold"
                                                                        >
                                                                            Package
                                                                            ID:
                                                                            {{
                                                                                pkg?.package_id
                                                                            }}
                                                                        </p>
                                                                        <p
                                                                            class="text-sm text-gray-600"
                                                                        >
                                                                            Tracking
                                                                            ID:
                                                                            {{
                                                                                pkg?.tracking_id ||
                                                                                "N/A"
                                                                            }}
                                                                        </p>
                                                                        <p
                                                                            class="text-sm text-gray-600"
                                                                        >
                                                                            From:
                                                                            {{
                                                                                pkg?.from
                                                                            }}
                                                                        </p>
                                                                        <p
                                                                            class="text-sm text-gray-600"
                                                                        >
                                                                            Weight:
                                                                            {{
                                                                                pkg?.weight
                                                                            }}
                                                                            kg
                                                                        </p>
                                                                        <p
                                                                            class="text-sm text-gray-600"
                                                                        >
                                                                            Value:
                                                                            {{
                                                                                formatCurrency(
                                                                                    pkg?.total_value
                                                                                )
                                                                            }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <!-- Package Files -->
                                                                <div
                                                                    v-if="
                                                                        pkg
                                                                            ?.files
                                                                            ?.length >
                                                                        0
                                                                    "
                                                                    class="mt-3"
                                                                >
                                                                    <strong
                                                                        class="text-sm"
                                                                        >Documents/Photos:</strong
                                                                    >
                                                                    <div
                                                                        class="flex flex-wrap gap-2 mt-2"
                                                                    >
                                                                        <a
                                                                            v-for="file in pkg?.files"
                                                                            :key="
                                                                                file.id
                                                                            "
                                                                            :href="
                                                                                file.file_with_url ||
                                                                                getFileUrl(
                                                                                    file.file
                                                                                )
                                                                            "
                                                                            target="_blank"
                                                                            class="text-blue-600 hover:text-blue-800 text-sm underline"
                                                                        >
                                                                            <i
                                                                                class="fas fa-file mr-1"
                                                                            ></i>
                                                                            {{
                                                                                file.name
                                                                            }}
                                                                        </a>
                                                                    </div>
                                                                </div>

                                                                <!-- Package Items with Files -->
                                                                <div
                                                                    v-if="
                                                                        pkg
                                                                            ?.items
                                                                            ?.length >
                                                                        0
                                                                    "
                                                                    class="mt-3"
                                                                >
                                                                    <strong
                                                                        class="text-sm"
                                                                        >Items:</strong
                                                                    >
                                                                    <div
                                                                        class="space-y-2 mt-2 pl-4"
                                                                    >
                                                                        <div
                                                                            v-for="item in pkg?.items"
                                                                            :key="
                                                                                item.id
                                                                            "
                                                                            class="border-l-2 border-gray-300 pl-3"
                                                                        >
                                                                            <p
                                                                                class="font-medium"
                                                                            >
                                                                                {{
                                                                                    item.title
                                                                                }}
                                                                            </p>
                                                                            <p
                                                                                v-if="
                                                                                    item.description
                                                                                "
                                                                                class="text-sm text-gray-600"
                                                                            >
                                                                                {{
                                                                                    item.description
                                                                                }}
                                                                            </p>
                                                                            <p
                                                                                class="text-xs text-gray-500"
                                                                            >
                                                                                Qty:
                                                                                {{
                                                                                    item.quantity
                                                                                }}
                                                                                |
                                                                                Value:
                                                                                {{
                                                                                    formatCurrency(
                                                                                        item.total_line_value
                                                                                    )
                                                                                }}
                                                                            </p>
                                                                            <!-- Item Files -->
                                                                            <div
                                                                                v-if="
                                                                                    item
                                                                                        ?.packageFiles
                                                                                        ?.length >
                                                                                    0
                                                                                "
                                                                                class="mt-1"
                                                                            >
                                                                                <span
                                                                                    class="text-xs text-gray-500"
                                                                                    >Files:</span
                                                                                >
                                                                                <div
                                                                                    class="flex flex-wrap gap-1 mt-1"
                                                                                >
                                                                                    <a
                                                                                        v-for="file in item?.packageFiles"
                                                                                        :key="
                                                                                            file.id
                                                                                        "
                                                                                        :href="
                                                                                            file.file_with_url ||
                                                                                            getFileUrl(
                                                                                                file.file
                                                                                            )
                                                                                        "
                                                                                        target="_blank"
                                                                                        class="text-xs text-blue-600 hover:text-blue-800 underline"
                                                                                    >
                                                                                        {{
                                                                                            file.name
                                                                                        }}
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Package Invoices -->
                                                                <div
                                                                    v-if="
                                                                        pkg
                                                                            ?.invoices
                                                                            ?.length >
                                                                        0
                                                                    "
                                                                    class="mt-3"
                                                                >
                                                                    <strong
                                                                        class="text-sm"
                                                                        >Invoices:</strong
                                                                    >
                                                                    <div
                                                                        class="flex flex-wrap gap-2 mt-2"
                                                                    >
                                                                        <a
                                                                            v-for="invoice in pkg?.invoices"
                                                                            :key="
                                                                                invoice.id
                                                                            "
                                                                            :href="
                                                                                invoice.image_with_url ||
                                                                                getFileUrl(
                                                                                    invoice.image
                                                                                )
                                                                            "
                                                                            target="_blank"
                                                                            class="text-blue-600 hover:text-blue-800 text-sm underline"
                                                                        >
                                                                            <i
                                                                                class="fas fa-file-invoice mr-1"
                                                                            ></i>
                                                                            Invoice
                                                                            #{{
                                                                                invoice.id
                                                                            }}
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p
                                                            v-else
                                                            class="text-sm text-gray-500"
                                                        >
                                                            No packages found
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <Pagination
                            :links="props.shipments.links"
                            :from="props.shipments.from"
                            :to="props.shipments.to"
                            :total="props.shipments.total"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
