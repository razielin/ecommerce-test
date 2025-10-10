<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { getProducts, getCategories } from '@/api/api';
import { Category, Product } from '@/types/model';
import ProductCard from '@/components/shop/ProductCard.vue';
import ShoppingCart from '@/components/shop/ShoppingCart.vue';
import ShoppingCartIcon from '@/components/shop/ShoppingCartIcon.vue';
import { cart } from '@/store/cart';

const categories = ref([] as Category[]);
onMounted(async () => {
    categories.value = await getCategories();
});
const selectedCategory = ref('');

function addToCard(product: Product) {
    cart.commit('addToCart', product);
}

const showCart = ref(false);
function toggleShowCart() {
    showCart.value = !showCart.value;
}

const products = ref([] as Product[]);
onMounted(async () => {
    products.value = await getProducts();
});
const fromPrice = ref('');
const toPrice = ref('');
const productsFilteredByPrice = computed(() => {
    if (!fromPrice.value && !toPrice.value) {
        return products.value;
    }
    let res = products.value.filter(p => +p.price >= +fromPrice.value);
    res = res.filter(p => +p.price <= +toPrice.value);
    return res;
});
const productsFilteredByPriceAndCategory = computed(() => {
    if (!selectedCategory.value) {
        return productsFilteredByPrice.value;
    }
    return productsFilteredByPrice.value.filter(p => +p.category_id === +selectedCategory.value);
})
</script>

<template>
    <div>
        <ShoppingCartIcon @click="toggleShowCart"/>
        <b>Price:</b>
        <input type="number" v-model="fromPrice" class="priceFilter">
        -
        <input type="number" v-model="toPrice" class="priceFilter">
        <b class="categorySelectTitle">Category</b>
        <select v-model="selectedCategory" class="categorySelect">
            <option value="">All</option>
            <option v-for="category in categories" :key="category.id"
                    :value="category.id"
            >{{category.name}}</option>
        </select>
        <h2 v-if="products.length === 0">No products added</h2>
        <div class="flex xs:flex-col xs:items-center rounded-2xl p-7">
            <ProductCard
                v-for="product in productsFilteredByPriceAndCategory"
                :key="product.id"
                :product="product"
                @addedToCart="addToCard"
            />
            <ShoppingCart v-if="showCart" @close="showCart = false"/>
        </div>
    </div>
</template>

<style scoped>
.priceFilter,.categorySelect {
    border-style: solid;
    border-width: 1px;
    border-color: gray;
}
.categorySelectTitle {
    margin-left: 30px;
}
</style>
