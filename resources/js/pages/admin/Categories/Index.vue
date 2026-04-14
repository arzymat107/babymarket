<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';

interface Category {
    id: number;
    name: string;
    image_url: string | null;
    products_count: number;
}

defineProps<{ categories: Category[] }>();


</script>

<template>
    <AppLayout>
        <Head title="Категории" />

        <div class="px-3 py-6 sm:px-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-800">Категории</h1>
                <Link
                    :href="route('admin.categories.create')"
                    class="flex items-center gap-1 rounded-lg bg-pink-500 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-600"
                >
                    <Plus class="h-4 w-4" /> Добавить
                </Link>
            </div>

            <div class="rounded-xl bg-white shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="border-b bg-gray-50 text-xs text-gray-400 uppercase">
                        <tr>
                            <th class="px-6 py-3 text-left">Название</th>
                            <th class="px-6 py-3 text-left">Товаров</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr v-for="cat in categories" :key="cat.id" class="hover:bg-gray-50">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-lg bg-gray-100 flex items-center justify-center text-lg overflow-hidden">
                                        <img v-if="cat.image_url" :src="cat.image_url" :alt="cat.name" class="h-full w-full object-cover" />
                                        <span v-else>🍼</span>
                                    </div>
                                    <Link :href="route('admin.categories.show', cat.id)" class="font-medium text-gray-800 hover:text-pink-500">
                                        {{ cat.name }}
                                    </Link>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-gray-500">{{ cat.products_count }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>