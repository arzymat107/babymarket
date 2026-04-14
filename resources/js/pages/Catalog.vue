<script setup lang="ts">
import { useCart } from '@/composables/useCart';
import StoreLayout from '@/layouts/StoreLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ShoppingCart, SlidersHorizontal, X } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

interface Category {
    id: number;
    name: string;
    brands: { id: number; name: string }[];
    sizes: { id: number; name: string }[];
    age_groups: { id: number; name: string }[];
}

interface Product {
    id: number;
    name: string;
    price: number;
    image_url: string | null;
    brand: { name: string } | null;
    size: { name: string } | null;
    age_group: { name: string } | null;
}

interface Paginated<T> {
    data: T[];
    current_page: number;
    last_page: number;
    total: number;
    links: { url: string | null; label: string; active: boolean }[];
}

const props = defineProps<{
    products: Paginated<Product>;
    categories: Category[];
    filters: {
        category_id?: string;
        brand_id?: string;
        size_id?: string;
        age_group_id?: string;
        min_price?: string;
        max_price?: string;
    };
}>();

const { add } = useCart();
const filterOpen = ref(false);

const filters = ref({ ...props.filters });

const selectedCategory = ref<Category | null>(
    props.filters.category_id ? props.categories.find((c) => c.id === Number(props.filters.category_id)) ?? null : null,
);

watch(
    () => filters.value.category_id,
    (id) => {
        selectedCategory.value = props.categories.find((c) => c.id === Number(id)) ?? null;
        filters.value.brand_id     = undefined;
        filters.value.size_id      = undefined;
        filters.value.age_group_id = undefined;
    },
);

function applyFilters() {
    filterOpen.value = false;
    router.get(route('catalog'), removeEmpty(filters.value), { preserveScroll: true });
}

function removeEmpty(obj: Record<string, unknown>) {
    return Object.fromEntries(Object.entries(obj).filter(([, v]) => v !== undefined && v !== ''));
}

function translateLabel(label: string): string {
    return label
        .replace('Previous', 'Назад')
        .replace('Next', 'Вперёд');
}

function clearFilters() {
    filters.value = {};
    selectedCategory.value = null;
    filterOpen.value = false;
    router.get(route('catalog'));
}

const activeFilterCount = computed(() =>
    Object.values(props.filters).filter((v) => v !== undefined && v !== '').length,
);
</script>

