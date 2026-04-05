<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

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
    paid: boolean;
    status: string;
    created_at: string;
    items: OrderItem[];
}

const props = defineProps<{ order: Order }>();

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

const statuses = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];

const statusForm = useForm({ status: props.order.status });
const paidForm   = useForm({});

function updateStatus() {
    statusForm.patch(route('admin.orders.status', props.order.id));
}

function togglePaid() {
    paidForm.patch(route('admin.orders.paid', props.order.id));
}
</script>

<template>
    <AppLayout>
        <Head :title="`Заказ #${order.id}`" />

        <div class="p-6">
            <div class="mb-6 flex items-center gap-4">
                <Link :href="route('admin.orders.index')" class="text-sm text-gray-400 hover:text-pink-500">← Назад</Link>
                <h1 class="text-2xl font-bold text-gray-800">Заказ #{{ order.id }}</h1>
                <span class="rounded-full px-3 py-1 text-sm font-medium" :class="statusClass[order.status]">
                    {{ statusLabel[order.status] }}
                </span>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <!-- Items -->
                <div class="md:col-span-2 rounded-xl bg-white p-6 shadow-sm">
                    <h2 class="mb-4 font-semibold text-gray-700">Состав заказа</h2>
                    <div v-for="item in order.items" :key="item.id" class="mb-4 flex items-center gap-3">
                        <div class="h-12 w-12 flex-shrink-0 rounded-lg bg-gray-100 flex items-center justify-center text-lg overflow-hidden">
                            <img v-if="item.product.image_url" :src="item.product.image_url" class="h-full w-full object-cover" />
                            <span v-else>🧸</span>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800 text-sm">{{ item.product.name }}</p>
                            <p class="text-xs text-gray-400">{{ item.quantity }} × {{ Number(item.unit_price).toLocaleString() }} сом</p>
                        </div>
                        <span class="font-semibold text-sm">{{ Number(item.subtotal).toLocaleString() }} сом</span>
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
                </div>

                <!-- Sidebar -->
                <div class="space-y-4">
                    <!-- Customer -->
                    <div class="rounded-xl bg-white p-5 shadow-sm">
                        <h2 class="mb-3 font-semibold text-gray-700">Клиент</h2>
                        <p class="text-sm font-medium text-gray-800">{{ order.customer_name }}</p>
                        <p class="text-sm text-gray-500">{{ order.customer_phone }}</p>
                        <p class="mt-2 text-sm text-gray-500">{{ order.shipping_address }}</p>
                        <div class="mt-3">
                            <span
                                class="rounded-full px-2.5 py-1 text-xs font-medium"
                                :class="order.paid ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
                            >
                                {{ order.paid ? 'Оплачен' : 'Не оплачен' }}
                            </span>
                        </div>
                    </div>

                    <!-- Paid toggle -->
                    <div class="rounded-xl bg-white p-5 shadow-sm">
                        <h2 class="mb-3 font-semibold text-gray-700">Оплата</h2>
                        <div class="mb-3 flex items-center justify-between">
                            <span class="text-sm text-gray-600">Статус оплаты</span>
                            <span
                                class="rounded-full px-2.5 py-1 text-xs font-medium"
                                :class="order.paid ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
                            >
                                {{ order.paid ? 'Оплачен' : 'Не оплачен' }}
                            </span>
                        </div>
                        <button
                            :disabled="paidForm.processing"
                            class="w-full rounded-lg py-2 text-sm font-semibold text-white disabled:opacity-50 transition"
                            :class="order.paid ? 'bg-gray-400 hover:bg-gray-500' : 'bg-green-500 hover:bg-green-600'"
                            @click="togglePaid"
                        >
                            {{ order.paid ? 'Отметить как не оплачен' : 'Отметить как оплачен' }}
                        </button>
                    </div>

                    <!-- Status update -->
                    <div class="rounded-xl bg-white p-5 shadow-sm">
                        <h2 class="mb-3 font-semibold text-gray-700">Изменить статус</h2>
                        <form @submit.prevent="updateStatus">
                            <select
                                v-model="statusForm.status"
                                class="mb-3 w-full rounded-lg border px-3 py-2 text-sm focus:border-pink-400 focus:outline-none"
                            >
                                <option v-for="s in statuses" :key="s" :value="s">{{ statusLabel[s] }}</option>
                            </select>
                            <button
                                type="submit"
                                :disabled="statusForm.processing"
                                class="w-full rounded-lg bg-pink-500 py-2 text-sm font-semibold text-white hover:bg-pink-600 disabled:opacity-50"
                            >
                                Сохранить
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>