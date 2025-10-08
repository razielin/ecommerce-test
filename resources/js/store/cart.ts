import { createStore } from 'vuex'
import { Product } from '@/types/model';

type ProductId = number

export const cart = createStore({
    state() {
        return {
            products: new Array<Product>,
            productsQtyInCart: new Map<ProductId, number>()
        }
    },
    mutations: {
        addToCart(state, product: Product) {
            if (!state.products.includes(product)) {
                state.products.push(product);
            }
            const currentInCart = getProductCountInCart(
                state.productsQtyInCart, product.id
            );
            state.productsQtyInCart.set(product.id, currentInCart + 1);
        },
        removeFromCart(state, product: Product) {
            state.products = state.products.filter(p => +p.id === +product.id);
            state.productsQtyInCart.set(product.id, 0);
        },
        incrementProductQty(state, product: Product) {
            const productQty = getProductCountInCart(state.productsQtyInCart, product.id);
            state.productsQtyInCart.set(product.id, productQty + 1);
        },
        decrementProductQty(state, product: Product) {
            const productQty = getProductCountInCart(state.productsQtyInCart, product.id);
            if (productQty <= 1) {
                state.productsQtyInCart.set(product.id, 0);
            } else {
                state.productsQtyInCart.set(product.id, productQty - 1);
            }
        }
    },
    getters: {
        getProductsQtyInCart: (state) => (id) => {
            return getProductCountInCart(state.productsQtyInCart, id);
        },
        getTotalPrice(state, getters) {
            let total = 0;
            for (const product of state.products) {
                const subtotal = getters.getSubTotal(product);
                total += subtotal;
            }
            return roundPrice(total);
        },
        productsInCartCount(state) {
            return state.products.length;
        },
        getSubTotal: (_, getters) => (product: Product) => {
            const qty = getters.getProductsQtyInCart(product.id);
            const subtotal = product.price * qty;
            return roundPrice(subtotal);
        },
    }
});

function getProductCountInCart(productsCountInCart: Map<ProductId, number>, id: number) {
    return productsCountInCart.get(+id) || 0;
}
function roundPrice(price: number) {
    return +price.toFixed(2);
}
