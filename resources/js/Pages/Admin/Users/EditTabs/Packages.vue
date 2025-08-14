<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import Edit from "../Edit.vue";
const props = defineProps({
    user: Object,
    userPackages: Object,
});
</script>
<template>
    <Head title="User Packages" />
    <AuthenticatedLayout>
        <Edit :user="props?.user">
            <div class="grid grid-cols-1">
                <div class="w-full">
                    <div class="card">
                        <div class="card-body">
                            <div class="overflow-x-auto">
                                <table class="table border">
                                    <!-- head -->
                                    <thead class="text-black">
                                        <tr>
                                            <th class="border">From</th>
                                            <th class="border">Package id</th>
                                            <th class="border">Tracking id</th>
                                            <th class="border">Total Value</th>
                                            <th class="border">Total Weight</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- row 1 -->
                                        <tr
                                            v-for="userPackage in props.userPackages"
                                            :key="userPackage?.id"
                                        >
                                            <td class="border">
                                                {{ userPackage?.from ?? "" }}
                                            </td>
                                            <td class="border">
                                                {{
                                                    userPackage?.package_id ??
                                                    ""
                                                }}
                                            </td>
                                            <td class="border">
                                                {{
                                                    userPackage?.tracking_id ??
                                                    ""
                                                }}
                                            </td>

                                            <td class="border">
                                                {{
                                                    userPackage?.total_value ??
                                                    ""
                                                }}
                                            </td>
                                            <td class="border">
                                                {{ userPackage?.weight ?? "" }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Edit>
    </AuthenticatedLayout>
</template>
