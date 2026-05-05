<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

defineProps({
    orders: Object,
})

const statusLabel = (s) => {
    const map = { pending: 'Ожидает', completed: 'Выполнен', cancelled: 'Отменён' }
    return map[s] || s
}

const paymentLabel = (s) => {
    const map = { pending: 'Ожидает', paid: 'Оплачен', failed: 'Ошибка' }
    return map[s] || s
}
</script>

<template>
    <div class="max-w-4xl mx-auto p-6 font-sans">
        <h1 class="text-3xl font-black mb-8 text-slate-800">Мои заказы</h1>

        <div v-if="orders.data && orders.data.length > 0" class="space-y-4">
            <div
                v-for="order in orders.data"
                :key="order.id"
                class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm"
            >
                <div class="flex items-center justify-between mb-4">
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
                        <span
                            class="px-2 py-1 rounded-md text-[10px] font-bold uppercase"
                            :class="{
                                'bg-green-50 text-green-600': order.payment_status === 'paid',
                                'bg-yellow-50 text-yellow-600': order.payment_status === 'pending',
                                'bg-red-50 text-red-600': order.payment_status === 'failed'
                            }"
                        >
                            {{ paymentLabel(order.payment_status) }}
                        </span>
                    </div>
                    <span class="text-sm text-gray-400">{{ order.created_at }}</span>
                </div>

                <div v-if="order.items" class="space-y-2 mb-4">
                    <div v-for="item in order.items" :key="item.id" class="flex justify-between text-sm">
                        <span class="text-slate-700">{{ item.name }} <span class="text-gray-400">x{{ item.quantity }}</span></span>
                        <span class="font-bold text-slate-800">{{ (item.price * item.quantity).toFixed(2) }} $</span>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                    <span class="text-gray-500 text-sm">
                        {{ order.payment_method === 'online' ? 'Онлайн (Stripe)' : 'Наличные' }}
                    </span>
                    <span class="text-xl font-black text-slate-900">{{ order.total }} $</span>
                </div>
            </div>
        </div>

        <div v-else class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
            <p class="text-gray-400 mb-4">У вас пока нет заказов</p>
            <Link href="/" class="text-blue-600 font-bold hover:underline">
                Перейти в каталог
            </Link>
        </div>
    </div>
</template>
