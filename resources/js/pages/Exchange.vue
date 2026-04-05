<script setup lang="ts">
import StoreLayout from '@/layouts/StoreLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { AlertCircle, CheckCircle, RefreshCw, XCircle } from 'lucide-vue-next';
</script>

<template>
    <StoreLayout>
        <Head title="Обмен и возврат" />

        <div class="mx-auto max-w-4xl px-4 py-10">
            <h1 class="mb-2 text-3xl font-bold text-gray-800">Обмен и возврат</h1>
            <p class="mb-8 text-gray-500">Мы хотим, чтобы вы были довольны каждой покупкой</p>

            <div class="space-y-6">
                <!-- Return period -->
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="rounded-full bg-pink-100 p-2"><RefreshCw class="h-5 w-5 text-pink-500" /></div>
                        <h2 class="text-lg font-semibold text-gray-800">Срок возврата</h2>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Вы можете вернуть или обменять товар в течение <strong>14 календарных дней</strong> с момента получения заказа,
                        если товар не подошёл вам по размеру, фасону или иным причинам.
                        Товар должен быть в оригинальной упаковке, с ярлыками, без следов использования.
                    </p>
                </div>

                <!-- Can return -->
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="rounded-full bg-green-100 p-2"><CheckCircle class="h-5 w-5 text-green-500" /></div>
                        <h2 class="text-lg font-semibold text-gray-800">Что можно вернуть</h2>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex gap-2"><CheckCircle class="mt-0.5 h-4 w-4 flex-shrink-0 text-green-500" /> Одежда и обувь (не надевавшаяся, с ярлыками)</li>
                        <li class="flex gap-2"><CheckCircle class="mt-0.5 h-4 w-4 flex-shrink-0 text-green-500" /> Игрушки в нераскрытой оригинальной упаковке</li>
                        <li class="flex gap-2"><CheckCircle class="mt-0.5 h-4 w-4 flex-shrink-0 text-green-500" /> Коляски и аксессуары (в полной комплектации, без повреждений)</li>
                        <li class="flex gap-2"><CheckCircle class="mt-0.5 h-4 w-4 flex-shrink-0 text-green-500" /> Товары ненадлежащего качества (брак) — в любое время</li>
                    </ul>
                </div>

                <!-- Cannot return -->
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="rounded-full bg-red-100 p-2"><XCircle class="h-5 w-5 text-red-500" /></div>
                        <h2 class="text-lg font-semibold text-gray-800">Что нельзя вернуть</h2>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex gap-2"><XCircle class="mt-0.5 h-4 w-4 flex-shrink-0 text-red-400" /> Детское питание, смеси, пюре (продукты питания)</li>
                        <li class="flex gap-2"><XCircle class="mt-0.5 h-4 w-4 flex-shrink-0 text-red-400" /> Подгузники и средства гигиены (после вскрытия упаковки)</li>
                        <li class="flex gap-2"><XCircle class="mt-0.5 h-4 w-4 flex-shrink-0 text-red-400" /> Товары со следами использования или повреждениями</li>
                        <li class="flex gap-2"><XCircle class="mt-0.5 h-4 w-4 flex-shrink-0 text-red-400" /> Товары без оригинальной упаковки или ярлыков</li>
                    </ul>
                </div>

                <!-- How to return -->
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="rounded-full bg-blue-100 p-2"><AlertCircle class="h-5 w-5 text-blue-500" /></div>
                        <h2 class="text-lg font-semibold text-gray-800">Как оформить возврат</h2>
                    </div>
                    <ol class="space-y-3 text-sm text-gray-600">
                        <li class="flex gap-3">
                            <span class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-pink-500 text-xs font-bold text-white">1</span>
                            Свяжитесь с нами по телефону или через мессенджер и сообщите номер заказа
                        </li>
                        <li class="flex gap-3">
                            <span class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-pink-500 text-xs font-bold text-white">2</span>
                            Менеджер уточнит причину возврата и согласует удобное время для курьера
                        </li>
                        <li class="flex gap-3">
                            <span class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-pink-500 text-xs font-bold text-white">3</span>
                            Упакуйте товар в оригинальную упаковку и передайте курьеру
                        </li>
                        <li class="flex gap-3">
                            <span class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-pink-500 text-xs font-bold text-white">4</span>
                            После проверки товара мы вернём деньги в течение 3 рабочих дней
                        </li>
                    </ol>
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