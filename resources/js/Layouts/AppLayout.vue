<script setup>
import { usePage, Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

const page = usePage()
const show = ref(false)
const mobileMenuOpen = ref(false)

watch(
    () => page.props.flash?.success,
    (val) => {
        if (val) {
            show.value = true
            setTimeout(() => (show.value = false), 3000)
        }
    },
    { immediate: true }
)

const logout = () => {
    router.post('/logout')
}
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex flex-col">
        <!-- FLASH УВЕДОМЛЕНИЯ -->
        <Transition
            enter-active-class="transform ease-out duration-300 transition"
            enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
            leave-active-class="transition ease-in duration-100"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="show && page.props.flash?.success"
                 class="fixed top-5 right-5 z-50 bg-slate-900 text-white px-6 py-3 rounded-2xl shadow-2xl flex items-center gap-3 border border-slate-700">
                <span class="text-green-400 font-bold">OK</span>
                {{ page.props.flash.success }}
            </div>
        </Transition>

        <!-- HEADER -->
        <header class="bg-white border-b sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
                <Link href="/" class="font-black text-2xl tracking-tighter text-slate-800">
                    Auto<span class="text-blue-600">Parts</span>
                </Link>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex items-center gap-6">
                    <Link href="/" class="font-medium text-slate-600 hover:text-blue-600 transition-colors">
                        Каталог
                    </Link>

                    <template v-if="page.props.auth?.user">
                        <Link href="/orders" class="font-medium text-slate-600 hover:text-blue-600 transition-colors">
                            Мои заказы
                        </Link>

                        <template v-if="page.props.auth.user.role === 'admin'">
                            <Link href="/admin/orders" class="font-medium text-orange-600 hover:text-orange-800 transition-colors">
                                Заказы (админ)
                            </Link>
                            <Link href="/admin/products" class="font-medium text-orange-600 hover:text-orange-800 transition-colors">
                                Товары (админ)
                            </Link>
                        </template>
                    </template>

                    <Link href="/cart" class="relative group flex items-center gap-1">
                        <span class="text-xl">🛒</span>
                        <span class="font-medium text-slate-600 group-hover:text-blue-600 transition-colors">Корзина</span>
                        <span v-if="page.props.cartCount > 0"
                              class="absolute -top-2 -right-4 bg-blue-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white">
                            {{ page.props.cartCount }}
                        </span>
                    </Link>

                    <template v-if="page.props.auth?.user">
                        <div class="flex items-center gap-3 ml-2 pl-4 border-l border-gray-200">
                            <Link href="/profile" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">
                                {{ page.props.auth.user.name }}
                            </Link>
                            <button
                                @click="logout"
                                class="text-sm text-red-500 hover:text-red-700 font-medium transition-colors"
                            >
                                Выйти
                            </button>
                        </div>
                    </template>
                    <template v-else>
                        <div class="flex items-center gap-3 ml-2 pl-4 border-l border-gray-200">
                            <Link href="/login" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">
                                Войти
                            </Link>
                            <Link href="/register" class="text-sm bg-slate-900 text-white px-4 py-2 rounded-xl font-bold hover:bg-blue-600 transition-colors">
                                Регистрация
                            </Link>
                        </div>
                    </template>
                </nav>

                <!-- Mobile menu button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Nav -->
            <div v-if="mobileMenuOpen" class="md:hidden bg-white border-t px-6 py-4 space-y-3">
                <Link href="/" class="block font-medium text-slate-600">Каталог</Link>
                <Link href="/cart" class="block font-medium text-slate-600">
                    Корзина
                    <span v-if="page.props.cartCount > 0" class="ml-1 bg-blue-600 text-white text-xs px-2 py-0.5 rounded-full">{{ page.props.cartCount }}</span>
                </Link>

                <template v-if="page.props.auth?.user">
                    <Link href="/orders" class="block font-medium text-slate-600">Мои заказы</Link>
                    <Link v-if="page.props.auth.user.role === 'admin'" href="/admin/orders" class="block font-medium text-orange-600">Заказы (админ)</Link>
                    <Link v-if="page.props.auth.user.role === 'admin'" href="/admin/products" class="block font-medium text-orange-600">Товары (админ)</Link>
                    <Link href="/profile" class="block font-medium text-slate-600">Профиль</Link>
                    <button @click="logout" class="block text-red-500 font-medium">Выйти</button>
                </template>
                <template v-else>
                    <Link href="/login" class="block font-medium text-slate-600">Войти</Link>
                    <Link href="/register" class="block font-medium text-blue-600">Регистрация</Link>
                </template>
            </div>
        </header>

        <!-- MAIN -->
        <main class="flex-grow py-6">
            <slot />
        </main>

        <!-- FOOTER -->
        <footer class="bg-white border-t mt-auto">
            <div class="max-w-7xl mx-auto px-6 py-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <span class="font-black text-xl tracking-tighter text-slate-800">
                            Auto<span class="text-blue-600">Parts</span>
                        </span>
                        <p class="text-gray-500 text-sm mt-2">
                            Интернет-магазин автозапчастей.
                            Качественные запчасти для вашего автомобиля.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 mb-3">Навигация</h4>
                        <div class="space-y-2">
                            <Link href="/" class="block text-gray-500 hover:text-blue-600 text-sm transition-colors">Каталог</Link>
                            <Link href="/cart" class="block text-gray-500 hover:text-blue-600 text-sm transition-colors">Корзина</Link>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 mb-3">Контакты</h4>
                        <p class="text-gray-500 text-sm">Телефон: +996 XXX XXX XXX</p>
                        <p class="text-gray-500 text-sm">Email: info@autoparts.kg</p>
                    </div>
                </div>
                <div class="border-t border-gray-100 mt-6 pt-6 text-center text-sm text-gray-400">
                    &copy; {{ new Date().getFullYear() }} AutoParts. Все права защищены.
                </div>
            </div>
        </footer>
    </div>
</template>
