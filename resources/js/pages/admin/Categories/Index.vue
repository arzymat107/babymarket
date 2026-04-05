<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Pencil, Trash2, X } from 'lucide-vue-next';
import { ref } from 'vue';

interface Category {
    id: number;
    name: string;
    image_url: string | null;
    products_count: number;
}

defineProps<{ categories: Category[] }>();

const showModal = ref(false);
const editing  = ref<Category | null>(null);

const form = useForm({ name: '', image_url: '' });

function openCreate() {
    editing.value = null;
    form.reset();
    showModal.value = true;
}

function openEdit(cat: Category) {
    editing.value = cat;
    form.name      = cat.name;
    form.image_url = cat.image_url ?? '';
    showModal.value = true;
}

function submit() {
    if (editing.value) {
        form.put(route('admin.categories.update', editing.value.id), {
            onSuccess: () => { showModal.value = false; form.reset(); },
        });
    } else {
        form.post(route('admin.categories.store'), {
            onSuccess: () => { showModal.value = false; form.reset(); },
        });
    }
}

function destroy(cat: Category) {
    if (confirm(`Удалить категорию «${cat.name}»?`)) {
        useForm({}).delete(route('admin.categories.destroy', cat.id));
    }
}
</script>

<template>
    <AppLayout>
        <Head title="Категории" />

        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-800">Категории</h1>
                <button
                    class="rounded-lg bg-pink-500 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-600"
                    @click="openCreate"
                >
                    + Добавить
                </button>
            </div>

            <div class="rounded-xl bg-white shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="border-b bg-gray-50 text-xs text-gray-400 uppercase">
                        <tr>
                            <th class="px-6 py-3 text-left">Название</th>
                            <th class="px-6 py-3 text-left">Товаров</th>
                            <th class="px-6 py-3 text-right">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr v-for="cat in categories" :key="cat.id" class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium text-gray-800">{{ cat.name }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ cat.products_count }}</td>
                            <td class="px-6 py-3 text-right">
                                <button class="mr-2 text-gray-400 hover:text-blue-500" @click="openEdit(cat)">
                                    <Pencil class="h-4 w-4" />
                                </button>
                                <button class="text-gray-400 hover:text-red-500" @click="destroy(cat)">
                                    <Trash2 class="h-4 w-4" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <Transition name="fade">
            <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
                <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-lg font-semibold">{{ editing ? 'Редактировать' : 'Новая категория' }}</h2>
                        <button class="text-gray-400 hover:text-gray-600" @click="showModal = false"><X class="h-5 w-5" /></button>
                    </div>
                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <label class="mb-1 block text-sm text-gray-600">Название</label>
                            <input
                                v-model="form.name"
                                type="text"
                                class="w-full rounded-lg border px-3 py-2 text-sm focus:border-pink-400 focus:outline-none"
                                :class="{ 'border-red-400': form.errors.name }"
                            />
                            <p v-if="form.errors.name" class="mt-1 text-xs text-red-500">{{ form.errors.name }}</p>
                        </div>
                        <div class="mb-6">
                            <label class="mb-1 block text-sm text-gray-600">URL изображения (необязательно)</label>
                            <input
                                v-model="form.image_url"
                                type="text"
                                class="w-full rounded-lg border px-3 py-2 text-sm focus:border-pink-400 focus:outline-none"
                            />
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" class="rounded-lg border px-4 py-2 text-sm hover:bg-gray-50" @click="showModal = false">
                                Отмена
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="rounded-lg bg-pink-500 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-600 disabled:opacity-50"
                            >
                                {{ editing ? 'Сохранить' : 'Создать' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>
    </AppLayout>
</template>

<style scoped>
.fade-enter-from, .fade-leave-to { opacity: 0; }
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
</style>