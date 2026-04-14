<script setup lang="ts">
import { useCart } from '@/composables/useCart';
import { Link } from '@inertiajs/vue3';
import { Instagram, Menu, Phone, ShoppingCart, X } from 'lucide-vue-next';
import { ref } from 'vue';

const PHONE     = '+996 703 967 596';
const PHONE_RAW = '+996703967596';
const WHATSAPP  = 'https://wa.me/996703967596';
const INSTAGRAM = 'https://instagram.com/mkar2504';

const { count, items, total, remove, updateQuantity } = useCart();
const cartOpen = ref(false);
const menuOpen = ref(false);

const navLinks = [
    { label: 'Каталог',        route: 'catalog' },
    { label: 'Доставка',       route: 'delivery' },
    { label: 'Обмен и возврат', route: 'exchange' },
    { label: 'Контакты',       route: 'contacts' },
];
</script>

<template>
    <div class="min-h-screen bg-gray-50">

        <!-- Navbar -->
        <header class="sticky top-0 z-50 bg-white shadow-sm">
            <div class="mx-auto max-w-7xl px-4">
                <div class="flex h-14 items-center justify-between md:h-16">
                    <!-- Logo -->
                    <Link :href="route('home')" class="text-xl font-bold text-pink-500 md:text-2xl">
                        BabyMarket
                    </Link>

                    <!-- Desktop nav -->
                    <nav class="hidden gap-6 md:flex">
                        <Link
                            v-for="link in navLinks"
                            :key="link.route"
                            :href="route(link.route)"
                            class="text-sm font-medium text-gray-700 hover:text-pink-500"
                        >
                            {{ link.label }}
                        </Link>
                    </nav>

                    <!-- Right: cart + hamburger -->
                    <div class="flex items-center gap-1">
                        <button class="relative p-2 text-gray-700 hover:text-pink-500" @click="cartOpen = !cartOpen">
                            <ShoppingCart class="h-6 w-6" />
                            <span
                                v-if="count > 0"
                                class="absolute -right-0.5 -top-0.5 flex h-5 w-5 items-center justify-center rounded-full bg-pink-500 text-xs font-bold text-white"
                            >
                                {{ count }}
                            </span>
                        </button>
                        <!-- Hamburger (mobile only) -->
                        <button class="p-2 text-gray-700 hover:text-pink-500 md:hidden" @click="menuOpen = !menuOpen">
                            <X v-if="menuOpen" class="h-6 w-6" />
                            <Menu v-else class="h-6 w-6" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile nav dropdown -->
            <Transition name="menu">
                <div v-if="menuOpen" class="border-t bg-white md:hidden">
                    <nav class="flex flex-col divide-y">
                        <Link
                            v-for="link in navLinks"
                            :key="link.route"
                            :href="route(link.route)"
                            class="px-4 py-3.5 text-sm font-medium text-gray-700 hover:bg-pink-50 hover:text-pink-500"
                            @click="menuOpen = false"
                        >
                            {{ link.label }}
                        </Link>
                    </nav>
                </div>
            </Transition>
        </header>

        <!-- Cart sidebar -->
        <Transition name="slide">
            <div v-if="cartOpen" class="fixed inset-0 z-50 flex justify-end">
                <div class="absolute inset-0 bg-black/40" @click="cartOpen = false" />
                <div class="relative flex h-full w-full max-w-sm flex-col bg-white shadow-xl">
                    <div class="flex items-center justify-between border-b p-4">
                        <h2 class="text-lg font-semibold">Корзина</h2>
                        <button class="text-gray-400 hover:text-gray-600" @click="cartOpen = false">
                            <X class="h-5 w-5" />
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4">
                        <p v-if="items.length === 0" class="py-10 text-center text-gray-400">Корзина пуста</p>
                        <div v-for="item in items" :key="item.id" class="mb-4 flex gap-3">
                            <div class="h-16 w-16 flex-shrink-0 rounded-lg bg-gray-100 overflow-hidden">
                                <img
                                    v-if="item.image_url"
                                    :src="item.image_url"
                                    :alt="item.name"
                                    class="h-full w-full object-cover"
                                />
                                <div v-else class="flex h-full items-center justify-center text-2xl">🧸</div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium leading-snug line-clamp-2">{{ item.name }}</p>
                                <p class="mt-1 text-sm text-pink-600 font-semibold">
                                    {{ (item.price * item.quantity).toLocaleString() }} сом
                                </p>
                                <div class="mt-1.5 flex items-center gap-2">
                                    <button
                                        class="flex h-7 w-7 items-center justify-center rounded border text-sm font-medium hover:bg-gray-50"
                                        @click="updateQuantity(item.id, item.quantity - 1)"
                                    >−</button>
                                    <span class="w-6 text-center text-sm">{{ item.quantity }}</span>
                                    <button
                                        class="flex h-7 w-7 items-center justify-center rounded border text-sm font-medium hover:bg-gray-50"
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

        <!-- Floating contact widget -->
        <div class="fixed bottom-6 right-4 z-40 flex flex-col items-end gap-3">
            <!-- WhatsApp -->
            <div class="group flex items-center gap-3">
                <span class="hidden rounded-lg bg-white px-3 py-1.5 text-sm font-medium text-gray-700 shadow-md group-hover:block">
                    Отправить сообщение
                </span>
                <a
                    :href="WHATSAPP"
                    target="_blank"
                    class="flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] shadow-lg transition hover:scale-110"
                    aria-label="WhatsApp"
                >
                    <svg class="h-7 w-7 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </a>
            </div>

            <!-- Phone -->
            <div class="group flex items-center gap-3">
                <span class="hidden rounded-lg bg-white px-3 py-1.5 text-sm font-medium text-gray-700 shadow-md group-hover:block">
                    Позвонить нам
                </span>
                <a
                    :href="`tel:${PHONE_RAW}`"
                    class="flex h-14 w-14 items-center justify-center rounded-full bg-pink-500 shadow-lg transition hover:scale-110"
                    aria-label="Позвонить"
                >
                    <Phone class="h-6 w-6 animate-ring text-white" />
                </a>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-12 border-t bg-white md:mt-16">
            <div class="mx-auto max-w-7xl px-4 py-8 md:py-10">
                <div class="grid gap-6 sm:grid-cols-3">
                    <div>
                        <p class="mb-2 text-xl font-bold text-pink-500">BabyMarket</p>
                        <p class="text-sm text-gray-400">Всё для вашего малыша — питание, одежда, игрушки и многое другое.</p>
                    </div>
                    <div>
                        <p class="mb-3 font-semibold text-gray-700">Информация</p>
                        <ul class="space-y-2 text-sm text-gray-500">
                            <li><Link :href="route('catalog')" class="hover:text-pink-500">Каталог</Link></li>
                            <li><Link :href="route('delivery')" class="hover:text-pink-500">Доставка</Link></li>
                            <li><Link :href="route('exchange')" class="hover:text-pink-500">Обмен и возврат</Link></li>
                            <li><Link :href="route('contacts')" class="hover:text-pink-500">Контакты</Link></li>
                        </ul>
                    </div>
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
.slide-leave-to .relative { transform: translateX(100%); }
.slide-enter-active .relative,
.slide-leave-active .relative { transition: transform 0.3s ease; }

.menu-enter-from,
.menu-leave-to { opacity: 0; transform: translateY(-8px); }
.menu-enter-active,
.menu-leave-active { transition: opacity 0.2s ease, transform 0.2s ease; }

@keyframes ring {
    0%, 100% { transform: rotate(0deg); }
    10%       { transform: rotate(-15deg); }
    20%       { transform: rotate(15deg); }
    30%       { transform: rotate(-10deg); }
    40%       { transform: rotate(10deg); }
    50%       { transform: rotate(0deg); }
}
.animate-ring {
    animation: ring 2.5s ease-in-out infinite;
    transform-origin: top center;
}
</style>