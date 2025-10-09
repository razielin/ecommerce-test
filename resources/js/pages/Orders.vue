<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { getOrders } from '@/api/admin';
import { AdminOrder } from '@/types/model';

const orders = ref<AdminOrder[]>([]);
const isLoading = ref(true);
const error = ref<string | null>(null);

function getProductName(order: AdminOrder, productId: number): string {
    const product = order.products?.find((p) => +p.id === +productId);
    return product ? product.name : `#${productId}`;
}

function getStatusColor(order: AdminOrder) {
    switch (+order.order_status) {
        case 1:
            return 'green';
        default:
            return 'black';
    }
}

onMounted(async () => {
    try {
        orders.value = await getOrders();
    } catch (e: any) {
        error.value = e?.message || 'Failed to load orders';
    } finally {
        isLoading.value = false;
    }
});
</script>

<template>
    <div class="p-6">
        <h1 class="mb-4 text-2xl font-semibold">Orders</h1>

        <div v-if="isLoading" class="text-gray-500">Loading…</div>
        <div v-else-if="error" class="text-red-600">{{ error }}</div>
        <div v-else>
            <div v-if="orders.length === 0" class="text-gray-500">
                No orders yet.
            </div>
            <div v-else>
                <table class="divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-4 py-2 text-left text-sm font-semibold text-gray-700"
                            >
                                #
                            </th>
                            <th
                                class="px-4 py-2 text-left text-sm font-semibold text-gray-700"
                            >
                                Client
                            </th>
                            <th
                                class="px-4 py-2 text-left text-sm font-semibold text-gray-700"
                            >
                                Phone
                            </th>
                            <th
                                class="px-4 py-2 text-left text-sm font-semibold text-gray-700"
                            >
                                Address
                            </th>
                            <th
                                class="px-4 py-2 text-left text-sm font-semibold text-gray-700"
                            >
                                Status
                            </th>
                            <th
                                class="px-4 py-2 text-left text-sm font-semibold text-gray-700"
                            >
                                Amount
                            </th>
                            <th
                                class="px-4 py-2 text-left text-sm font-semibold text-gray-700"
                            >
                                Items
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="o in orders" :key="o.id" class="align-top">
                            <td class="px-4 py-2">{{ o.id }}</td>
                            <td class="px-4 py-2">{{ o.client_name }}</td>
                            <td class="px-4 py-2">{{ o.client_phone }}</td>
                            <td class="px-4 py-2">{{ o.client_address }}</td>
                            <td
                                class="px-4 py-2"
                                :style="{ color: getStatusColor(o) }"
                            >
                                {{ o.order_status_label }}
                            </td>
                            <td class="px-4 py-2">{{ o.amount ?? '-' }} $</td>
                            <td class="px-4 py-2">
                                <ul class="list-disc pl-5">
                                    <li
                                        style="white-space: nowrap"
                                        v-for="it in o.items || []"
                                        :key="`${o.id}-${it.product_id}`"
                                    >
                                        <span>{{
                                            getProductName(o, it.product_id)
                                        }}</span>
                                        <span class="text-gray-500">
                                            × {{ it.quantity }}</span
                                        >
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
