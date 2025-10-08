import { Category, Order } from '@/types/model';

const API_URL = 'http://localhost:8000';

export async function getProducts(): Promise<Order[]> {
    return await fetchFromApi('products');
}
export async function getCategories(): Promise<Category[]> {
    return await fetchFromApi('categories');
}

async function fetchFromApi(path: string) {
    let res =  await fetch(API_URL+ '/api/' + path);
    res = await res.json();
    if (res.status === 'success') {
        return res.data;
    } else {
        throw new Error(res.error_message);
    }
}
