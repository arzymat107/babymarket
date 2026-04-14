<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Check, Pencil, Plus, Trash2, X } from 'lucide-vue-next';
import { ref } from 'vue';
import { translatePageLabel } from '@/composables/usePaginationLabel';

interface Item    { id: number; name: string }
interface Product {
    id: number; name: string; price: number; image_url: string | null;
    brand: Item | null; size: Item | null; age_group: Item | null;
}
interface Paginated<T> {
    data: T[]; current_page: number; last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
}
interface Category {
    id: number; name: string; image_url: string | null;
    brands: Item[]; sizes: Item[]; age_groups: Item[];
}

const props = defineProps<{ category: Category; products: Paginated<Product> }>();

// ── Generic inline list manager ──────────────────────────────────────────────
function useItemList(storeRoute: string, updateRoute: string, destroyRoute: string, categoryId: number) {
    const editingId  = ref<number | null>(null);
    const editName   = ref('');
    const addForm    = useForm({ name: '' });

    function startEdit(item: Item) {
        editingId.value = item.id;
        editName.value  = item.name;
    }

    function cancelEdit() {
        editingId.value = null;
    }

    function saveEdit(item: Item) {
        useForm({ name: editName.value }).patch(route(updateRoute, item.id), {
            onSuccess: () => { editingId.value = null; },
        });
    }

    function remove(item: Item) {
        if (confirm(`Удалить «${item.name}»?`)) {
            useForm({}).delete(route(destroyRoute, item.id));
        }
    }

    function add() {
        addForm.post(route(storeRoute, categoryId), {
            onSuccess: () => addForm.reset(),
        });
    }

    return { editingId, editName, addForm, startEdit, cancelEdit, saveEdit, remove, add };
}

const brands    = useItemList('admin.brands.store',     'admin.brands.update',     'admin.brands.destroy',     props.category.id);
const sizes     = useItemList('admin.sizes.store',      'admin.sizes.update',      'admin.sizes.destroy',      props.category.id);
const ageGroups = useItemList('admin.age_groups.store', 'admin.age_groups.update', 'admin.age_groups.destroy', props.category.id);

// ── Category ──────────────────────────────────────────────────────────────────
function destroyCategory() {
    if (confirm(`Удалить категорию «${props.category.name}»?`)) {
        useForm({}).delete(route('admin.categories.destroy', props.category.id));
    }
}

</script>

