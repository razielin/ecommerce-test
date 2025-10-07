<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { getProducts } from '@/api/api';
import { Product } from '@/types/model';
import ProductCard from '@/components/shop/ProductCard.vue';

const products = ref([] as Product[]);

onMounted(async () => {
    products.value = await getProducts();
});
function addToCard(product: Product) {
    console.log(product);
}

</script>

<template>
    <div>
        <div class="flex flex-col items-center rounded-2xl p-7">
            <ProductCard
                v-for="product in products"
                :key="product.id"
                :product="product"
                @addedToCart="addToCard"
            />
        </div>
    </div>
</template>

<style scoped></style>
