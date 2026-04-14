<script setup lang="ts">
import { useCart } from '@/composables/useCart';
import StoreLayout from '@/layouts/StoreLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ShoppingCart } from 'lucide-vue-next';

interface Category {
    id: number;
    name: string;
    image_url: string | null;
}

interface Product {
    id: number;
    name: string;
    price: number;
    image_url: string | null;
    category: Category;
    brand: { id: number; name: string } | null;
}

defineProps<{
    categories: Category[];
    featured: Product[];
}>();

const { add } = useCart();
</script>

<template>
    <StoreLayout>
        <Head title="BabyMarket — всё для малышей" />

        <!-- Hero -->
        <section class="bg-gradient-to-br from-pink-50 to-purple-50 py-16">
            <div class="mx-auto max-w-7xl px-4 text-center">
                <h1 class="text-4xl font-bold text-gray-800 md:text-5xl">
                    Всё для вашего <span class="text-pink-500">малыша</span>
                </h1>
                <p class="mt-4 text-lg text-gray-500">Питание, одежда, игрушки и многое другое</p>
                <Link
                    :href="route('catalog')"
                    class="mt-8 inline-block rounded-full bg-pink-500 px-8 py-3 font-semibold text-white shadow hover:bg-pink-600"
                >
                    Перейти в каталог
                </Link>
            </div>
        </section>

        <!-- Categories -->
        <section class="mx-auto max-w-7xl px-4 py-12">
            <h2 class="mb-6 text-2xl font-bold text-gray-800">Категории</h2>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-6">
                <Link
                    v-for="category in categories"
                    :key="category.id"
                    :href="route('catalog', { category_id: category.id })"
                    class="group flex flex-col overflow-hidden rounded-xl bg-white shadow-sm transition hover:shadow-md hover:-translate-y-1"
                >
                    <div class="aspect-square w-full overflow-hidden bg-pink-50 flex items-center justify-center text-5xl">
                        <img
                            v-if="category.image_url"
                            :src="category.image_url"
                            :alt="category.name"
                            class="h-full w-full object-cover"
                        />
                        <span v-else>🍼</span>
                    </div>
                    <span class="p-2 text-center text-sm font-medium text-gray-700 group-hover:text-pink-500">
                        {{ category.name }}
                    </span>
                </Link>
            </div>
        </section>

        <!-- Featured products -->
        <section class="mx-auto max-w-7xl px-4 pb-16">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-800">Новинки</h2>
                <Link :href="route('catalog')" class="text-sm text-pink-500 hover:underline">Смотреть все →</Link>
            </div>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                <div
                    v-for="product in featured"
                    :key="product.id"
                    class="group rounded-xl bg-white p-4 shadow-sm transition hover:shadow-md"
                >
                    <Link :href="route('products.show', product.id)">
                        <div class="mb-3 overflow-hidden rounded-lg bg-gray-100 aspect-square">
                            <img
                                v-if="product.image_url"
                                :src="product.image_url"
                                :alt="product.name"
                                class="h-full w-full object-cover transition group-hover:scale-105"
                            />
                            <div v-else class="flex h-full items-center justify-center text-5xl">🧸</div>
                        </div>
                        <p v-if="product.brand" class="text-xs text-gray-400">{{ product.brand.name }}</p>
                        <p class="mt-1 text-sm font-medium text-gray-800 leading-snug line-clamp-2">{{ product.name }}</p>
                    </Link>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="font-bold text-pink-600">{{ Number(product.price).toLocaleString() }} сом</span>
                        <button
                            class="rounded-full bg-pink-500 p-1.5 text-white hover:bg-pink-600"
                            @click="add({ id: product.id, name: product.name, price: Number(product.price), image_url: product.image_url })"
                        >
                            <ShoppingCart class="h-4 w-4" />
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </StoreLayout>
</template>