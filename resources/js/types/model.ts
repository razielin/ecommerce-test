export type Product = {
    id: number
    name: string
    price: number
    description: string
    image: string
    category_id: number
}

export type Category = {
    id: number
    name: string
}

export type OrderItem = {
    product_id: number
    quantity: number
}

export type NewOrderData = {
    client_name: string
    client_phone: string
    client_address: string
    comment: string
    order_items: OrderItem[]
}

export type Order = {
    id: number
    client_name: string
    client_phone: string
    client_address: string
    comment: string
    amount: number
}

export type AdminOrder = {
    items: OrderItem[]
    products: Product[]
    order_status: number
    order_status_label: string
} & Order
