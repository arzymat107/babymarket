<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Pencil, Trash2 } from 'lucide-vue-next';

interface Product {
    id: number;
    name: string;
    price: number;
    image_url: string | null;
    category: { id: number; name: string } | null;
    brand: { name: string } | null;
    size: { name: string } | null;
    age_group: { name: string } | null;
}

interface OrderRow {
    order_id: number;
    quantity: number;
    unit_price: string;
    subtotal: string;
    status: string;
    paid: boolean;
    created_at: string;
}

const props = defineProps<{ product: Product; orders: OrderRow[]; from_category?: number | null }>();

const STATUS_LABELS: Record<string, string> = {
    new:        'Новый',
    processing: 'В обработке',
    shipped:    'Отправлен',
    delivered:  'Доставлен',
    cancelled:  'Отменён',
};

const STATUS_COLORS: Record<string, string> = {
    new:        'bg-blue-100 text-blue-700',
    processing: 'bg-yellow-100 text-yellow-700',
    shipped:    'bg-purple-100 text-purple-700',
    delivered:  'bg-green-100 text-green-700',
    cancelled:  'bg-red-100 text-red-700',
};

function destroy() {
    if (confirm(`Удалить товар «${props.product.name}»?`)) {
        useForm({ from_category: props.from_category ?? null }).delete(route('admin.products.destroy', props.product.id));
    }
}
</script>

<template>
    <AppLayout>
        <Head :title="product.name" />

        <div class="px-3 py-6 sm:px-6">
            <!-- Header -->
            <div class="mb-6 flex items-center gap-4">
                <Link
                    :href="from_category ? route('admin.categories.show', from_category) : route('admin.products.index')"
                    class="text-gray-400 hover:text-pink-500"
                ><ArrowLeft class="h-6 w-6" /></Link>
                <h1 class="text-base sm:text-2xl font-bold text-gray-800">{{ product.name }}</h1>
                <div class="ml-auto flex items-center gap-2">
                    <Link
                        :href="route('admin.products.edit', [product.id, from_category ? { from_category } : {}])"
                        class="flex items-center gap-1 rounded-lg border px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50"
                    >
                        <Pencil class="h-4 w-4" /> <span class="hidden sm:inline">Редактировать</span>
                    </Link>
                    <button
                        class="flex items-center gap-1 rounded-lg border border-red-200 px-3 py-1.5 text-sm text-red-500 hover:bg-red-50"
                        @click="destroy"
                    >
                        <Trash2 class="h-4 w-4" /> <span class="hidden sm:inline">Удалить</span>
                    </button>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <!-- Left: image + details -->
                <div class="space-y-4">
                    <!-- Image -->
                    <div class="rounded-xl bg-white p-5 shadow-sm">
                        <div class="mb-4 aspect-square overflow-hidden rounded-xl bg-gray-100 flex items-center justify-center text-6xl">
                            <img v-if="product.image_url" :src="product.image_url" :alt="product.name" class="h-full w-full object-cover" />
                            <span v-else>🧸</span>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">{{ product.name }}</h2>
                        <p class="mt-1 text-xl font-bold text-pink-600">{{ Number(product.price).toLocaleString() }} сом</p>
                    </div>

                    <!-- Attributes -->
                    <div class="rounded-xl bg-white p-5 shadow-sm">
                        <h3 class="mb-3 font-semibold text-gray-700">Характеристики</h3>
                        <dl class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-gray-400">Категория</dt>
                                <dd class="font-medium text-gray-700">
                                    <Link
                                        v-if="product.category"
                                        :href="route('admin.categories.show', product.category.id)"
                                        class="text-pink-600 hover:underline"
                                    >{{ product.category.name }}</Link>
                                    <span v-else>—</span>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-400">Бренд</dt>
                                <dd class="font-medium text-gray-700">{{ product.brand?.name ?? '—' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-400">Размер</dt>
                                <dd class="font-medium text-gray-700">{{ product.size?.name ?? '—' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-400">Возраст</dt>
                                <dd class="font-medium text-gray-700">{{ product.age_group?.name ?? '—' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Right: order history -->
                <div class="md:col-span-2">
                    <h2 class="mb-4 font-semibold text-gray-700">История заказов</h2>

                    <div class="rounded-xl bg-white shadow-sm overflow-hidden">
                        <div v-if="orders.length === 0" class="py-16 text-center text-gray-400">
                            Этот товар ещё не заказывали
                        </div>
                        <table v-else class="w-full text-sm">
                            <thead class="border-b bg-gray-50 text-xs text-gray-400 uppercase">
                                <tr>
                                    <th class="px-4 py-3 text-left">Заказ</th>
                                    <th class="px-4 py-3 text-left">Дата</th>
                                    <th class="px-4 py-3 text-center">Кол-во</th>
                                    <th class="hidden sm:table-cell px-4 py-3 text-right">Сумма</th>
                                    <th class="hidden sm:table-cell px-4 py-3 text-center">Статус</th>
                                    <th class="hidden sm:table-cell px-4 py-3 text-center">Оплата</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                <tr v-for="row in orders" :key="row.order_id" class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <span class="font-medium text-gray-700">#{{ row.order_id }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">{{ row.created_at }}</td>
                                    <td class="px-4 py-3 text-center text-gray-700">{{ row.quantity }}</td>
                                    <td class="hidden sm:table-cell px-4 py-3 text-right font-semibold text-gray-800">
                                        {{ Number(row.subtotal).toLocaleString() }} сом
                                    </td>
                                    <td class="hidden sm:table-cell px-4 py-3 text-center">
                                        <span
                                            class="rounded-full px-2 py-0.5 text-xs font-medium"
                                            :class="STATUS_COLORS[row.status] ?? 'bg-gray-100 text-gray-600'"
                                        >{{ STATUS_LABELS[row.status] ?? row.status }}</span>
                                    </td>
                                    <td class="hidden sm:table-cell px-4 py-3 text-center">
                                        <span
                                            class="rounded-full px-2 py-0.5 text-xs font-medium"
                                            :class="row.paid ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
                                        >{{ row.paid ? 'Оплачен' : 'Не оплачен' }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>