<script setup>
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'
import debounce from 'lodash/debounce'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

const props = defineProps({
    products: Object,
    filters: Object,
    brands: Array,
    categories: Array,
})

const form = reactive({
    search: props.filters.search || '',
    brand_id: props.filters.brand_id || '',
    category_id: props.filters.category_id || '',
})

const apply = debounce(() => {
    router.get('/admin/products', form, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    })
}, 300)

const destroy = (id) => {
    if (confirm('Удалить этот товар?')) {
        router.delete(`/admin/products/${id}`)
    }
}
</script>

<template>
    <div class="max-w-6xl mx-auto p-6 font-sans">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Управление товарами</h1>
            <Link
                href="/admin/products/create"
                class="bg-slate-900 text-white px-5 py-2 rounded-xl font-bold text-sm hover:bg-blue-600 transition-colors"
            >
                + Добавить товар
            </Link>
        </div>

        <div class="flex gap-4 mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
            <input
                v-model="form.search"
                @input="apply"
                placeholder="Поиск..."
                class="flex-1 border border-gray-200 px-4 py-2 rounded-lg outline-none focus:ring-2 focus:ring-blue-500"
            />
            <select v-model="form.brand_id" @change="apply" class="border border-gray-200 px-3 py-2 rounded-lg bg-white">
                <option value="">Все бренды</option>
                <option v-for="b in brands" :key="b.id" :value="b.id">{{ b.name }}</option>
            </select>
            <select v-model="form.category_id" @change="apply" class="border border-gray-200 px-3 py-2 rounded-lg bg-white">
                <option value="">Все категории</option>
                <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
            </select>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="grid grid-cols-7 bg-gray-50 p-4 text-xs font-bold uppercase tracking-wider text-gray-500 border-b">
                <div>Фото</div>
                <div class="col-span-2">Название</div>
                <div>Бренд</div>
                <div>Цена</div>
                <div>Склад</div>
                <div class="text-right">Действия</div>
            </div>

            <div v-if="products.data.length > 0">
                <div v-for="product in products.data" :key="product.id" class="grid grid-cols-7 p-4 border-b last:border-0 items-center hover:bg-gray-50">
                    <div>
                        <img v-if="product.image" :src="`/storage/${product.image}`" class="w-12 h-12 object-cover rounded-lg" />
                        <div v-else class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-300 text-xs">N/A</div>
                    </div>
                    <div class="col-span-2">
                        <p class="font-semibold text-slate-800">{{ product.name }}</p>
                        <p class="text-xs text-gray-400">{{ product.category?.name }}</p>
                    </div>
                    <div class="text-sm text-gray-600">{{ product.brand?.name }}</div>
                    <div class="font-bold text-slate-800">{{ product.price }} $</div>
                    <div>
                        <span
                            class="text-xs font-bold px-2 py-1 rounded"
                            :class="product.stock > 0 ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-500'"
                        >
                            {{ product.stock }} шт.
                        </span>
                    </div>
                    <div class="text-right flex items-center justify-end gap-2">
                        <Link :href="`/admin/products/${product.id}/edit`" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Редакт.
                        </Link>
                        <button @click="destroy(product.id)" class="text-red-500 hover:text-red-700 text-sm font-medium">
                            Удалить
                        </button>
                    </div>
                </div>
            </div>
            <div v-else class="p-10 text-center text-gray-400">Товаров пока нет</div>
        </div>

        <div v-if="products.links && products.links.length > 3" class="mt-6 flex justify-center gap-2">
            <button
                v-for="link in products.links"
                :key="link.label"
                v-html="link.label"
                @click="link.url && router.visit(link.url)"
                :disabled="!link.url || link.active"
                class="px-4 py-2 border rounded-lg text-sm"
                :class="{
                    'bg-blue-600 text-white border-blue-600': link.active,
                    'bg-white text-gray-600 hover:bg-gray-50': !link.active && link.url,
                    'opacity-30 cursor-not-allowed': !link.url
                }"
            />
        </div>
    </div>
</template>