<template>
    <StoreLayout>
        <Head title="Каталог" />

        <div class="mx-auto max-w-7xl px-4 py-6 md:py-8">
            <div class="mb-4 flex items-center justify-between md:mb-6">
                <h1 class="text-xl font-bold text-gray-800 md:text-2xl">Каталог товаров</h1>
                <!-- Mobile filter button -->
                <button
                    class="flex items-center gap-2 rounded-lg border bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 md:hidden"
                    @click="filterOpen = true"
                >
                    <SlidersHorizontal class="h-4 w-4" />
                    Фильтры
                    <span v-if="activeFilterCount > 0" class="flex h-5 w-5 items-center justify-center rounded-full bg-pink-500 text-xs text-white">
                        {{ activeFilterCount }}
                    </span>
                </button>
            </div>

            <div class="flex gap-6">
                <!-- Desktop filters sidebar -->
                <aside class="hidden w-60 flex-shrink-0 md:block">
                    <div class="rounded-xl bg-white p-4 shadow-sm">
                        <h3 class="mb-3 font-semibold text-gray-700">Категория</h3>
                        <select v-model="filters.category_id" class="w-full rounded-lg border px-3 py-2 text-sm">
                            <option value="">Все категории</option>
                            <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                        </select>

                        <template v-if="selectedCategory">
                            <div v-if="selectedCategory.brands.length" class="mt-4">
                                <h3 class="mb-2 font-semibold text-gray-700">Бренд</h3>
                                <select v-model="filters.brand_id" class="w-full rounded-lg border px-3 py-2 text-sm">
                                    <option value="">Все бренды</option>
                                    <option v-for="b in selectedCategory.brands" :key="b.id" :value="b.id">{{ b.name }}</option>
                                </select>
                            </div>
                            <div v-if="selectedCategory.sizes.length" class="mt-4">
                                <h3 class="mb-2 font-semibold text-gray-700">Размер</h3>
                                <select v-model="filters.size_id" class="w-full rounded-lg border px-3 py-2 text-sm">
                                    <option value="">Все размеры</option>
                                    <option v-for="s in selectedCategory.sizes" :key="s.id" :value="s.id">{{ s.name }}</option>
                                </select>
                            </div>
                            <div v-if="selectedCategory.age_groups.length" class="mt-4">
                                <h3 class="mb-2 font-semibold text-gray-700">Возраст</h3>
                                <select v-model="filters.age_group_id" class="w-full rounded-lg border px-3 py-2 text-sm">
                                    <option value="">Любой возраст</option>
                                    <option v-for="a in selectedCategory.age_groups" :key="a.id" :value="a.id">{{ a.name }}</option>
                                </select>
                            </div>
                        </template>

                        <div class="mt-4">
                            <h3 class="mb-2 font-semibold text-gray-700">Цена (сом)</h3>
                            <div class="flex gap-2">
                                <input v-model="filters.min_price" type="number" placeholder="От" class="w-full rounded-lg border px-3 py-2 text-sm" />
                                <input v-model="filters.max_price" type="number" placeholder="До" class="w-full rounded-lg border px-3 py-2 text-sm" />
                            </div>
                        </div>

                        <button class="mt-4 w-full rounded-lg bg-pink-500 py-2 text-sm font-semibold text-white hover:bg-pink-600" @click="applyFilters">
                            Применить
                        </button>
                        <button class="mt-2 w-full rounded-lg border py-2 text-sm text-gray-500 hover:bg-gray-50" @click="clearFilters">
                            Сбросить
                        </button>
                    </div>
                </aside>

                <!-- Products grid -->
                <div class="flex-1 min-w-0">
                    <p class="mb-4 text-sm text-gray-400">Найдено товаров: {{ products.total }}</p>

                    <div v-if="products.data.length === 0" class="py-20 text-center text-gray-400">
                        Товары не найдены
                    </div>

                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:gap-4">
                        <div
                            v-for="product in products.data"
                            :key="product.id"
                            class="group rounded-xl bg-white p-3 shadow-sm transition hover:shadow-md md:p-4"
                        >
                            <Link :href="route('products.show', product.id)">
                                <div class="mb-2 overflow-hidden rounded-lg bg-gray-100 aspect-square md:mb-3">
                                    <img
                                        v-if="product.image_url"
                                        :src="product.image_url"
                                        :alt="product.name"
                                        class="h-full w-full object-cover transition group-hover:scale-105"
                                    />
                                    <div v-else class="flex h-full items-center justify-center text-4xl md:text-5xl">🧸</div>
                                </div>
                                <p v-if="product.brand" class="text-xs text-gray-400">{{ product.brand.name }}</p>
                                <p class="mt-0.5 text-xs font-medium text-gray-800 leading-snug line-clamp-2 md:text-sm">{{ product.name }}</p>
                                <div class="mt-1 flex flex-wrap gap-1">
                                    <span v-if="product.size" class="rounded bg-gray-100 px-1 py-0.5 text-xs text-gray-500">
                                        {{ product.size.name }}
                                    </span>
                                    <span v-if="product.age_group" class="rounded bg-purple-50 px-1 py-0.5 text-xs text-purple-500">
                                        {{ product.age_group.name }}
                                    </span>
                                </div>
                            </Link>
                            <div class="mt-2 flex items-center justify-between gap-1">
                                <span class="text-sm font-bold text-pink-600 md:text-base">{{ Number(product.price).toLocaleString() }} сом</span>
                                <button
                                    class="rounded-full bg-pink-500 p-1.5 text-white hover:bg-pink-600 flex-shrink-0"
                                    @click="add({ id: product.id, name: product.name, price: Number(product.price), image_url: product.image_url })"
                                >
                                    <ShoppingCart class="h-3.5 w-3.5 md:h-4 md:w-4" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div v-if="products.last_page > 1" class="mt-8 flex flex-wrap justify-center gap-2">
                        <template v-for="link in products.links" :key="link.label">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                class="rounded-lg border px-3 py-1.5 text-sm"
                                :class="link.active ? 'bg-pink-500 text-white border-pink-500' : 'text-gray-600 hover:bg-gray-50'"
                                v-html="translateLabel(link.label)"
                            />
                            <span v-else class="rounded-lg border px-3 py-1.5 text-sm text-gray-300" v-html="translateLabel(link.label)" />
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile filter drawer -->
        <Transition name="fade">
            <div v-if="filterOpen" class="fixed inset-0 z-50 flex items-end md:hidden">
                <div class="absolute inset-0 bg-black/40" @click="filterOpen = false" />
                <Transition name="slide-up">
                    <div v-if="filterOpen" class="relative w-full max-h-[85vh] overflow-y-auto rounded-t-2xl bg-white px-4 pb-8 pt-4 shadow-xl">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-lg font-semibold">Фильтры</h2>
                            <button class="text-gray-400" @click="filterOpen = false"><X class="h-5 w-5" /></button>
                        </div>

                        <div class="mb-4">
                            <h3 class="mb-2 font-semibold text-gray-700">Категория</h3>
                            <select v-model="filters.category_id" class="w-full rounded-lg border px-3 py-2.5 text-sm">
                                <option value="">Все категории</option>
                                <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                            </select>
                        </div>

                        <template v-if="selectedCategory">
                            <div v-if="selectedCategory.brands.length" class="mb-4">
                                <h3 class="mb-2 font-semibold text-gray-700">Бренд</h3>
                                <select v-model="filters.brand_id" class="w-full rounded-lg border px-3 py-2.5 text-sm">
                                    <option value="">Все бренды</option>
                                    <option v-for="b in selectedCategory.brands" :key="b.id" :value="b.id">{{ b.name }}</option>
                                </select>
                            </div>
                            <div v-if="selectedCategory.sizes.length" class="mb-4">
                                <h3 class="mb-2 font-semibold text-gray-700">Размер</h3>
                                <select v-model="filters.size_id" class="w-full rounded-lg border px-3 py-2.5 text-sm">
                                    <option value="">Все размеры</option>
                                    <option v-for="s in selectedCategory.sizes" :key="s.id" :value="s.id">{{ s.name }}</option>
                                </select>
                            </div>
                            <div v-if="selectedCategory.age_groups.length" class="mb-4">
                                <h3 class="mb-2 font-semibold text-gray-700">Возраст</h3>
                                <select v-model="filters.age_group_id" class="w-full rounded-lg border px-3 py-2.5 text-sm">
                                    <option value="">Любой возраст</option>
                                    <option v-for="a in selectedCategory.age_groups" :key="a.id" :value="a.id">{{ a.name }}</option>
                                </select>
                            </div>
                        </template>

                        <div class="mb-4">
                            <h3 class="mb-2 font-semibold text-gray-700">Цена (сом)</h3>
                            <div class="flex gap-2">
                                <input v-model="filters.min_price" type="number" placeholder="От" class="w-full rounded-lg border px-3 py-2.5 text-sm" />
                                <input v-model="filters.max_price" type="number" placeholder="До" class="w-full rounded-lg border px-3 py-2.5 text-sm" />
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button class="flex-1 rounded-lg border py-3 text-sm text-gray-500" @click="clearFilters">Сбросить</button>
                            <button class="flex-1 rounded-lg bg-pink-500 py-3 text-sm font-semibold text-white" @click="applyFilters">Применить</button>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </StoreLayout>
</template>

<style scoped>
.fade-enter-from, .fade-leave-to { opacity: 0; }
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }

.slide-up-enter-from, .slide-up-leave-to { transform: translateY(100%); }
.slide-up-enter-active, .slide-up-leave-active { transition: transform 0.3s ease; }
</style>