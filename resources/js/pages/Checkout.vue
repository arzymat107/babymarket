<script setup lang="ts">
import { useCart } from '@/composables/useCart';
import StoreLayout from '@/layouts/StoreLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { RefreshCw, Truck } from 'lucide-vue-next';

const { items, total, clear } = useCart();

const form = useForm({
    customer_name:    '',
    customer_phone:   '',
    shipping_address: '',
    items:            items,
});

const SHIPPING = 200;

function submit() {
    form.items = items.value.map((i) => ({ id: i.id, quantity: i.quantity }));
    form.post(route('orders.store'), {
        onSuccess: () => clear(),
    });
}
</script>

<template>
    <StoreLayout>
        <Head title="Оформление заказа" />

        <div class="mx-auto max-w-4xl px-4 py-10">
            <h1 class="mb-8 text-2xl font-bold text-gray-800">Оформление заказа</h1>

            <div v-if="items.length === 0" class="py-20 text-center text-gray-400">
                Ваша корзина пуста.
                <br />
                <a :href="route('catalog')" class="mt-2 inline-block text-pink-500 hover:underline">Перейти в каталог</a>
            </div>

            <div v-else class="grid gap-8 md:grid-cols-5">
                <!-- Form -->
                <form class="md:col-span-3" @submit.prevent="submit">
                    <div class="rounded-xl bg-white p-6 shadow-sm">
                        <h2 class="mb-4 font-semibold text-gray-700">Контактные данные</h2>

                        <div class="mb-4">
                            <label class="mb-1 block text-sm text-gray-600">Ваше имя</label>
                            <input
                                v-model="form.customer_name"
                                type="text"
                                placeholder="Иван Иванов"
                                class="w-full rounded-lg border px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none"
                                :class="{ 'border-red-400': form.errors.customer_name }"
                            />
                            <p v-if="form.errors.customer_name" class="mt-1 text-xs text-red-500">
                                {{ form.errors.customer_name }}
                            </p>
                        </div>

                        <div class="mb-4">
                            <label class="mb-1 block text-sm text-gray-600">Телефон</label>
                            <input
                                v-model="form.customer_phone"
                                type="tel"
                                placeholder="+996 700 000 000"
                                class="w-full rounded-lg border px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none"
                                :class="{ 'border-red-400': form.errors.customer_phone }"
                            />
                            <p v-if="form.errors.customer_phone" class="mt-1 text-xs text-red-500">
                                {{ form.errors.customer_phone }}
                            </p>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm text-gray-600">Адрес доставки</label>
                            <textarea
                                v-model="form.shipping_address"
                                rows="3"
                                placeholder="г. Бишкек, ул. Манаса 25, кв. 10"
                                class="w-full rounded-lg border px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none"
                                :class="{ 'border-red-400': form.errors.shipping_address }"
                            />
                            <p v-if="form.errors.shipping_address" class="mt-1 text-xs text-red-500">
                                {{ form.errors.shipping_address }}
                            </p>
                        </div>
                    </div>

                    <!-- Info banners -->
                    <div class="mt-4 space-y-2">
                        <Link
                            :href="route('delivery')"
                            class="flex items-center gap-3 rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-700 hover:bg-blue-100 transition"
                        >
                            <Truck class="h-4 w-4 flex-shrink-0" />
                            <span><strong>Доставка</strong> по Бишкеку — 200 сом, от 5 000 сом — бесплатно. Подробнее →</span>
                        </Link>
                        <Link
                            :href="route('exchange')"
                            class="flex items-center gap-3 rounded-lg border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700 hover:bg-green-100 transition"
                        >
                            <RefreshCw class="h-4 w-4 flex-shrink-0" />
                            <span><strong>Обмен и возврат</strong> в течение 14 дней. Подробнее →</span>
                        </Link>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="mt-4 w-full rounded-lg bg-pink-500 py-3 font-semibold text-white hover:bg-pink-600 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Отправляем...' : 'Подтвердить заказ' }}
                    </button>
                </form>

                <!-- Order summary -->
                <div class="md:col-span-2">
                    <div class="rounded-xl bg-white p-6 shadow-sm">
                        <h2 class="mb-4 font-semibold text-gray-700">Ваш заказ</h2>
                        <div v-for="item in items" :key="item.id" class="mb-3 flex justify-between text-sm">
                            <span class="text-gray-700">{{ item.name }} × {{ item.quantity }}</span>
                            <span class="font-medium">{{ (item.price * item.quantity).toLocaleString() }} сом</span>
                        </div>
                        <div class="my-3 border-t pt-3 flex justify-between text-sm text-gray-500">
                            <span>Доставка</span>
                            <span>{{ SHIPPING.toLocaleString() }} сом</span>
                        </div>
                        <div class="flex justify-between font-bold">
                            <span>Итого</span>
                            <span class="text-pink-600">{{ (total + SHIPPING).toLocaleString() }} сом</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </StoreLayout>
</template>