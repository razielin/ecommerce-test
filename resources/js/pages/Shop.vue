<script setup lang="ts">
import { ref, onMounted } from 'vue';
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

</script>

<template>
    <div>
        <ShoppingCartIcon @click="toggleShowCart"/>
        <div class="flex xs:flex-col xs:items-center rounded-2xl p-7">
            <ProductCard
                v-for="product in products"
                :key="product.id"
                :product="product"
                @addedToCart="addToCard"
            />
            <ShoppingCart v-if="showCart" @close="showCart = false"/>
        </div>
    </div>
</template>

<style scoped></style>
