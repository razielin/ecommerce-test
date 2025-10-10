<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { getProducts } from '@/api/api';
import { deleteProduct } from '@/api/admin';
import { Product } from '@/types/model';
import AppLayout from '@/layouts/AppLayout.vue';
import EditProductDialog from '@/components/admin/EditProductDialog.vue';

const products = ref<Product[]>([]);
const isLoading = ref(true);
const error = ref<string | null>(null);
const selectedProduct = ref<Product | null>(null);
const isEditDialogOpen = ref(false);
const isCreateDialogOpen = ref(false);

onMounted(async () => {
    try {
        products.value = await getProducts();
    } catch (e: any) {
        error.value = e?.message || 'Failed to load products';
    } finally {
        isLoading.value = false;
    }
});

function editProduct(product: Product) {
    selectedProduct.value = product;
    isEditDialogOpen.value = true;
}

function createProduct() {
    selectedProduct.value = null;
    isCreateDialogOpen.value = true;
}

function handleSaveProduct(updatedProduct: Product) {
    const index = products.value.findIndex(p => p.id === updatedProduct.id);
    if (index !== -1) {
        // Update existing product
        products.value[index] = updatedProduct;
    } else {
        // Add new product
        products.value.push(updatedProduct);
    }
}

function handleCloseDialog() {
    isEditDialogOpen.value = false;
    isCreateDialogOpen.value = false;
    selectedProduct.value = null;
}

async function handleDeleteProduct(product: Product) {
    if (confirm(`Are you sure you want to delete "${product.name}"?`)) {
        try {
            await deleteProduct(product.id);
            // Remove product from local array
            const index = products.value.findIndex(p => p.id === product.id);
            if (index !== -1) {
                products.value.splice(index, 1);
            }
        } catch (error) {
            console.error('Failed to delete product:', error);
            alert('Failed to delete product. Please try again.');
        }
    }
}

</script>

<template>
    <AppLayout>
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold">Products</h1>
                <button
                    @click="createProduct"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors"
                >
                    Add Product
                </button>
            </div>

            <div v-if="isLoading" class="text-gray-500">Loading products...</div>
            <div v-else-if="error" class="text-red-600">{{ error }}</div>
            <div v-else class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div
                    v-for="product in products"
                    :key="product.id"
                    class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow"
                >
                    <div class="bg-gray-100">
                        <img
                            :src="product.image"
                            :alt="product.name"
                            style="height: 150px; margin: 0 auto;"
                            class="object-cover"
                        />
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2">{{ product.name }}</h3>
                        <p class="text-gray-600 text-sm mb-3">{{ product.description }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold">${{ product.price }}</span>
                            <div class="flex gap-2">
                                <button
                                    @click="editProduct(product)"
                                    class="bg-gray-600 text-white px-3 py-1 rounded-md hover:bg-gray-700 transition-colors text-sm"
                                >
                                    Edit
                                </button>
                                <button
                                    @click="handleDeleteProduct(product)"
                                    class="bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700 transition-colors text-sm"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <EditProductDialog
            :product="selectedProduct"
            :is-open="isEditDialogOpen"
            @close="handleCloseDialog"
            @save="handleSaveProduct"
        />

        <EditProductDialog
            :product="null"
            :is-open="isCreateDialogOpen"
            :is-create-mode="true"
            v-if="isCreateDialogOpen"
            @close="handleCloseDialog"
            @save="handleSaveProduct"
        />
    </AppLayout>
</template>

<style scoped>

</style>
