<script setup lang="ts">
import StoreLayout from '@/layouts/StoreLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { CheckCircle, Clock, MapPin, Truck } from 'lucide-vue-next';
</script>

<template>
    <StoreLayout>
        <Head title="Доставка" />

        <div class="mx-auto max-w-4xl px-4 py-10">
            <h1 class="mb-2 text-3xl font-bold text-gray-800">Доставка</h1>
            <p class="mb-8 text-gray-500">Мы доставляем заказы по всему Кыргызстану</p>

            <!-- Cards -->
            <div class="mb-10 grid gap-4 sm:grid-cols-2 md:grid-cols-4">
                <div class="rounded-xl bg-white p-5 shadow-sm text-center">
                    <div class="mb-3 flex justify-center">
                        <div class="rounded-full bg-pink-100 p-3"><Truck class="h-6 w-6 text-pink-500" /></div>
                    </div>
                    <p class="font-semibold text-gray-800">Курьером</p>
                    <p class="mt-1 text-sm text-gray-500">По Бишкеку и регионам</p>
                </div>
                <div class="rounded-xl bg-white p-5 shadow-sm text-center">
                    <div class="mb-3 flex justify-center">
                        <div class="rounded-full bg-pink-100 p-3"><Clock class="h-6 w-6 text-pink-500" /></div>
                    </div>
                    <p class="font-semibold text-gray-800">1–2 дня</p>
                    <p class="mt-1 text-sm text-gray-500">По Бишкеку</p>
                </div>
                <div class="rounded-xl bg-white p-5 shadow-sm text-center">
                    <div class="mb-3 flex justify-center">
                        <div class="rounded-full bg-pink-100 p-3"><MapPin class="h-6 w-6 text-pink-500" /></div>
                    </div>
                    <p class="font-semibold text-gray-800">3–7 дней</p>
                    <p class="mt-1 text-sm text-gray-500">По регионам КР</p>
                </div>
                <div class="rounded-xl bg-white p-5 shadow-sm text-center">
                    <div class="mb-3 flex justify-center">
                        <div class="rounded-full bg-green-100 p-3"><CheckCircle class="h-6 w-6 text-green-500" /></div>
                    </div>
                    <p class="font-semibold text-gray-800">Бесплатно</p>
                    <p class="mt-1 text-sm text-gray-500">При заказе от 5 000 сом</p>
                </div>
            </div>

            <!-- Details -->
            <div class="space-y-6">
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-800">Стоимость доставки</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b text-left text-gray-400 text-xs uppercase">
                                    <th class="pb-2 pr-4">Зона</th>
                                    <th class="pb-2 pr-4">Срок</th>
                                    <th class="pb-2">Стоимость</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y text-gray-700">
                                <tr>
                                    <td class="py-3 pr-4 font-medium">Бишкек (в пределах города)</td>
                                    <td class="py-3 pr-4">1–2 рабочих дня</td>
                                    <td class="py-3 font-semibold text-pink-600">200 сом</td>
                                </tr>
                                <tr>
                                    <td class="py-3 pr-4 font-medium">Чуйская область</td>
                                    <td class="py-3 pr-4">2–3 рабочих дня</td>
                                    <td class="py-3 font-semibold text-pink-600">350 сом</td>
                                </tr>
                                <tr>
                                    <td class="py-3 pr-4 font-medium">Ош, Джалал-Абад, Баткен</td>
                                    <td class="py-3 pr-4">3–5 рабочих дней</td>
                                    <td class="py-3 font-semibold text-pink-600">500 сом</td>
                                </tr>
                                <tr>
                                    <td class="py-3 pr-4 font-medium">Иссык-Куль, Нарын, Талас</td>
                                    <td class="py-3 pr-4">5–7 рабочих дней</td>
                                    <td class="py-3 font-semibold text-pink-600">600 сом</td>
                                </tr>
                                <tr class="bg-green-50">
                                    <td class="py-3 pr-4 font-medium text-green-700">Любой регион (заказ от 5 000 сом)</td>
                                    <td class="py-3 pr-4 text-green-700">По расписанию</td>
                                    <td class="py-3 font-bold text-green-600">Бесплатно</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-800">Условия доставки</h2>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li class="flex gap-2"><CheckCircle class="mt-0.5 h-4 w-4 flex-shrink-0 text-green-500" /> Заказы оформляются ежедневно с 9:00 до 20:00</li>
                        <li class="flex gap-2"><CheckCircle class="mt-0.5 h-4 w-4 flex-shrink-0 text-green-500" /> Заказы, оформленные до 14:00, доставляются в тот же день (по Бишкеку)</li>
                        <li class="flex gap-2"><CheckCircle class="mt-0.5 h-4 w-4 flex-shrink-0 text-green-500" /> Доставка осуществляется с 10:00 до 21:00</li>
                        <li class="flex gap-2"><CheckCircle class="mt-0.5 h-4 w-4 flex-shrink-0 text-green-500" /> Перед доставкой наш менеджер свяжется с вами для подтверждения времени</li>
                        <li class="flex gap-2"><CheckCircle class="mt-0.5 h-4 w-4 flex-shrink-0 text-green-500" /> Оплата при получении — наличными или картой</li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 text-center">
                <Link :href="route('catalog')" class="inline-block rounded-full bg-pink-500 px-8 py-3 font-semibold text-white hover:bg-pink-600">
                    Перейти в каталог
                </Link>
            </div>
        </div>
    </StoreLayout>
</template>