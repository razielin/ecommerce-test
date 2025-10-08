<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { getProducts } from '@/api/api';
import { Product } from '@/types/model';
import ProductCard from '@/components/shop/ProductCard.vue';
import ShoppingCart from '@/components/shop/ShoppingCart.vue';
import ShoppingCartIcon from '@/components/shop/ShoppingCartIcon.vue';
import { cart } from '@/store/cart';

const products = ref([] as Product[]);

onMounted(async () => {
    products.value = await getProducts();
});
function addToCard(product: Product) {
    cart.commit('addToCart', product);
}

const showCart = ref(false);
function toggleShowCart() {
    showCart.value = !showCart.value;
}

const fromPrice = ref('');
const toPrice = ref('');
const filteredProducts = computed(() => {
    if (!fromPrice.value && !toPrice.value) {
        return products.value;
    }
    let res = products.value.filter(p => +p.price > +fromPrice.value);
    res = res.filter(p => +p.price < +toPrice.value);
    return res;
});
</script>

<template>
    <div>
        <ShoppingCartIcon @click="toggleShowCart"/>
        <b>Price:</b>
        <input type="number" v-model="fromPrice" class="priceFilter">
        -
        <input type="number" v-model="toPrice" class="priceFilter">
        <div class="flex xs:flex-col xs:items-center rounded-2xl p-7">
            <ProductCard
                v-for="product in filteredProducts"
                :key="product.id"
                :product="product"
                @addedToCart="addToCard"
            />
            <ShoppingCart v-if="showCart" @close="showCart = false"/>
        </div>
    </div>
</template>

<style scoped>
.priceFilter {
    border-style: solid;
    border-width: 1px;
    border-color: gray;
}
</style>
