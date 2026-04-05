<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Pencil, Plus, Trash2 } from 'lucide-vue-next';

interface Product {
    id: number;
    name: string;
    price: number;
    image_url: string | null;
    category: { name: string };
    brand: { name: string } | null;
}

interface Paginated<T> {
    data: T[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
}

defineProps<{ products: Paginated<Product> }>();

function destroy(product: Product) {
    if (confirm(`Удалить товар «${product.name}»?`)) {
        useForm({}).delete(route('admin.products.destroy', product.id));
    }
}
</script>

<template>
    <AppLayout>
        <Head title="Товары" />

        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-800">Товары</h1>
                <Link
                    :href="route('admin.products.create')"
                    class="flex items-center gap-1 rounded-lg bg-pink-500 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-600"
                >
                    <Plus class="h-4 w-4" /> Добавить
                </Link>
            </div>

            <div class="rounded-xl bg-white shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="border-b bg-gray-50 text-xs text-gray-400 uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left">Товар</th>
                            <th class="px-4 py-3 text-left">Категория</th>
                            <th class="px-4 py-3 text-left">Бренд</th>
                            <th class="px-4 py-3 text-left">Цена</th>
                            <th class="px-4 py-3 text-right">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-lg bg-gray-100 flex items-center justify-center text-lg overflow-hidden">
                                        <img v-if="product.image_url" :src="product.image_url" :alt="product.name" class="h-full w-full object-cover" />
                                        <span v-else>🧸</span>
                                    </div>
                                    <span class="font-medium text-gray-800">{{ product.name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ product.category.name }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ product.brand?.name ?? '—' }}</td>
                            <td class="px-4 py-3 font-semibold text-pink-600">{{ Number(product.price).toLocaleString() }} сом</td>
                            <td class="px-4 py-3 text-right">
                                <Link :href="route('admin.products.edit', product.id)" class="mr-2 text-gray-400 hover:text-blue-500">
                                    <Pencil class="h-4 w-4 inline" />
                                </Link>
                                <button class="text-gray-400 hover:text-red-500" @click="destroy(product)">
                                    <Trash2 class="h-4 w-4" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="products.last_page > 1" class="mt-6 flex flex-wrap justify-center gap-2">
                <template v-for="link in products.links" :key="link.label">
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        class="rounded-lg border px-3 py-1.5 text-sm"
                        :class="link.active ? 'bg-pink-500 text-white border-pink-500' : 'text-gray-600 hover:bg-gray-50'"
                        v-html="link.label"
                    />
                    <span v-else class="rounded-lg border px-3 py-1.5 text-sm text-gray-300" v-html="link.label" />
                </template>
            </div>
        </div>
    </AppLayout>
</template>