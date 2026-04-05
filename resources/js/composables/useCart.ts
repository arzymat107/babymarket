import { computed, ref } from 'vue';

export interface CartItem {
    id: number;
    name: string;
    price: number;
    image_url: string | null;
    quantity: number;
}

const STORAGE_KEY = 'babymarket_cart';

function loadFromStorage(): CartItem[] {
    try {
        const raw = localStorage.getItem(STORAGE_KEY);
        return raw ? JSON.parse(raw) : [];
    } catch {
        return [];
    }
}

const items = ref<CartItem[]>(loadFromStorage());

function save() {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(items.value));
}

export function useCart() {
    const count = computed(() => items.value.reduce((sum, i) => sum + i.quantity, 0));

    const total = computed(() => items.value.reduce((sum, i) => sum + i.price * i.quantity, 0));

    function add(product: Omit<CartItem, 'quantity'>, quantity = 1) {
        const existing = items.value.find((i) => i.id === product.id);
        if (existing) {
            existing.quantity += quantity;
        } else {
            items.value.push({ ...product, quantity });
        }
        save();
    }

    function remove(id: number) {
        items.value = items.value.filter((i) => i.id !== id);
        save();
    }

    function updateQuantity(id: number, quantity: number) {
        const item = items.value.find((i) => i.id === id);
        if (item) {
            item.quantity = quantity;
            if (item.quantity <= 0) remove(id);
            else save();
        }
    }

    function clear() {
        items.value = [];
        save();
    }

    return { items, count, total, add, remove, updateQuantity, clear };
}