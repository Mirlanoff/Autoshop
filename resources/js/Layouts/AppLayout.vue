<script setup>
import { usePage, Link } from '@inertiajs/vue3' // Добавили Link
import { ref, watch } from 'vue'

const page = usePage()
const show = ref(false)

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
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- УВЕДОМЛЕНИЕ -->
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
                <span class="text-green-400">✔</span>
                {{ page.props.flash.success }}
            </div>
        </Transition>

        <header class="bg-white border-b sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
                <Link href="/" class="font-black text-2xl tracking-tighter text-slate-800">
                    Auto<span class="text-blue-600">Parts</span>
                </Link>

                <div class="flex items-center gap-8">
                    <Link href="/" class="font-medium text-slate-600 hover:text-blue-600 transition-colors">
                        Каталог
                    </Link>
                    <Link
                        v-if="page.props.auth?.user?.role === 'admin'"
                        href="/admin/orders"
                    >
                        Админка
                    </Link>

                    <Link href="/cart" class="relative group">
                        <span class="text-xl">🛒</span>
                        <span class="font-medium text-slate-600 group-hover:text-blue-600 transition-colors ml-1">Корзина</span>
                        <span v-if="page.props.cartCount > 0"
                              class="absolute -top-2 -right-4 bg-blue-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white">
                            {{ page.props.cartCount }}
                        </span>
                    </Link>
                </div>
            </div>
        </header>

        <main class="py-6">
            <div class="max-w-7xl mx-auto">
                <slot />
            </div>
        </main>
    </div>
</template>
