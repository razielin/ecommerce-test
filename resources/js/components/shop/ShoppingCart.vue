<script setup lang="ts">
import { computed } from 'vue';
import { cart } from '@/store/cart';
import { Product } from '@/types/model';

const emit = defineEmits<{
    close: [void]
}>()

const products: Product[] = computed(() => cart.state.products);
const totalPrice = computed(() => cart.getters.getTotalPrice);
function getProductsQty(product: Product) {
    return cart.getters.getProductsQtyInCart(product.id);
}
function incrementQty(product: Product) {
    cart.commit('incrementProductQty', product);
}
function decrementQty(product: Product) {
    cart.commit('decrementProductQty', product);
}
function removeFromCart(product: Product) {
    cart.commit('removeFromCart', product);
}
function getSubTotal(product: Product) {
    return cart.getters.getSubTotal(product);
}
</script>

<template>
    <div>
        <div id="modal">
            <div
                class="fixed inset-0 p-4 flex flex-wrap justify-center items-center w-full h-full z-[1000] before:fixed before:inset-0 before:w-full before:h-full before:bg-[rgba(0,0,0,0.5)] overflow-auto">
                <div class="w-full max-w-2xl bg-white shadow-lg rounded-lg p-6 relative">
                    <div class="flex items-center pb-3 border-b border-gray-300">
                        <h3 class="text-slate-900 text-xl font-semibold flex-1">Shopping Cart</h3>
                        <svg @click="emit('close')" id="closeIcon" xmlns="http://www.w3.org/2000/svg"
                             class="w-3.5 h-3.5 ml-2 cursor-pointer shrink-0 fill-gray-400 hover:fill-red-500"
                             viewBox="0 0 320.591 320.591">
                            <path
                                d="M30.391 318.583a30.37 30.37 0 0 1-21.56-7.288c-11.774-11.844-11.774-30.973 0-42.817L266.643 10.665c12.246-11.459 31.462-10.822 42.921 1.424 10.362 11.074 10.966 28.095 1.414 39.875L51.647 311.295a30.366 30.366 0 0 1-21.256 7.288z"
                                data-original="#000000"></path>
                            <path
                                d="M287.9 318.583a30.37 30.37 0 0 1-21.257-8.806L8.83 51.963C-2.078 39.225-.595 20.055 12.143 9.146c11.369-9.736 28.136-9.736 39.504 0l259.331 257.813c12.243 11.462 12.876 30.679 1.414 42.922-.456.487-.927.958-1.414 1.414a30.368 30.368 0 0 1-23.078 7.288z"
                                data-original="#000000"></path>
                        </svg>
                    </div>

                    <div class="my-6">
                        <div class="bg-white divide-y divide-gray-300 px-4">
                            <div v-for="product in products" :key="+product.id" class="grid md:grid-cols-4 items-center md:gap-4 gap-6 py-4">
                                <div class="col-span-2 flex items-center gap-6">
                                    <div class="w-20 h-20 shrink-0">
                                        <img :src='product.image' class="w-full h-full object-contain" />
                                    </div>
                                    <div>
                                        <h3 class="text-[15px] font-semibold text-slate-900">
                                            {{product.name}}
                                        </h3>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <button @click="decrementQty(product)" type="button"
                                            class="flex items-center justify-center w-5 h-5 bg-gray-200 outline-none rounded-sm cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-2 fill-black" viewBox="0 0 124 124">
                                            <path d="M112 50H12C5.4 50 0 55.4 0 62s5.4 12 12 12h100c6.6 0 12-5.4 12-12s-5.4-12-12-12z" data-original="#000000"></path>
                                        </svg>
                                    </button>
                                    <span class="font-semibold text-[15px] leading-[18px]">{{getProductsQty(product)}}</span>
                                    <button @click="incrementQty(product)" type="button"
                                            class="flex items-center justify-center w-5 h-5 bg-gray-200 outline-none rounded-sm cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-2 fill-black" viewBox="0 0 42 42">
                                            <path d="M37.059 16H26V4.941C26 2.224 23.718 0 21 0s-5 2.224-5 4.941V16H4.941C2.224 16 0 18.282 0 21s2.224 5 4.941 5H16v11.059C16 39.776 18.282 42 21 42s5-2.224 5-4.941V26h11.059C39.776 26 42 23.718 42 21s-2.224-5-4.941-5z" data-original="#000000"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="flex items-center">
                                    <h4 class="text-[15px] font-semibold text-slate-900">{{getSubTotal(product)}}$</h4>
                                    <svg @click="removeFromCart" xmlns="http://www.w3.org/2000/svg" class="w-3 cursor-pointer shrink-0 fill-red-500 ml-auto" viewBox="0 0 320.591 320.591">
                                        <path d="M30.391 318.583a30.37 30.37 0 0 1-21.56-7.288c-11.774-11.844-11.774-30.973 0-42.817L266.643 10.665c12.246-11.459 31.462-10.822 42.921 1.424 10.362 11.074 10.966 28.095 1.414 39.875L51.647 311.295a30.366 30.366 0 0 1-21.256 7.288z" data-original="#000000"></path>
                                        <path d="M287.9 318.583a30.37 30.37 0 0 1-21.257-8.806L8.83 51.963C-2.078 39.225-.595 20.055 12.143 9.146c11.369-9.736 28.136-9.736 39.504 0l259.331 257.813c12.243 11.462 12.876 30.679 1.414 42.922-.456.487-.927.958-1.414 1.414a30.368 30.368 0 0 1-23.078 7.288z" data-original="#000000"></path>
                                    </svg>
                                </div>
                            </div>

                        </div>

                        <div class="bg-gray-100 rounded-sm p-6 mt-8">
                            <ul class="text-slate-500 font-medium divide-y divide-gray-300">
                                <li class="flex flex-wrap gap-4 text-sm pt-4 font-semibold text-slate-900">Total <span class="ml-auto">
                                    {{totalPrice}}$
                                </span></li>
                            </ul>
                        </div>
                    </div>

                    <div class="border-t border-gray-300 pt-6 flex justify-end gap-4">
                        <button @click="emit('close')" id="closeButton" type="button"
                                class="px-4 py-2.5 rounded-md text-slate-900 text-sm font-medium border-none outline-none tracking-wide bg-gray-200 hover:bg-gray-300 active:bg-gray-200 cursor-pointer">
                            Continue Shopping
                        </button>
                        <button type="button"
                                :disabled="products.length === 0"
                                class="px-4 py-2.5 rounded-md text-white text-sm font-medium border-none outline-none tracking-wide bg-blue-600 hover:bg-blue-700 active:bg-blue-600 cursor-pointer">
                            Proceed to checkout
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <button id="openModal" type="button"
                class="mt-4 mx-auto block px-4 py-2.5 rounded-md text-white text-sm font-medium border-none outline-none tracking-wide bg-blue-600 hover:bg-blue-700 active:bg-blue-600">Open
            Shopping Cart</button>
    </div>
</template>

<style scoped>

</style>
