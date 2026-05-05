<script setup>
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'
import debounce from 'lodash/debounce'
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

const props = defineProps({
    orders: Object,
    filters: Object
})

const form = reactive({
    search: props.filters.search || '',
    status: props.filters.status || ''
})

const apply = debounce(() => {
    router.get('/admin/orders', form, {
        preserveState: true,
        replace: true,
        preserveScroll: true
    })
}, 300)

const paymentLabel = (method) => {
    return method === 'online' ? 'Онлайн' : 'Наличные'
}

const paymentStatusLabel = (status) => {
    const labels = { pending: 'Ожидает', paid: 'Оплачен', failed: 'Ошибка' }
    return labels[status] || status
}
</script>

<template>
    <div class="max-w-6xl mx-auto p-6 font-sans">
        <h1 class="text-2xl font-bold mb-6 text-slate-800">Управление заказами</h1>

        <div class="flex gap-4 mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
            <input
                v-model="form.search"
                @input="apply"
                placeholder="Поиск (имя или телефон)..."
                class="flex-1 border border-gray-200 px-4 py-2 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 transition-all"
            />

            <select
                v-model="form.status"
                @change="apply"
                :class="form.status ? 'border-blue-500 ring-1 ring-blue-500' : 'border-gray-200'"
                class="border px-4 py-2 rounded-lg bg-white outline-none cursor-pointer"
            >
                <option value="">Все статусы</option>
                <option value="pending">Ожидает</option>
                <option value="completed">Выполнен</option>
                <option value="cancelled">Отменен</option>
            </select>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="grid grid-cols-6 bg-gray-50 p-4 text-xs font-bold uppercase tracking-wider text-gray-500 border-b">
                <div>ID</div>
                <div>Клиент</div>
                <div>Сумма</div>
                <div>Оплата</div>
                <div>Статус</div>
                <div class="text-right">Действие</div>
            </div>

            <div v-if="orders.data.length > 0">
                <div v-for="order in orders.data" :key="order.id" class="grid grid-cols-6 p-4 border-b last:border-0 items-center hover:bg-gray-50 transition-colors">
                    <div class="font-mono text-sm text-gray-600">#{{ order.id }}</div>
                    <div>
                        <p class="font-semibold text-slate-800">{{ order.customer_name }}</p>
                        <p class="text-xs text-gray-400">{{ order.phone }}</p>
                    </div>
                    <div class="font-bold text-slate-800">{{ order.total }} $</div>
                    <div>
                        <span
                            class="px-2 py-1 rounded-md text-[10px] font-bold uppercase"
                            :class="{
                                'bg-green-50 text-green-600': order.payment_status === 'paid',
                                'bg-yellow-50 text-yellow-600': order.payment_status === 'pending',
                                'bg-red-50 text-red-600': order.payment_status === 'failed'
                            }"
                        >
                            {{ paymentStatusLabel(order.payment_status) }}
                        </span>
                        <p class="text-[10px] text-gray-400 mt-1">{{ paymentLabel(order.payment_method) }}</p>
                    </div>
                    <div>
                        <span
                            class="px-2 py-1 rounded-md text-[10px] font-bold uppercase"
                            :class="{
                                'bg-yellow-50 text-yellow-600': order.status === 'pending',
                                'bg-green-50 text-green-600': order.status === 'completed',
                                'bg-red-50 text-red-600': order.status === 'cancelled'
                            }"
                        >
                            {{ order.status }}
                        </span>
                    </div>
                    <div class="text-right">
                        <a :href="`/admin/orders/${order.id}`" class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors">
                            Открыть &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <div v-else class="p-10 text-center text-gray-400">
                Заказов пока нет
            </div>
        </div>

        <div v-if="orders.links.length > 3" class="mt-6 flex justify-center gap-2">
            <button
                v-for="link in orders.links"
                :key="link.label"
                v-html="link.label"
                @click="link.url && router.visit(link.url)"
                :disabled="!link.url || link.active"
                class="px-4 py-2 border rounded-lg text-sm transition-all"
                :class="{
                    'bg-blue-600 text-white border-blue-600': link.active,
                    'bg-white text-gray-600 hover:bg-gray-50': !link.active && link.url,
                    'opacity-30 cursor-not-allowed': !link.url
                }"
            />
        </div>
    </div>
</template>
