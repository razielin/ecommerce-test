import { Category, NewOrderData, Order, Product } from '@/types/model';
import { getApi, postApi } from '@/api/utils';

export async function getProducts(): Promise<Product[]> {
    return await getApi('/api/products');
}
export async function getCategories(): Promise<Category[]> {
    return await getApi('/api/categories');
}

export async function postNewOrder(order: NewOrderData): Promise<Order> {
    return await postApi('/api/order', order);
}
