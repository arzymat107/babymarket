<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
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
    category_id: number;
    brand_id: number | null;
    size_id: number | null;
    age_group_id: number | null;
}

const props = defineProps<{
    categories: Category[];
    product?: Product;
    from_category?: number | null;
}>();

const isEdit = computed(() => !!props.product);

const form = useForm({
    category_id:   props.product?.category_id  ?? '',
    brand_id:      props.product?.brand_id     ?? '',
    size_id:       props.product?.size_id      ?? '',
    age_group_id:  props.product?.age_group_id ?? '',
    name:          props.product?.name         ?? '',
    price:         props.product?.price        ?? '',
    image:         null as File | null,
    from_category: props.from_category ?? null,
});

const preview = ref<string | null>(props.product?.image_url ?? null);

function onFileChange(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0] ?? null;
    form.image   = file;
    preview.value = file ? URL.createObjectURL(file) : (props.product?.image_url ?? null);
}

const selectedCategory = computed<Category | undefined>(
    () => props.categories.find((c) => c.id === Number(form.category_id)),
);

watch(() => form.category_id, () => {
    form.brand_id     = '';
    form.size_id      = '';
    form.age_group_id = '';
});

function submit() {
    if (isEdit.value && props.product) {
        form.transform((data) => ({ ...data, _method: 'PUT' }))
            .post(route('admin.products.update', props.product.id));
    } else {
        form.post(route('admin.products.store'));
    }
}
</script>

<template>
    <AppLayout>
        <Head :title="isEdit ? 'Редактировать товар' : 'Новый товар'" />

        <div class="px-3 py-6 sm:px-6">
            <h1 class="mb-6 text-2xl font-bold text-gray-800">
                {{ isEdit ? 'Редактировать товар' : 'Новый товар' }}
            </h1>

            <form class="max-w-2xl rounded-xl bg-white p-6 shadow-sm" @submit.prevent="submit">
                <!-- Name -->
                <div class="mb-4">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Название</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full rounded-lg border px-3 py-2 text-sm focus:border-pink-400 focus:outline-none"
                        :class="{ 'border-red-400': form.errors.name }"
                    />
                    <p v-if="form.errors.name" class="mt-1 text-xs text-red-500">{{ form.errors.name }}</p>
                </div>

                <!-- Category -->
                <div class="mb-4">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Категория</label>
                    <select
                        v-model="form.category_id"
                        class="w-full rounded-lg border px-3 py-2 text-sm focus:border-pink-400 focus:outline-none"
                        :class="{ 'border-red-400': form.errors.category_id }"
                    >
                        <option value="">Выберите категорию</option>
                        <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                    </select>
                    <p v-if="form.errors.category_id" class="mt-1 text-xs text-red-500">{{ form.errors.category_id }}</p>
                </div>

                <div v-if="selectedCategory" class="grid grid-cols-3 gap-4 mb-4">
                    <!-- Brand -->
                    <div v-if="selectedCategory.brands.length">
                        <label class="mb-1 block text-sm font-medium text-gray-700">Бренд</label>
                        <select v-model="form.brand_id" class="w-full rounded-lg border px-3 py-2 text-sm focus:border-pink-400 focus:outline-none">
                            <option value="">—</option>
                            <option v-for="b in selectedCategory.brands" :key="b.id" :value="b.id">{{ b.name }}</option>
                        </select>
                    </div>

                    <!-- Size -->
                    <div v-if="selectedCategory.sizes.length">
                        <label class="mb-1 block text-sm font-medium text-gray-700">Размер</label>
                        <select v-model="form.size_id" class="w-full rounded-lg border px-3 py-2 text-sm focus:border-pink-400 focus:outline-none">
                            <option value="">—</option>
                            <option v-for="s in selectedCategory.sizes" :key="s.id" :value="s.id">{{ s.name }}</option>
                        </select>
                    </div>

                    <!-- Age group -->
                    <div v-if="selectedCategory.age_groups.length">
                        <label class="mb-1 block text-sm font-medium text-gray-700">Возраст</label>
                        <select v-model="form.age_group_id" class="w-full rounded-lg border px-3 py-2 text-sm focus:border-pink-400 focus:outline-none">
                            <option value="">—</option>
                            <option v-for="a in selectedCategory.age_groups" :key="a.id" :value="a.id">{{ a.name }}</option>
                        </select>
                    </div>
                </div>

                <!-- Price -->
                <div class="mb-4">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Цена (сом)</label>
                    <input
                        v-model="form.price"
                        type="number"
                        step="0.01"
                        min="0"
                        class="w-full rounded-lg border px-3 py-2 text-sm focus:border-pink-400 focus:outline-none"
                        :class="{ 'border-red-400': form.errors.price }"
                    />
                    <p v-if="form.errors.price" class="mt-1 text-xs text-red-500">{{ form.errors.price }}</p>
                </div>

                <!-- Image upload -->
                <div class="mb-6">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Изображение</label>
                    <div
                        class="relative flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-200 p-6 hover:border-pink-400 transition"
                        @click="($refs.fileInput as HTMLInputElement).click()"
                    >
                        <img v-if="preview" :src="preview" class="mb-3 h-32 w-32 rounded-lg object-cover" />
                        <span class="text-sm text-gray-400">{{ form.image ? form.image.name : 'Нажмите для выбора файла' }}</span>
                        <span class="mt-1 text-xs text-gray-300">PNG, JPG до 4MB</span>
                        <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="onFileChange" />
                    </div>
                    <p v-if="form.errors.image" class="mt-1 text-xs text-red-500">{{ form.errors.image }}</p>
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-lg bg-pink-500 px-6 py-2 font-semibold text-white hover:bg-pink-600 disabled:opacity-50"
                    >
                        {{ isEdit ? 'Сохранить' : 'Создать' }}
                    </button>
                    <a
                        :href="from_category ? route('admin.categories.show', from_category) : route('admin.products.index')"
                        class="rounded-lg border px-6 py-2 text-sm text-gray-600 hover:bg-gray-50"
                    >
                        Отмена
                    </a>
                </div>
            </form>
        </div>
    </AppLayout>
</template>