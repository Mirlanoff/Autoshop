<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, usePage } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

defineProps({
    stats: Object,
    recentOrders: Array,
})

const page = usePage()

const statusLabel = (s) => {
    const map = { pending: 'Ожидает', completed: 'Выполнен', cancelled: 'Отменён' }
    return map[s] || s
}
</script>

<template>
    <div class="max-w-5xl mx-auto p-6 font-sans">
        <h1 class="text-3xl font-black text-slate-800 mb-2">
            Привет, {{ page.props.auth?.user?.name }}!
        </h1>
        <p class="text-gray-400 mb-8">Ваш личный кабинет</p>

        <!-- Статистика -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm text-center">
                <p class="text-3xl font-black text-slate-900">{{ stats.orders_count }}</p>
                <p class="text-sm text-gray-400 mt-1">Заказов</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm text-center">
                <p class="text-3xl font-black text-slate-900">{{ stats.wishlist_count }}</p>
                <p class="text-sm text-gray-400 mt-1">В избранном</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm text-center">
                <p class="text-3xl font-black text-slate-900">{{ Number(stats.total_spent).toFixed(2) }} $</p>
                <p class="text-sm text-gray-400 mt-1">Потрачено</p>
            </div>
        </div>

        <!-- Быстрые ссылки -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-10">
            <Link href="/" class="bg-white border border-gray-100 rounded-xl p-4 text-center hover:shadow-md transition-shadow">
                <p class="text-2xl mb-1">&#128269;</p>
                <p class="text-sm font-bold text-slate-700">Каталог</p>
            </Link>
            <Link href="/orders" class="bg-white border border-gray-100 rounded-xl p-4 text-center hover:shadow-md transition-shadow">
                <p class="text-2xl mb-1">&#128230;</p>
                <p class="text-sm font-bold text-slate-700">Мои заказы</p>
            </Link>
            <Link href="/wishlist" class="bg-white border border-gray-100 rounded-xl p-4 text-center hover:shadow-md transition-shadow">
                <p class="text-2xl mb-1">&#9829;</p>
                <p class="text-sm font-bold text-slate-700">Избранное</p>
            </Link>
            <Link href="/profile" class="bg-white border border-gray-100 rounded-xl p-4 text-center hover:shadow-md transition-shadow">
                <p class="text-2xl mb-1">&#9881;</p>
                <p class="text-sm font-bold text-slate-700">Профиль</p>
            </Link>
        </div>

        <!-- Последние заказы -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-slate-800">Последние заказы</h2>
                <Link href="/orders" class="text-sm text-blue-600 hover:underline font-medium">Все заказы</Link>
            </div>

            <div v-if="recentOrders.length > 0" class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <div v-for="order in recentOrders" :key="order.id" class="flex items-center justify-between p-4 border-b last:border-0 hover:bg-gray-50">
                    <div class="flex items-center gap-3">
                        <span class="font-mono text-sm text-gray-400">#{{ order.id }}</span>
                        <span
                            class="px-2 py-1 rounded-md text-[10px] font-bold uppercase"
                            :class="{
                                'bg-yellow-50 text-yellow-600': order.status === 'pending',
                                'bg-green-50 text-green-600': order.status === 'completed',
                                'bg-red-50 text-red-600': order.status === 'cancelled'
                            }"
                        >
                            {{ statusLabel(order.status) }}
                        </span>
                        <span class="text-sm text-gray-400">{{ order.items_count }} товаров</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-400">{{ order.created_at }}</span>
                        <span class="font-bold text-slate-800">{{ order.total }} $</span>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-10 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                <p class="text-gray-400 mb-3">У вас пока нет заказов</p>
                <Link href="/" class="text-blue-600 font-bold hover:underline">Перейти в каталог</Link>
            </div>
        </div>
    </div>
</template>
