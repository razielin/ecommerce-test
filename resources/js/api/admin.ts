import { Order, Product } from '@/types/model';
import { getApi, postApi } from '@/api/utils';

export async function getOrders(): Promise<Order[]> {
    return await getApi('/admin/orders');
}

export async function editProduct(product: Product): Promise<Product> {
    return await postApi('/admin/product/edit', product);
}

export async function addProduct(product: Partial<Product>): Promise<Product> {
    return await postApi('/admin/product/new', product);
}

export async function deleteProduct(id: number): Promise<{ message: string }> {
    return await postApi(`/admin/product/delete/${id}`, {});
}
