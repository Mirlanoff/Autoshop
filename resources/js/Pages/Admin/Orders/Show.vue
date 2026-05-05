<script setup>
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

const props = defineProps({
    order: Object
})

const updateStatus = (status) => {
    router.post(`/admin/orders/${props.order.id}/status`, {
        status
    })
}
</script>

<template>
    <div class="max-w-4xl mx-auto p-6 font-sans">
        <div class="flex items-center gap-4 mb-8">
            <Link href="/admin/orders" class="text-gray-400 hover:text-slate-800 transition-colors">
                &larr; Назад
            </Link>
            <h1 class="text-2xl font-black text-slate-800">
                Заказ #{{ order.id }}
            </h1>
            <span
                class="px-3 py-1 rounded-lg text-xs font-bold uppercase"
                :class="{
                    'bg-yellow-50 text-yellow-600': order.status === 'pending',
                    'bg-green-50 text-green-600': order.status === 'completed',
                    'bg-red-50 text-red-600': order.status === 'cancelled'
                }"
            >
                {{ order.status }}
            </span>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm mb-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-xs uppercase tracking-wider text-gray-400 mb-1">Клиент</p>
                    <p class="font-bold text-slate-800">{{ order.customer_name }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider text-gray-400 mb-1">Телефон</p>
                    <p class="font-bold text-slate-800">{{ order.phone }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider text-gray-400 mb-1">Сумма</p>
                    <p class="font-black text-xl text-slate-900">{{ order.total }} $</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider text-gray-400 mb-1">Дата</p>
                    <p class="font-bold text-slate-800">{{ order.created_at }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm mb-6">
            <div class="p-4 border-b bg-gray-50">
                <h2 class="font-bold text-slate-800">Товары</h2>
            </div>
            <div v-for="item in order.items" :key="item.id" class="flex items-center justify-between p-4 border-b last:border-0">
                <span class="font-medium text-slate-800">{{ item.name }}</span>
                <span class="text-gray-500">{{ item.quantity }} x {{ item.price }} $</span>
                <span class="font-bold text-slate-900">{{ (item.quantity * item.price).toFixed(2) }} $</span>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-4">Изменить статус</h3>
            <div class="flex gap-3">
                <button
                    @click="updateStatus('pending')"
                    :disabled="order.status === 'pending'"
                    class="px-4 py-2 rounded-xl font-bold text-sm transition-colors"
                    :class="order.status === 'pending' ? 'bg-yellow-100 text-yellow-700 cursor-not-allowed' : 'bg-gray-100 text-gray-700 hover:bg-yellow-50'"
                >
                    Ожидает
                </button>

                <button
                    @click="updateStatus('completed')"
                    :disabled="order.status === 'completed'"
                    class="px-4 py-2 rounded-xl font-bold text-sm transition-colors"
                    :class="order.status === 'completed' ? 'bg-green-100 text-green-700 cursor-not-allowed' : 'bg-gray-100 text-gray-700 hover:bg-green-50'"
                >
                    Выполнен
                </button>

                <button
                    @click="updateStatus('cancelled')"
                    :disabled="order.status === 'cancelled'"
                    class="px-4 py-2 rounded-xl font-bold text-sm transition-colors"
                    :class="order.status === 'cancelled' ? 'bg-red-100 text-red-700 cursor-not-allowed' : 'bg-gray-100 text-gray-700 hover:bg-red-50'"
                >
                    Отменен
                </button>
            </div>
        </div>
    </div>
</template>
