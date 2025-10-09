import { Category, NewOrderData, Order, Product } from '@/types/model';

const API_URL = 'http://localhost:8000';

export async function getProducts(): Promise<Product[]> {
    return await getFromApi('products');
}
export async function getCategories(): Promise<Category[]> {
    return await getFromApi('categories');
}

export async function postNewOrder(order: NewOrderData): Promise<Order> {
    return await postFromApi('order', order);
}

async function getFromApi(path: string) {
    let res =  await fetch(API_URL+ '/api/' + path);
    res = await res.json();
    if (res.status === 'success') {
        return res.data;
    } else {
        throw new Error(res.error_message);
    }
}

async function postFromApi(path: string, data: any) {
    let res = await fetch(API_URL+ '/api/' + path, {
        method: 'POST', // Specify the HTTP method as POST
        headers: {
            'Content-Type': 'application/json', // Indicate the type of content in the body
        },
        body: JSON.stringify(data), // Convert the JavaScript object to a JSON string for the body
    });
    res = await res.json();
    if (res.status === 'success') {
        return res.data;
    } else {
        throw new Error(res.error_message);
    }
}
