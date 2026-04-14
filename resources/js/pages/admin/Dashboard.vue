<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Package, ShoppingBag, Tag, TrendingUp } from 'lucide-vue-next';

interface Order {
    id: number;
    customer_name: string;
    customer_phone: string;
    total_amount: number;
    status: string;
    created_at: string;
}

defineProps<{
    stats: {
        total_orders: number;
        pending_orders: number;
        total_revenue: number;
        total_products: number;
        total_categories: number;
    };
    recent_orders: Order[];
}>();

const statusLabel: Record<string, string> = {
    pending:   'Ожидает',
    confirmed: 'Подтверждён',
    shipped:   'Отправлен',
    delivered: 'Доставлен',
    cancelled: 'Отменён',
};

const statusClass: Record<string, string> = {
    pending:   'bg-yellow-100 text-yellow-700',
    confirmed: 'bg-blue-100 text-blue-700',
    shipped:   'bg-purple-100 text-purple-700',
    delivered: 'bg-green-100 text-green-700',
    cancelled: 'bg-red-100 text-red-700',
};
</script>

<template>
    <AppLayout>
        <Head title="Главная" />

        <div class="px-3 py-6 sm:px-6">
            <h1 class="mb-6 text-2xl font-bold text-gray-800">Главная</h1>

            <!-- Stats -->
            <div class="mb-8 grid grid-cols-2 gap-4 md:grid-cols-4">
                <div class="rounded-xl bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="rounded-full bg-pink-100 p-2"><ShoppingBag class="h-5 w-5 text-pink-600" /></div>
                        <div>
                            <p class="text-sm text-gray-400">Всего заказов</p>
                            <p class="text-2xl font-bold text-gray-800">{{ stats.total_orders }}</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="rounded-full bg-yellow-100 p-2"><ShoppingBag class="h-5 w-5 text-yellow-600" /></div>
                        <div>
                            <p class="text-sm text-gray-400">Ожидают</p>
                            <p class="text-2xl font-bold text-gray-800">{{ stats.pending_orders }}</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="rounded-full bg-green-100 p-2"><TrendingUp class="h-5 w-5 text-green-600" /></div>
                        <div>
                            <p class="text-sm text-gray-400">Выручка</p>
                            <p class="text-2xl font-bold text-gray-800">{{ Number(stats.total_revenue).toLocaleString() }} сом</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="rounded-full bg-purple-100 p-2"><Package class="h-5 w-5 text-purple-600" /></div>
                        <div>
                            <p class="text-sm text-gray-400">Товаров</p>
                            <p class="text-2xl font-bold text-gray-800">{{ stats.total_products }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent orders -->
            <div class="rounded-xl bg-white shadow-sm">
                <div class="flex items-center justify-between border-b px-6 py-4">
                    <h2 class="font-semibold text-gray-700">Последние заказы</h2>
                    <Link :href="route('admin.orders.index')" class="text-sm text-pink-500 hover:underline">Все заказы →</Link>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b bg-gray-50 text-xs text-gray-400 uppercase">
                            <tr>
                                <th class="px-6 py-3 text-left">#</th>
                                <th class="px-6 py-3 text-left">Клиент</th>
                                <th class="hidden sm:table-cell px-6 py-3 text-left">Телефон</th>
                                <th class="hidden sm:table-cell px-6 py-3 text-left">Сумма</th>
                                <th class="px-6 py-3 text-left">Статус</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <tr v-for="order in recent_orders" :key="order.id" class="hover:bg-gray-50">
                                <td class="px-6 py-3">
                                    <Link :href="route('admin.orders.show', order.id)" class="font-medium text-pink-500 hover:underline">
                                        #{{ order.id }}
                                    </Link>
                                </td>
                                <td class="px-6 py-3">{{ order.customer_name }}</td>
                                <td class="hidden sm:table-cell px-6 py-3">{{ order.customer_phone }}</td>
                                <td class="hidden sm:table-cell px-6 py-3 font-medium">{{ Number(order.total_amount).toLocaleString() }} сом</td>
                                <td class="px-6 py-3">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-medium" :class="statusClass[order.status]">
                                        {{ statusLabel[order.status] }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>