<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';
import { Product, Category } from '@/types/model';
import { editProduct } from '@/api/admin';
import { getCategories } from '@/api/api';

const props = defineProps<{
    product: Product | null;
    isOpen: boolean;
}>();

const emit = defineEmits<{
    close: [void];
    save: [product: Product];
}>();

const formData = ref({
    name: '',
    price: '',
    description: '',
    image: '',
    category_id: 1
});

const isLoading = ref(false);
const categories = ref<Category[]>([]);
const categoriesLoading = ref(false);

watch(() => props.product, (newProduct) => {
    if (newProduct) {
        formData.value = {
            name: newProduct.name,
            price: newProduct.price.toString(),
            description: newProduct.description,
            image: newProduct.image,
            category_id: newProduct.category_id
        };
    }
});

onMounted(async () => {
    categoriesLoading.value = true;
    try {
        categories.value = await getCategories();
    } catch (error) {
        console.error('Failed to load categories:', error);
    } finally {
        categoriesLoading.value = false;
    }
});

async function handleSave() {
    if (!props.product) return;

    isLoading.value = true;

    try {
        const updatedProduct: Product = {
            ...props.product,
            name: formData.value.name,
            price: parseFloat(formData.value.price),
            description: formData.value.description,
            image: formData.value.image,
            category_id: formData.value.category_id
        };

        const savedProduct = await editProduct(updatedProduct);
        emit('save', savedProduct);
        emit('close');
    } catch (error) {
        console.error('Failed to save product:', error);
        alert('Failed to save product. Please try again.');
    } finally {
        isLoading.value = false;
    }
}

function handleClose() {
    emit('close');
}
</script>

<template>
    <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <h2 class="text-xl font-semibold mb-4">Edit Product</h2>

            <form @submit.prevent="handleSave" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input
                        v-model="formData.name"
                        type="text"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                    <input
                        v-model="formData.price"
                        type="number"
                        step="0.01"
                        min="0"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea
                        v-model="formData.description"
                        rows="3"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image URL</label>
                    <input
                        v-model="formData.image"
                        type="url"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select
                        v-model.number="formData.category_id"
                        required
                        :disabled="categoriesLoading"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="" disabled>Select a category</option>
                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                    <div v-if="categoriesLoading" class="text-sm text-gray-500 mt-1">Loading categories...</div>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button
                        type="button"
                        @click="handleClose"
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        :disabled="isLoading"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ isLoading ? 'Saving...' : 'Save' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>

</style>
