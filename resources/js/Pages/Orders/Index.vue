<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

const props = defineProps({
    orders: Object,
})

const statusLabels = {
    pending: 'Ожидает',
    processing: 'В обработке',
    completed: 'Выполнен',
    cancelled: 'Отменён',
}

const statusColors = {
    pending: 'bg-yellow-50 text-yellow-700',
    processing: 'bg-blue-50 text-blue-700',
    completed: 'bg-green-50 text-green-700',
    cancelled: 'bg-red-50 text-red-700',
}
</script>

<template>
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-3xl font-black mb-8 text-slate-800">Мои заказы</h1>

        <div v-if="!orders.data || orders.data.length === 0" class="text-center py-20">
            <p class="text-6xl mb-4">📦</p>
            <p class="text-xl text-gray-500 mb-6">У вас пока нет заказов</p>
            <Link href="/" class="inline-block bg-slate-900 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-600 transition-colors">
                Перейти в каталог
            </Link>
        </div>

        <div v-else class="space-y-4">
            <Link
                v-for="order in orders.data"
                :key="order.id"
                :href="`/orders/${order.id}`"
                class="block bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow"
            >
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <span class="text-lg font-black text-slate-800">Заказ #{{ order.id }}</span>
                        <span
                            :class="statusColors[order.status] || 'bg-gray-50 text-gray-700'"
                            class="text-xs font-bold uppercase px-3 py-1 rounded-full"
                        >
                            {{ statusLabels[order.status] || order.status }}
                        </span>
                    </div>
                    <span class="text-sm text-gray-500">{{ order.created_at }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-600 text-sm">
                        {{ order.items?.length || 0 }} товар(ов)
                    </span>
                    <span class="text-xl font-black text-slate-900">{{ order.total }} $</span>
                </div>
            </Link>

            <div v-if="orders.links && orders.links.length > 3" class="flex justify-center gap-2 mt-8">
                <Link
                    v-for="link in orders.links"
                    :key="link.label"
                    :href="link.url || '#'"
                    v-html="link.label"
                    class="px-4 py-2 rounded-xl text-sm font-bold transition-colors"
                    :class="link.active ? 'bg-slate-900 text-white' : 'bg-white border border-gray-200 text-slate-600 hover:bg-gray-50'"
                />
            </div>
        </div>
    </div>
</template>
