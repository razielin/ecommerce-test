import { Order } from '@/types/model';
const API_URL = 'http://localhost:8000';

export async function getOrders(): Promise<Order[]> {
    return await getFromApi('orders');
}

async function getFromApi(path: string) {
    let res =  await fetch(API_URL+ '/admin/' + path);
    res = await res.json();
    if (res.status === 'success') {
        return res.data;
    } else {
        throw new Error(res.error_message);
    }
}