<template>
    <AppLayout>
        <Head :title="category.name" />

        <div class="px-3 py-6 sm:px-6">
            <!-- Header -->
            <div class="mb-6 flex items-center gap-4">
                <Link :href="route('admin.categories.index')" class="text-gray-400 hover:text-pink-500"><ArrowLeft class="h-6 w-6" /></Link>
                <h1 class="text-base sm:text-2xl font-bold text-gray-800">{{ category.name }}</h1>
                <div class="ml-auto flex items-center gap-2">
                    <Link
                        :href="route('admin.categories.edit', category.id)"
                        class="flex items-center gap-1 rounded-lg border px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50"
                    >
                        <Pencil class="h-4 w-4" /> <span class="hidden sm:inline">Редактировать</span>
                    </Link>
                    <button
                        class="flex items-center gap-1 rounded-lg border border-red-200 px-3 py-1.5 text-sm text-red-500 hover:bg-red-50"
                        @click="destroyCategory"
                    >
                        <Trash2 class="h-4 w-4" /> <span class="hidden sm:inline">Удалить</span>
                    </button>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <!-- Left column -->
                <div class="space-y-4">
                    <!-- Category image -->
                    <div class="rounded-xl bg-white p-5 shadow-sm">
                        <div class="mb-4 overflow-hidden rounded-xl bg-gray-100 aspect-square flex items-center justify-center text-6xl">
                            <img v-if="category.image_url" :src="category.image_url" :alt="category.name" class="h-full w-full object-cover" />
                            <span v-else>🍼</span>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">{{ category.name }}</h2>
                    </div>

                    <!-- Brands -->
                    <div class="rounded-xl bg-white p-5 shadow-sm">
                        <h3 class="mb-3 font-semibold text-gray-700">Бренды</h3>
                        <div class="space-y-2">
                            <div v-for="item in category.brands" :key="item.id" class="flex items-center gap-2">
                                <template v-if="brands.editingId.value === item.id">
                                    <input
                                        v-model="brands.editName.value"
                                        class="flex-1 rounded-lg border px-2 py-1 text-sm focus:border-pink-400 focus:outline-none"
                                        @keyup.enter="brands.saveEdit(item)"
                                        @keyup.esc="brands.cancelEdit()"
                                    />
                                    <button class="text-green-500 hover:text-green-600" @click="brands.saveEdit(item)"><Check class="h-4 w-4" /></button>
                                    <button class="text-gray-400 hover:text-gray-600" @click="brands.cancelEdit()"><X class="h-4 w-4" /></button>
                                </template>
                                <template v-else>
                                    <span class="flex-1 rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-700">{{ item.name }}</span>
                                    <button class="text-gray-400 hover:text-blue-500" @click="brands.startEdit(item)"><Pencil class="h-3.5 w-3.5" /></button>
                                    <button class="text-gray-400 hover:text-red-500" @click="brands.remove(item)"><Trash2 class="h-3.5 w-3.5" /></button>
                                </template>
                            </div>
                            <p v-if="!category.brands.length" class="text-sm text-gray-400">Нет брендов</p>
                        </div>
                        <form class="mt-3 flex gap-2" @submit.prevent="brands.add()">
                            <input
                                v-model="brands.addForm.name"
                                placeholder="Новый бренд"
                                class="flex-1 rounded-lg border px-2 py-1.5 text-sm focus:border-pink-400 focus:outline-none"
                            />
                            <button type="submit" class="rounded-lg bg-pink-500 px-2.5 py-1.5 text-white hover:bg-pink-600">
                                <Plus class="h-4 w-4" />
                            </button>
                        </form>
                    </div>

                    <!-- Sizes -->
                    <div class="rounded-xl bg-white p-5 shadow-sm">
                        <h3 class="mb-3 font-semibold text-gray-700">Размеры</h3>
                        <div class="space-y-2">
                            <div v-for="item in category.sizes" :key="item.id" class="flex items-center gap-2">
                                <template v-if="sizes.editingId.value === item.id">
                                    <input
                                        v-model="sizes.editName.value"
                                        class="flex-1 rounded-lg border px-2 py-1 text-sm focus:border-pink-400 focus:outline-none"
                                        @keyup.enter="sizes.saveEdit(item)"
                                        @keyup.esc="sizes.cancelEdit()"
                                    />
                                    <button class="text-green-500 hover:text-green-600" @click="sizes.saveEdit(item)"><Check class="h-4 w-4" /></button>
                                    <button class="text-gray-400 hover:text-gray-600" @click="sizes.cancelEdit()"><X class="h-4 w-4" /></button>
                                </template>
                                <template v-else>
                                    <span class="flex-1 rounded-full bg-blue-50 px-3 py-1 text-sm text-blue-700">{{ item.name }}</span>
                                    <button class="text-gray-400 hover:text-blue-500" @click="sizes.startEdit(item)"><Pencil class="h-3.5 w-3.5" /></button>
                                    <button class="text-gray-400 hover:text-red-500" @click="sizes.remove(item)"><Trash2 class="h-3.5 w-3.5" /></button>
                                </template>
                            </div>
                            <p v-if="!category.sizes.length" class="text-sm text-gray-400">Нет размеров</p>
                        </div>
                        <form class="mt-3 flex gap-2" @submit.prevent="sizes.add()">
                            <input
                                v-model="sizes.addForm.name"
                                placeholder="Новый размер"
                                class="flex-1 rounded-lg border px-2 py-1.5 text-sm focus:border-pink-400 focus:outline-none"
                            />
                            <button type="submit" class="rounded-lg bg-pink-500 px-2.5 py-1.5 text-white hover:bg-pink-600">
                                <Plus class="h-4 w-4" />
                            </button>
                        </form>
                    </div>

                    <!-- Age groups -->
                    <div class="rounded-xl bg-white p-5 shadow-sm">
                        <h3 class="mb-3 font-semibold text-gray-700">Возрастные группы</h3>
                        <div class="space-y-2">
                            <div v-for="item in category.age_groups" :key="item.id" class="flex items-center gap-2">
                                <template v-if="ageGroups.editingId.value === item.id">
                                    <input
                                        v-model="ageGroups.editName.value"
                                        class="flex-1 rounded-lg border px-2 py-1 text-sm focus:border-pink-400 focus:outline-none"
                                        @keyup.enter="ageGroups.saveEdit(item)"
                                        @keyup.esc="ageGroups.cancelEdit()"
                                    />
                                    <button class="text-green-500 hover:text-green-600" @click="ageGroups.saveEdit(item)"><Check class="h-4 w-4" /></button>
                                    <button class="text-gray-400 hover:text-gray-600" @click="ageGroups.cancelEdit()"><X class="h-4 w-4" /></button>
                                </template>
                                <template v-else>
                                    <span class="flex-1 rounded-full bg-purple-50 px-3 py-1 text-sm text-purple-700">{{ item.name }}</span>
                                    <button class="text-gray-400 hover:text-blue-500" @click="ageGroups.startEdit(item)"><Pencil class="h-3.5 w-3.5" /></button>
                                    <button class="text-gray-400 hover:text-red-500" @click="ageGroups.remove(item)"><Trash2 class="h-3.5 w-3.5" /></button>
                                </template>
                            </div>
                            <p v-if="!category.age_groups.length" class="text-sm text-gray-400">Нет возрастных групп</p>
                        </div>
                        <form class="mt-3 flex gap-2" @submit.prevent="ageGroups.add()">
                            <input
                                v-model="ageGroups.addForm.name"
                                placeholder="Новая группа"
                                class="flex-1 rounded-lg border px-2 py-1.5 text-sm focus:border-pink-400 focus:outline-none"
                            />
                            <button type="submit" class="rounded-lg bg-pink-500 px-2.5 py-1.5 text-white hover:bg-pink-600">
                                <Plus class="h-4 w-4" />
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Products -->
                <div class="md:col-span-2">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="font-semibold text-gray-700">Товары</h2>
                        <Link
                            :href="route('admin.products.create', { from_category: category.id })"
                            class="flex items-center gap-1 rounded-lg bg-pink-500 px-3 py-1.5 text-sm font-semibold text-white hover:bg-pink-600"
                        >
                            <Plus class="h-4 w-4" /> Добавить товар
                        </Link>
                    </div>

                    <div class="rounded-xl bg-white shadow-sm overflow-hidden">
                        <div v-if="products.data.length === 0" class="py-16 text-center text-gray-400">
                            В этой категории нет товаров
                        </div>
                        <table v-else class="w-full text-sm">
                            <thead class="border-b bg-gray-50 text-xs text-gray-400 uppercase">
                                <tr>
                                    <th class="px-4 py-3 text-left">Товар</th>
                                    <th class="px-4 py-3 text-left">Бренд</th>
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
                                            <Link :href="route('admin.products.show', [product.id, { from_category: category.id }])" class="font-medium text-gray-800 hover:text-pink-600">{{ product.name }}</Link>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">{{ product.brand?.name ?? '—' }}</td>
                                    <td class="px-4 py-3 font-semibold text-pink-600">{{ Number(product.price).toLocaleString() }} сом</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="products.last_page > 1" class="mt-4 flex flex-wrap justify-center gap-2">
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
            </div>
        </div>
    </AppLayout>
</template>