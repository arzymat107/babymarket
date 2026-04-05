<script setup lang="ts">
import StoreLayout from '@/layouts/StoreLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Clock, Instagram, MapPin, Phone } from 'lucide-vue-next';

const PHONE     = '+996 703 967 596';
const PHONE_RAW = '+996703967596';
const WHATSAPP  = 'https://wa.me/996703967596';
const INSTAGRAM = 'https://instagram.com/mkar2504';
</script>

<template>
    <StoreLayout>
        <Head title="Контакты" />

        <div class="mx-auto max-w-4xl px-4 py-10">
            <h1 class="mb-2 text-3xl font-bold text-gray-800">Контакты</h1>
            <p class="mb-8 text-gray-500">Свяжитесь с нами любым удобным способом</p>

            <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-4 mb-10">
                <!-- Phone -->
                <a
                    :href="`tel:${PHONE_RAW}`"
                    class="group flex flex-col items-center rounded-xl bg-white p-6 shadow-sm text-center hover:shadow-md transition"
                >
                    <div class="mb-3 rounded-full bg-pink-100 p-4 group-hover:bg-pink-500 transition">
                        <Phone class="h-6 w-6 text-pink-500 group-hover:text-white transition" />
                    </div>
                    <p class="font-semibold text-gray-800">Телефон</p>
                    <p class="mt-1 text-sm text-pink-500">{{ PHONE }}</p>
                </a>

                <!-- WhatsApp -->
                <a
                    :href="WHATSAPP"
                    target="_blank"
                    class="group flex flex-col items-center rounded-xl bg-white p-6 shadow-sm text-center hover:shadow-md transition"
                >
                    <div class="mb-3 rounded-full bg-green-100 p-4 group-hover:bg-green-500 transition">
                        <svg class="h-6 w-6 text-green-500 group-hover:text-white transition" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-800">WhatsApp</p>
                    <p class="mt-1 text-sm text-green-500">Написать нам</p>
                </a>

                <!-- Instagram -->
                <a
                    :href="INSTAGRAM"
                    target="_blank"
                    class="group flex flex-col items-center rounded-xl bg-white p-6 shadow-sm text-center hover:shadow-md transition"
                >
                    <div class="mb-3 rounded-full bg-purple-100 p-4 group-hover:bg-purple-500 transition">
                        <Instagram class="h-6 w-6 text-purple-500 group-hover:text-white transition" />
                    </div>
                    <p class="font-semibold text-gray-800">Instagram</p>
                    <p class="mt-1 text-sm text-purple-500">@mkar2504</p>
                </a>

                <!-- Address -->
                <div class="flex flex-col items-center rounded-xl bg-white p-6 shadow-sm text-center">
                    <div class="mb-3 rounded-full bg-blue-100 p-4">
                        <MapPin class="h-6 w-6 text-blue-500" />
                    </div>
                    <p class="font-semibold text-gray-800">Адрес</p>
                    <p class="mt-1 text-sm text-gray-500">г. Бишкек, ул. Манаса 25</p>
                </div>
            </div>

            <!-- Working hours + details -->
            <div class="grid gap-6 md:grid-cols-2">
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="rounded-full bg-pink-100 p-2"><Clock class="h-5 w-5 text-pink-500" /></div>
                        <h2 class="text-lg font-semibold text-gray-800">Режим работы</h2>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex justify-between">
                            <span>Понедельник — пятница</span>
                            <span class="font-medium">9:00 — 20:00</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Суббота</span>
                            <span class="font-medium">10:00 — 18:00</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Воскресенье</span>
                            <span class="font-medium text-red-400">Выходной</span>
                        </li>
                    </ul>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-800">Напишите нам</h2>
                    <p class="mb-4 text-sm text-gray-500">Мы отвечаем на WhatsApp и Instagram в течение нескольких минут.</p>
                    <div class="flex flex-col gap-3">
                        <a
                            :href="WHATSAPP"
                            target="_blank"
                            class="flex items-center justify-center gap-2 rounded-lg bg-green-500 py-2.5 text-sm font-semibold text-white hover:bg-green-600"
                        >
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            Написать в WhatsApp
                        </a>
                        <a
                            :href="INSTAGRAM"
                            target="_blank"
                            class="flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-purple-500 to-pink-500 py-2.5 text-sm font-semibold text-white hover:opacity-90"
                        >
                            <Instagram class="h-4 w-4" />
                            Написать в Instagram
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>