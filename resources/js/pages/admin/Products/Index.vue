<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Search, Plus } from 'lucide-vue-next';
import { translatePageLabel } from '@/composables/usePaginationLabel';
import { ref, watch } from 'vue';

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

const props = defineProps<{ products: Paginated<Product>; search: string }>();

const searchQuery = ref(props.search);

let debounceTimer: ReturnType<typeof setTimeout>;
watch(searchQuery, (val) => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        router.get(route('admin.products.index'), { search: val }, { preserveState: true, replace: true });
    }, 300);
});


</script>

<template>
    <AppLayout>
        <Head title="Товары" />

        <div class="px-3 py-6 sm:px-6">
            <div class="mb-6 flex flex-wrap items-center gap-4">
                <h1 class="order-1 text-2xl font-bold text-gray-800">Товары</h1>
                <div class="relative order-3 w-full sm:order-2 sm:w-auto sm:flex-1 sm:max-w-sm">
                    <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Поиск по названию..."
                        class="w-full rounded-lg border py-2 pl-9 pr-3 text-sm focus:border-pink-400 focus:outline-none"
                    />
                </div>
                <Link
                    :href="route('admin.products.create')"
                    class="order-2 ml-auto flex items-center gap-1 rounded-lg bg-pink-500 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-600 sm:order-3"
                >
                    <Plus class="h-4 w-4" /> Добавить
                </Link>
            </div>

            <div class="rounded-xl bg-white shadow-sm overflow-hidden">
                <div v-if="products.data.length === 0" class="py-16 text-center text-gray-400">
                    {{ searchQuery ? `По запросу «${searchQuery}» ничего не найдено` : 'Нет товаров' }}
                </div>
                <table v-else class="w-full text-sm">
                    <thead class="border-b bg-gray-50 text-xs text-gray-400 uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left">Товар</th>
                            <th class="px-4 py-3 text-left">Категория</th>
                            <th class="hidden sm:table-cell px-4 py-3 text-left">Бренд</th>
                            <th class="px-4 py-3 text-left">Цена</th>
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
                                    <Link :href="route('admin.products.show', product.id)" class="font-medium text-gray-800 hover:text-pink-600">{{ product.name }}</Link>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ product.category.name }}</td>
                            <td class="hidden sm:table-cell px-4 py-3 text-gray-500">{{ product.brand?.name ?? '—' }}</td>
                            <td class="px-4 py-3 font-semibold text-pink-600">{{ Number(product.price).toLocaleString() }} сом</td>
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
                        v-html="translatePageLabel(link.label)"
                    />
                    <span v-else class="rounded-lg border px-3 py-1.5 text-sm text-gray-300" v-html="translatePageLabel(link.label)" />
                </template>
            </div>
        </div>
    </AppLayout>
</template>