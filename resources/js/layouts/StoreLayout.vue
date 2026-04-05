<script setup lang="ts">
import { useCart } from '@/composables/useCart';
import { Link } from '@inertiajs/vue3';
import { Instagram, Phone, ShoppingCart } from 'lucide-vue-next';
import { ref } from 'vue';

const PHONE     = '+996 703 967 596';
const PHONE_RAW = '+996703967596';
const WHATSAPP  = 'https://wa.me/996703967596';
const INSTAGRAM = 'https://instagram.com/mkar2504';

const { count, items, total, remove } = useCart();
const cartOpen = ref(false);
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Top bar -->
        <div class="bg-pink-500 py-1.5 text-center text-xs text-white">
            <div class="mx-auto flex max-w-7xl items-center justify-center gap-6 px-4">
                <a :href="`tel:${PHONE_RAW}`" class="flex items-center gap-1 hover:underline">
                    <Phone class="h-3 w-3" /> {{ PHONE }}
                </a>
                <a :href="WHATSAPP" target="_blank" class="hover:underline">WhatsApp</a>
                <a :href="INSTAGRAM" target="_blank" class="flex items-center gap-1 hover:underline">
                    <Instagram class="h-3 w-3" /> Instagram
                </a>
            </div>
        </div>

        <!-- Navbar -->
        <header class="sticky top-0 z-50 bg-white shadow-sm">
            <div class="mx-auto max-w-7xl px-4">
                <div class="flex h-16 items-center justify-between">
                    <!-- Logo -->
                    <Link :href="route('home')" class="text-2xl font-bold text-pink-500">
                        BabyMarket
                    </Link>

                    <!-- Nav links -->
                    <nav class="hidden gap-6 md:flex">
                        <Link :href="route('catalog')" class="text-sm font-medium text-gray-700 hover:text-pink-500">Каталог</Link>
                        <Link :href="route('delivery')" class="text-sm font-medium text-gray-700 hover:text-pink-500">Доставка</Link>
                        <Link :href="route('exchange')" class="text-sm font-medium text-gray-700 hover:text-pink-500">Обмен и возврат</Link>
                        <Link :href="route('contacts')" class="text-sm font-medium text-gray-700 hover:text-pink-500">Контакты</Link>
                    </nav>

                    <!-- Cart -->
                    <button class="relative p-2 text-gray-700 hover:text-pink-500" @click="cartOpen = !cartOpen">
                        <ShoppingCart class="h-6 w-6" />
                        <span
                            v-if="count > 0"
                            class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-pink-500 text-xs font-bold text-white"
                        >
                            {{ count }}
                        </span>
                    </button>
                </div>
            </div>
        </header>

        <!-- Cart sidebar -->
        <Transition name="slide">
            <div v-if="cartOpen" class="fixed inset-0 z-50 flex justify-end">
                <div class="absolute inset-0 bg-black/40" @click="cartOpen = false" />
                <div class="relative flex h-full w-full max-w-sm flex-col bg-white shadow-xl">
                    <div class="flex items-center justify-between border-b p-4">
                        <h2 class="text-lg font-semibold">Корзина</h2>
                        <button class="text-gray-400 hover:text-gray-600" @click="cartOpen = false">✕</button>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4">
                        <p v-if="items.length === 0" class="py-10 text-center text-gray-400">Корзина пуста</p>
                        <div v-for="item in items" :key="item.id" class="mb-4 flex gap-3">
                            <div class="h-16 w-16 flex-shrink-0 rounded-lg bg-gray-100">
                                <img
                                    v-if="item.image_url"
                                    :src="item.image_url"
                                    :alt="item.name"
                                    class="h-full w-full rounded-lg object-cover"
                                />
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium leading-snug">{{ item.name }}</p>
                                <p class="mt-1 text-sm text-pink-600 font-semibold">
                                    {{ (item.price * item.quantity).toLocaleString() }} сом
                                </p>
                                <div class="mt-1 flex items-center gap-2">
                                    <button
                                        class="flex h-6 w-6 items-center justify-center rounded border text-xs"
                                        @click="updateQuantity(item.id, item.quantity - 1)"
                                    >−</button>
                                    <span class="text-sm">{{ item.quantity }}</span>
                                    <button
                                        class="flex h-6 w-6 items-center justify-center rounded border text-xs"
                                        @click="updateQuantity(item.id, item.quantity + 1)"
                                    >+</button>
                                    <button class="ml-auto text-xs text-red-400 hover:text-red-600" @click="remove(item.id)">
                                        Удалить
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="items.length > 0" class="border-t p-4">
                        <div class="mb-3 flex items-center justify-between">
                            <span class="font-medium">Итого:</span>
                            <span class="text-lg font-bold text-pink-600">{{ total.toLocaleString() }} сом</span>
                        </div>
                        <Link
                            :href="route('checkout')"
                            class="block w-full rounded-lg bg-pink-500 py-3 text-center text-sm font-semibold text-white hover:bg-pink-600"
                            @click="cartOpen = false"
                        >
                            Оформить заказ
                        </Link>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- Page content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="mt-16 border-t bg-white">
            <div class="mx-auto max-w-7xl px-4 py-10">
                <div class="grid gap-8 sm:grid-cols-3">
                    <!-- Brand -->
                    <div>
                        <p class="mb-2 text-xl font-bold text-pink-500">BabyMarket</p>
                        <p class="text-sm text-gray-400">Всё для вашего малыша — питание, одежда, игрушки и многое другое.</p>
                    </div>

                    <!-- Nav -->
                    <div>
                        <p class="mb-3 font-semibold text-gray-700">Информация</p>
                        <ul class="space-y-2 text-sm text-gray-500">
                            <li><Link :href="route('catalog')" class="hover:text-pink-500">Каталог</Link></li>
                            <li><Link :href="route('delivery')" class="hover:text-pink-500">Доставка</Link></li>
                            <li><Link :href="route('exchange')" class="hover:text-pink-500">Обмен и возврат</Link></li>
                            <li><Link :href="route('contacts')" class="hover:text-pink-500">Контакты</Link></li>
                        </ul>
                    </div>

                    <!-- Contacts -->
                    <div>
                        <p class="mb-3 font-semibold text-gray-700">Контакты</p>
                        <ul class="space-y-2 text-sm text-gray-500">
                            <li>
                                <a :href="`tel:${PHONE_RAW}`" class="flex items-center gap-2 hover:text-pink-500">
                                    <Phone class="h-4 w-4" /> {{ PHONE }}
                                </a>
                            </li>
                            <li>
                                <a :href="WHATSAPP" target="_blank" class="flex items-center gap-2 hover:text-pink-500">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    WhatsApp
                                </a>
                            </li>
                            <li>
                                <a :href="INSTAGRAM" target="_blank" class="flex items-center gap-2 hover:text-pink-500">
                                    <Instagram class="h-4 w-4" /> @mkar2504
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8 border-t pt-6 text-center text-xs text-gray-400">
                    © {{ new Date().getFullYear() }} BabyMarket — всё для малышей
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.slide-enter-from .relative,
.slide-leave-to .relative {
    transform: translateX(100%);
}
.slide-enter-active .relative,
.slide-leave-active .relative {
    transition: transform 0.3s ease;
}
</style>