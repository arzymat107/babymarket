<script setup lang="ts">
import StoreLayout from '@/layouts/StoreLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

interface OrderItem {
    id: number;
    quantity: number;
    unit_price: number;
    subtotal: number;
    product: { id: number; name: string; image_url: string | null };
}

interface Order {
    id: number;
    customer_name: string;
    customer_phone: string;
    shipping_address: string;
    shipping_cost: number;
    total_amount: number;
    status: string;
    items: OrderItem[];
}

defineProps<{ order: Order }>();
</script>

<template>
    <StoreLayout>
        <Head title="Заказ оформлен" />

        <div class="mx-auto max-w-2xl px-4 py-16 text-center">
            <div class="mb-4 text-7xl">🎉</div>
            <h1 class="text-2xl font-bold text-gray-800">Заказ #{{ order.id }} оформлен!</h1>
            <p class="mt-2 text-gray-500">Мы свяжемся с вами по номеру {{ order.customer_phone }} для подтверждения.</p>

            <div class="mt-8 rounded-xl bg-white p-6 shadow-sm text-left">
                <h2 class="mb-4 font-semibold text-gray-700">Состав заказа</h2>
                <div v-for="item in order.items" :key="item.id" class="mb-3 flex items-center gap-3">
                    <div class="h-12 w-12 flex-shrink-0 rounded-lg bg-gray-100 flex items-center justify-center text-xl">
                        <img
                            v-if="item.product.image_url"
                            :src="item.product.image_url"
                            :alt="item.product.name"
                            class="h-full w-full rounded-lg object-cover"
                        />
                        <span v-else>🧸</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">{{ item.product.name }}</p>
                        <p class="text-xs text-gray-400">{{ item.quantity }} × {{ Number(item.unit_price).toLocaleString() }} сом</p>
                    </div>
                    <span class="font-semibold text-gray-800">{{ Number(item.subtotal).toLocaleString() }} сом</span>
                </div>

                <div class="mt-4 border-t pt-4 space-y-1 text-sm">
                    <div class="flex justify-between text-gray-500">
                        <span>Доставка</span>
                        <span>{{ Number(order.shipping_cost).toLocaleString() }} сом</span>
                    </div>
                    <div class="flex justify-between font-bold text-base">
                        <span>Итого</span>
                        <span class="text-pink-600">{{ Number(order.total_amount).toLocaleString() }} сом</span>
                    </div>
                </div>

                <div class="mt-4 border-t pt-4 text-sm text-gray-500">
                    <p>Адрес доставки: <span class="text-gray-800">{{ order.shipping_address }}</span></p>
                </div>
            </div>

            <Link :href="route('home')" class="mt-6 inline-block rounded-full bg-pink-500 px-8 py-3 font-semibold text-white hover:bg-pink-600">
                Вернуться на главную
            </Link>
        </div>
    </StoreLayout>
</template>