<script setup lang="ts">
import Label from "@/components/ui/label/Label.vue"
import Input from "@/components/ui/input/Input.vue"
import { cart } from '@/store/cart';
import { NewOrderData } from '@/types/model';
import { reactive, ref } from 'vue';
import { postNewOrder } from '@/api/api';
import OrderPlacedConfirm from '@/components/shop/OrderPlacedConfirm.vue';

const emit = defineEmits<{
    close: [void]
}>()

const orderData: NewOrderData = reactive({
    client_name: '',
    client_phone: '',
    client_address: '',
    comment: '',
    order_items: []
});
const placedOrder = ref(null);

async function placeOrder() {
    if (!orderData.client_name) {
        alert("Please fill the name field");
        return;
    }
    if (!orderData.client_phone) {
        alert("Please fill the phone field");
        return;
    }
    if (!orderData.client_address) {
        alert("Please fill the address field");
        return;
    }
    orderData.order_items = cart.state.products.map((p) => {
        return {product_id: p.id, quantity: cart.getters.getProductsQtyInCart(p.id)}
    });
    const newOrder = await postNewOrder(orderData);
    console.log(newOrder);
    placedOrder.value = newOrder;
    cart.commit('resetCart');
}
function close() {
    placedOrder.value = null;
    emit('close')
}

</script>

<template>
    <div class="fixed inset-0 p-4 flex flex-wrap justify-center items-center w-full h-full z-[1000] before:fixed before:inset-0 before:w-full before:h-full before:bg-[rgba(0,0,0,0.5)] overflow-auto">
        <div v-if="!placedOrder" class="w-full max-w-2xl bg-white shadow-lg rounded-lg p-6 relative">
            <h1 style="text-align: center">Order details</h1>
            <Label>Client Name</Label>
            <Input  v-model="orderData.client_name"/>
            <Label>Phone</Label>
            <Input  v-model="orderData.client_phone"/>
            <Label>Address</Label>
            <textarea v-model="orderData.client_address" cols="60" rows="5"></textarea>
            <Label>Comment</Label>
            <textarea v-model="orderData.comment" name="" id="" cols="60" rows="5"></textarea>
            <button type="button"
                    @click="placeOrder"
                    class="px-4 py-2.5 rounded-md text-white text-sm font-medium border-none outline-none tracking-wide bg-blue-600 hover:bg-blue-700 active:bg-blue-600 cursor-pointer">
                Place order
            </button>
            <button type="button"
                    @click="close"
                    style="margin-left: 15px">
                Cancel
            </button>
        </div>
        <OrderPlacedConfirm
            class="w-full max-w-2xl bg-white shadow-lg rounded-lg p-6 relative"
            :order="placedOrder"
            v-if="placedOrder"
            @close="close"
        />
    </div>
</template>

<style scoped>
    textarea {
        border-style: solid;
        border-width: 1px;
        border-color: gray;
    }
</style>
