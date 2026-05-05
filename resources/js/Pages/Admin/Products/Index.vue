<script setup>
import { router, Link } from '@inertiajs/vue3'
import { reactive, watch } from 'vue'
import debounce from 'lodash/debounce'
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

const props = defineProps({
    products: Object,
    filters: Object,
    brands: Array,
    categories: Array,
})

const form = reactive({
    search: props.filters?.search || '',
    brand_id: props.filters?.brand_id || '',
    category_id: props.filters?.category_id || '',
})

const applyFilters = debounce(() => {
    router.get('/admin/products', form, {
        preserveState: true,
        replace: true,
    })
}, 300)

watch(() => [form.brand_id, form.category_id], () => applyFilters())

const deleteProduct = (id) => {
    if (!confirm('Удалить этот товар?')) return
    router.delete(`/admin/products/${id}`)
}
</script>

<template>
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-black text-slate-800">Управление товарами</h1>
            <Link
                href="/admin/products/create"
                class="bg-slate-900 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-600 transition-colors"
            >
                + Добавить товар
            </Link>
        </div>

        <!-- ФИЛЬТРЫ -->
        <div class="flex flex-wrap gap-4 mb-8 bg-gray-50 p-4 rounded-2xl border border-gray-100">
            <input
                v-model="form.search"
                @input="applyFilters"
                type="text"
                placeholder="Поиск по названию..."
                class="flex-1 min-w-[200px] border border-gray-200 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
            />
            <select v-model="form.brand_id" class="border border-gray-200 rounded-xl px-3 py-2 bg-white outline-none cursor-pointer">
                <option value="">Все бренды</option>
                <option v-for="b in brands" :key="b.id" :value="b.id">{{ b.name }}</option>
            </select>
            <select v-model="form.category_id" class="border border-gray-200 rounded-xl px-3 py-2 bg-white outline-none cursor-pointer">
                <option value="">Все категории</option>
                <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
            </select>
        </div>

        <!-- ТАБЛИЦА -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 text-left text-sm text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">Фото</th>
                        <th class="px-6 py-4 font-bold">Название</th>
                        <th class="px-6 py-4 font-bold">Бренд</th>
                        <th class="px-6 py-4 font-bold">Категория</th>
                        <th class="px-6 py-4 font-bold">Цена</th>
                        <th class="px-6 py-4 font-bold">Склад</th>
                        <th class="px-6 py-4 font-bold"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="product in products.data"
                        :key="product.id"
                        class="border-t border-gray-50 hover:bg-gray-50 transition-colors"
                    >
                        <td class="px-6 py-3">
                            <img
                                v-if="product.image"
                                :src="product.image"
                                :alt="product.name"
                                class="w-12 h-12 object-cover rounded-lg"
                            />
                            <div v-else class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-300 text-xs">
                                —
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            <span class="font-bold text-slate-800">{{ product.name }}</span>
                        </td>
                        <td class="px-6 py-3 text-slate-600">{{ product.brand?.name }}</td>
                        <td class="px-6 py-3 text-slate-600">{{ product.category?.name }}</td>
                        <td class="px-6 py-3 font-bold text-slate-900">{{ product.price }} $</td>
                        <td class="px-6 py-3">
                            <span
                                :class="product.stock > 0 ? 'text-green-600 bg-green-50' : 'text-red-500 bg-red-50'"
                                class="text-xs font-bold px-2 py-1 rounded-full"
                            >
                                {{ product.stock }} шт.
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <Link
                                    :href="`/admin/products/${product.id}/edit`"
                                    class="text-blue-600 hover:text-blue-800 font-bold text-sm transition-colors"
                                >
                                    Изменить
                                </Link>
                                <button
                                    @click="deleteProduct(product.id)"
                                    class="text-red-500 hover:text-red-700 font-bold text-sm transition-colors"
                                >
                                    Удалить
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="!products.data || products.data.length === 0" class="text-center py-12 text-gray-400">
                Товаров не найдено
            </div>
        </div>

        <!-- ПАГИНАЦИЯ -->
        <div v-if="products.links && products.links.length > 3" class="flex justify-center gap-2 mt-8">
            <Link
                v-for="link in products.links"
                :key="link.label"
                :href="link.url || '#'"
                v-html="link.label"
                class="px-4 py-2 rounded-xl text-sm font-bold transition-colors"
                :class="link.active ? 'bg-slate-900 text-white' : 'bg-white border border-gray-200 text-slate-600 hover:bg-gray-50'"
            />
        </div>
    </div>
</template>
