<script setup lang="ts">
import { Order } from '@/types/model';

const props = defineProps<{
    order: Order
}>()
const order = props.order;
const emit = defineEmits<{
  close: [void]
}>()
</script>

<template>
    <div class="w-full max-w-xl mx-auto bg-white rounded-lg shadow p-6 text-center">
        <h2 class="text-2xl font-semibold mb-2">Спасибо за заказ!</h2>
        <p class="text-gray-600 mb-6">Мы приняли ваш заказ и скоро свяжемся с вами для подтверждения.</p>

        <div class="grid grid-cols-1 gap-2 text-left bg-gray-50 rounded p-4 mb-6">
            <div class="flex justify-between">
                <span class="text-gray-500">Номер заказа:</span>
                <span class="font-medium">#{{ order.id }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Имя клиента:</span>
                <span class="font-medium">{{ order.client_name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Телефон:</span>
                <span class="font-medium">{{ order.client_phone }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Адрес доставки:</span>
                <span class="font-medium">{{ order.client_address }}</span>
            </div>
            <div class="flex justify-between" v-if="order.amount !== undefined && order.amount !== null">
                <span class="text-gray-500">Сумма заказа:</span>
                <span class="font-medium">{{ order.amount }}</span>
            </div>
        </div>

        <p class="text-gray-500">Письмо с деталями заказа отправлено на контактные данные, указанные при оформлении.</p>

        <button
          type="button"
          class="mt-6 inline-flex items-center justify-center rounded-md bg-black px-4 py-2 text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black"
          @click="emit('close')"
        >
          ОК
        </button>
    </div>
</template>

<style scoped>

</style>
