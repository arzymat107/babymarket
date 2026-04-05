<script setup lang="ts">
import { useCart } from '@/composables/useCart';
import StoreLayout from '@/layouts/StoreLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ShoppingCart } from 'lucide-vue-next';
import { ref } from 'vue';

interface Product {
    id: number;
    name: string;
    price: number;
    image_url: string | null;
    category: { id: number; name: string };
    brand: { name: string } | null;
    size: { name: string } | null;
    age_group: { name: string } | null;
}

const props = defineProps<{
    product: Product;
    related: Product[];
}>();

const { add } = useCart();
const quantity = ref(1);

function addToCart() {
    add(
        {
            id: props.product.id,
            name: props.product.name,
            price: Number(props.product.price),
            image_url: props.product.image_url,
        },
        quantity.value,
    );
}
</script>

<template>
    <StoreLayout>
        <Head :title="product.name" />

        <div class="mx-auto max-w-7xl px-4 py-8">
            <!-- Breadcrumb -->
            <nav class="mb-6 flex gap-2 text-sm text-gray-400">
                <Link :href="route('home')" class="hover:text-pink-500">Главная</Link>
                <span>/</span>
                <Link :href="route('catalog', { category_id: product.category.id })" class="hover:text-pink-500">
                    {{ product.category.name }}
                </Link>
                <span>/</span>
                <span class="text-gray-600">{{ product.name }}</span>
            </nav>

            <!-- Product -->
            <div class="grid gap-8 md:grid-cols-2">
                <!-- Image -->
                <div class="overflow-hidden rounded-2xl bg-gray-100 aspect-square">
                    <img
                        v-if="product.image_url"
                        :src="product.image_url"
                        :alt="product.name"
                        class="h-full w-full object-cover"
                    />
                    <div v-else class="flex h-full items-center justify-center text-8xl">🧸</div>
                </div>

                <!-- Info -->
                <div class="flex flex-col">
                    <p v-if="product.brand" class="text-sm font-medium text-gray-400 uppercase tracking-wide">
                        {{ product.brand.name }}
                    </p>
                    <h1 class="mt-1 text-2xl font-bold text-gray-800">{{ product.name }}</h1>

                    <div class="mt-3 flex flex-wrap gap-2">
                        <span v-if="product.size" class="rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-600">
                            Размер: {{ product.size.name }}
                        </span>
                        <span v-if="product.age_group" class="rounded-full bg-purple-50 px-3 py-1 text-sm text-purple-600">
                            {{ product.age_group.name }}
                        </span>
                    </div>

                    <p class="mt-6 text-3xl font-bold text-pink-600">
                        {{ Number(product.price).toLocaleString() }} сом
                    </p>

                    <!-- Quantity + Add to cart -->
                    <div class="mt-6 flex items-center gap-4">
                        <div class="flex items-center rounded-lg border">
                            <button
                                class="px-3 py-2 text-gray-600 hover:bg-gray-50"
                                @click="quantity = Math.max(1, quantity - 1)"
                            >−</button>
                            <span class="w-10 text-center font-medium">{{ quantity }}</span>
                            <button class="px-3 py-2 text-gray-600 hover:bg-gray-50" @click="quantity++">+</button>
                        </div>
                        <button
                            class="flex flex-1 items-center justify-center gap-2 rounded-lg bg-pink-500 py-3 font-semibold text-white hover:bg-pink-600"
                            @click="addToCart"
                        >
                            <ShoppingCart class="h-5 w-5" />
                            В корзину
                        </button>
                    </div>
                </div>
            </div>

            <!-- Related products -->
            <div v-if="related.length > 0" class="mt-14">
                <h2 class="mb-4 text-xl font-bold text-gray-800">Похожие товары</h2>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <div
                        v-for="item in related"
                        :key="item.id"
                        class="group rounded-xl bg-white p-4 shadow-sm hover:shadow-md transition"
                    >
                        <Link :href="route('products.show', item.id)">
                            <div class="mb-3 overflow-hidden rounded-lg bg-gray-100 aspect-square">
                                <img
                                    v-if="item.image_url"
                                    :src="item.image_url"
                                    :alt="item.name"
                                    class="h-full w-full object-cover group-hover:scale-105 transition"
                                />
                                <div v-else class="flex h-full items-center justify-center text-4xl">🧸</div>
                            </div>
                            <p v-if="item.brand" class="text-xs text-gray-400">{{ item.brand.name }}</p>
                            <p class="mt-1 text-sm font-medium text-gray-800 line-clamp-2">{{ item.name }}</p>
                            <p class="mt-1 font-bold text-pink-600">{{ Number(item.price).toLocaleString() }} сом</p>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>