<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

const props = defineProps({
    order: Object,
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
        <Link href="/orders" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6 transition-colors">
            &larr; Назад к заказам
        </Link>

        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm mb-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-black text-slate-800">Заказ #{{ order.id }}</h1>
                <span
                    :class="statusColors[order.status] || 'bg-gray-50 text-gray-700'"
                    class="text-sm font-bold uppercase px-4 py-2 rounded-full"
                >
                    {{ statusLabels[order.status] || order.status }}
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm mb-6">
                <div>
                    <span class="text-gray-500 block mb-1">Имя</span>
                    <span class="font-bold text-slate-800">{{ order.customer_name }}</span>
                </div>
                <div>
                    <span class="text-gray-500 block mb-1">Телефон</span>
                    <span class="font-bold text-slate-800">{{ order.phone }}</span>
                </div>
                <div>
                    <span class="text-gray-500 block mb-1">Дата</span>
                    <span class="font-bold text-slate-800">{{ order.created_at }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-4">Товары</h3>

            <div class="space-y-3">
                <div
                    v-for="item in order.items"
                    :key="item.id"
                    class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0"
                >
                    <div>
                        <span class="font-bold text-slate-800">{{ item.name }}</span>
                        <span class="text-gray-500 text-sm ml-2">× {{ item.quantity }}</span>
                    </div>
                    <span class="font-bold text-slate-900">{{ (item.price * item.quantity).toFixed(2) }} $</span>
                </div>
            </div>

            <div class="border-t border-gray-200 mt-4 pt-4 flex justify-between">
                <span class="text-lg font-bold text-slate-800">Итого:</span>
                <span class="text-2xl font-black text-slate-900">{{ order.total }} $</span>
            </div>
        </div>
    </div>
</template>
