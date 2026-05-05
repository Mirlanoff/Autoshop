<script setup>
import { router, Link } from '@inertiajs/vue3'
import { reactive, watch } from 'vue'
import debounce from 'lodash/debounce'
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

const props = defineProps({
    orders: Object,
    filters: Object,
})

const form = reactive({
    search: props.filters?.search || '',
    status: props.filters?.status || '',
})

const applyFilters = debounce(() => {
    router.get('/admin/orders', form, {
        preserveState: true,
        replace: true,
    })
}, 300)

watch(() => form.status, () => applyFilters())

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
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-3xl font-black mb-8 text-slate-800">
            Управление заказами
        </h1>

        <!-- ФИЛЬТРЫ -->
        <div class="flex flex-wrap gap-4 mb-8 bg-gray-50 p-4 rounded-2xl border border-gray-100">
            <input
                v-model="form.search"
                @input="applyFilters"
                type="text"
                placeholder="Поиск по имени или телефону..."
                class="flex-1 min-w-[200px] border border-gray-200 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
            />
            <select v-model="form.status" class="border border-gray-200 rounded-xl px-3 py-2 bg-white outline-none cursor-pointer">
                <option value="">Все статусы</option>
                <option value="pending">Ожидает</option>
                <option value="processing">В обработке</option>
                <option value="completed">Выполнен</option>
                <option value="cancelled">Отменён</option>
            </select>
        </div>

        <!-- ТАБЛИЦА -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 text-left text-sm text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">#</th>
                        <th class="px-6 py-4 font-bold">Клиент</th>
                        <th class="px-6 py-4 font-bold">Телефон</th>
                        <th class="px-6 py-4 font-bold">Сумма</th>
                        <th class="px-6 py-4 font-bold">Статус</th>
                        <th class="px-6 py-4 font-bold">Дата</th>
                        <th class="px-6 py-4 font-bold"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="order in orders.data"
                        :key="order.id"
                        class="border-t border-gray-50 hover:bg-gray-50 transition-colors"
                    >
                        <td class="px-6 py-4 font-bold text-slate-800">{{ order.id }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ order.customer_name }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ order.phone }}</td>
                        <td class="px-6 py-4 font-bold text-slate-900">{{ order.total }} $</td>
                        <td class="px-6 py-4">
                            <span
                                :class="statusColors[order.status] || 'bg-gray-50 text-gray-700'"
                                class="text-xs font-bold uppercase px-3 py-1 rounded-full"
                            >
                                {{ statusLabels[order.status] || order.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ order.created_at }}</td>
                        <td class="px-6 py-4">
                            <Link
                                :href="`/admin/orders/${order.id}`"
                                class="text-blue-600 hover:text-blue-800 font-bold text-sm transition-colors"
                            >
                                Подробнее
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="!orders.data || orders.data.length === 0" class="text-center py-12 text-gray-400">
                Заказов не найдено
            </div>
        </div>

        <!-- ПАГИНАЦИЯ -->
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
</template>
