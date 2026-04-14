<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { translatePageLabel } from '@/composables/usePaginationLabel';

interface Order {
    id: number;
    customer_name: string;
    customer_phone: string;
    shipping_address: string;
    total_amount: number;
    paid: boolean;
    status: string;
    created_at: string;
}

interface Paginated<T> {
    data: T[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
}

defineProps<{
    orders: Paginated<Order>;
    filters: { status?: string };
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

const statuses = ['', 'pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];
const statusFilter = ref('');

function filterByStatus(s: string) {
    statusFilter.value = s;
    router.get(route('admin.orders.index'), s ? { status: s } : {}, { preserveScroll: true });
}
</script>

<template>
    <AppLayout>
        <Head title="Заказы" />

        <div class="px-3 py-6 sm:px-6">
            <h1 class="mb-6 text-2xl font-bold text-gray-800">Заказы</h1>

            <!-- Status filter tabs -->
            <div class="mb-4 flex flex-wrap gap-2">
                <button
                    v-for="s in statuses"
                    :key="s"
                    class="rounded-full border px-3 py-1 text-sm transition"
                    :class="filters.status === s || (!filters.status && s === '') ? 'bg-pink-500 text-white border-pink-500' : 'text-gray-500 hover:bg-gray-50'"
                    @click="filterByStatus(s)"
                >
                    {{ s ? statusLabel[s] : 'Все' }}
                </button>
            </div>

            <div class="rounded-xl bg-white shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="border-b bg-gray-50 text-xs text-gray-400 uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left">#</th>
                            <th class="px-4 py-3 text-left">Клиент</th>
                            <th class="hidden sm:table-cell px-4 py-3 text-left">Телефон</th>
                            <th class="hidden sm:table-cell px-4 py-3 text-left">Сумма</th>
                            <th class="hidden sm:table-cell px-4 py-3 text-left">Оплата</th>
                            <th class="px-4 py-3 text-left">Статус</th>
                            <th class="hidden sm:table-cell px-4 py-3 text-left">Дата</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr v-for="order in orders.data" :key="order.id" class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <Link :href="route('admin.orders.show', order.id)" class="font-medium text-pink-500 hover:underline">
                                    #{{ order.id }}
                                </Link>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ order.customer_name }}</td>
                            <td class="hidden sm:table-cell px-4 py-3 text-gray-500">{{ order.customer_phone }}</td>
                            <td class="hidden sm:table-cell px-4 py-3 font-semibold">{{ Number(order.total_amount).toLocaleString() }} сом</td>
                            <td class="hidden sm:table-cell px-4 py-3">
                                <span
                                    class="rounded-full px-2.5 py-1 text-xs font-medium"
                                    :class="order.paid ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
                                >
                                    {{ order.paid ? 'Оплачен' : 'Не оплачен' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium" :class="statusClass[order.status]">
                                    {{ statusLabel[order.status] }}
                                </span>
                            </td>
                            <td class="hidden sm:table-cell px-4 py-3 text-gray-400">{{ new Date(order.created_at).toLocaleDateString('ru') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="orders.last_page > 1" class="mt-6 flex flex-wrap justify-center gap-2">
                <template v-for="link in orders.links" :key="link.label">
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        class="rounded-lg border px-3 py-1.5 text-sm"
                        :class="link.active ? 'bg-pink-500 text-white border-pink-500' : 'text-gray-600 hover:bg-gray-50'"
                        v-html="translatePageLabel(link.label)"
                    />
                    <span v-else class="rounded-lg border px-3 py-1.5 text-sm text-gray-300" v-html="translatePageLabel(link.label)" />
                </template>
            </div>
        </div>
    </AppLayout>
</template>