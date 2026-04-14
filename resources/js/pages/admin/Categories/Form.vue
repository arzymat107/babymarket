<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface Category {
    id: number;
    name: string;
    image_url: string | null;
}

const props = defineProps<{ category?: Category }>();

const isEdit = computed(() => !!props.category);

const form = useForm({
    name:  props.category?.name  ?? '',
    image: null as File | null,
});

const preview = ref<string | null>(props.category?.image_url ?? null);

function onFileChange(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0] ?? null;
    form.image    = file;
    preview.value = file ? URL.createObjectURL(file) : (props.category?.image_url ?? null);
}

function submit() {
    if (isEdit.value && props.category) {
        form.transform((data) => ({ ...data, _method: 'PUT' }))
            .post(route('admin.categories.update', props.category.id));
    } else {
        form.post(route('admin.categories.store'));
    }
}
</script>

<template>
    <AppLayout>
        <Head :title="isEdit ? 'Редактировать категорию' : 'Новая категория'" />

        <div class="px-3 py-6 sm:px-6">
            <h1 class="mb-6 text-2xl font-bold text-gray-800">
                {{ isEdit ? 'Редактировать категорию' : 'Новая категория' }}
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

                <!-- Image upload -->
                <div class="mb-6">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Изображение</label>
                    <div
                        class="relative flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-200 p-6 hover:border-pink-400 transition"
                        @click="($refs.fileInput as HTMLInputElement).click()"
                    >
                        <img v-if="preview" :src="preview" class="mb-3 h-32 w-32 rounded-lg object-cover" />
                        <span class="text-sm text-gray-400">{{ form.image ? form.image.name : 'Нажмите для выбора файла' }}</span>
                        <span class="mt-1 text-xs text-gray-300">PNG, JPG до 2MB</span>
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
                    <a :href="route('admin.categories.index')" class="rounded-lg border px-6 py-2 text-sm text-gray-600 hover:bg-gray-50">
                        Отмена
                    </a>
                </div>
            </form>
        </div>
    </AppLayout>
</template>