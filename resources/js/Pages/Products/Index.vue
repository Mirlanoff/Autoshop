<script setup>
import { router, Link } from '@inertiajs/vue3'
import { reactive, watch, ref } from 'vue'
import debounce from 'lodash/debounce'
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

const props = defineProps({
    products: Object,
    filters: Object,
    brands: Array,
    categories: Array,
})

const isLoading = ref(false)
const addingId = ref(null)

const form = reactive({
    search: props.filters.search || '',
    brand_id: props.filters.brandId || '',
    category_id: props.filters.categoryId || '',
})

const applyFilters = debounce(() => {
    isLoading.value = true
    router.get('/', form, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
        onFinish: () => isLoading.value = false
    })
}, 300)

watch(() => [form.brand_id, form.category_id], () => applyFilters())

const addToCart = (id) => {
    if (addingId.value) return
    addingId.value = id

    router.post('/cart/add', {
        product_id: id
    }, {
        preserveScroll: true,
        onFinish: () => addingId.value = null,
    })
}
</script>

<template>
    <div class="max-w-7xl mx-auto p-6 font-sans">
        <h1 class="text-3xl font-black mb-8 text-slate-800">
            Каталог запчастей
        </h1>

        <!-- ФИЛЬТРЫ -->
        <div class="flex flex-wrap gap-4 mb-8 bg-gray-50 p-4 rounded-2xl border border-gray-100">
            <input
                v-model="form.search"
                @input="applyFilters"
                type="text"
                placeholder="Поиск по названию..."
                class="flex-1 min-w-[200px] border border-gray-200 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
            />

            <select v-model="form.brand_id" class="border border-gray-200 rounded-xl px-3 py-2 bg-white outline-none cursor-pointer">
                <option value="">Все бренды</option>
                <option v-for="b in brands" :key="b.id" :value="b.id">
                    {{ b.name }}
                </option>
            </select>

            <select v-model="form.category_id" class="border border-gray-200 rounded-xl px-3 py-2 bg-white outline-none cursor-pointer">
                <option value="">Все категории</option>
                <option v-for="c in categories" :key="c.id" :value="c.id">
                    {{ c.name }}
                </option>
            </select>
        </div>

        <!-- ЗАГРУЗКА -->
        <div v-if="isLoading" class="text-center py-20 text-gray-400">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-blue-500 border-t-transparent mb-4"></div>
            <p>Обновление списка...</p>
        </div>

        <!-- ТОВАРЫ -->
        <div v-else-if="products.data && products.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div v-for="product in products.data" :key="product.id" class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow flex flex-col">
                <Link :href="`/products/${product.slug}`" class="flex-grow block">
                    <div v-if="product.image" class="mb-3 rounded-xl overflow-hidden bg-gray-50 h-40 flex items-center justify-center">
                        <img :src="product.image" :alt="product.name" class="max-h-full object-contain" />
                    </div>
                    <h2 class="font-bold text-slate-800 leading-tight mb-1 hover:text-blue-600 transition-colors">
                        {{ product.name }}
                    </h2>
                    <p class="text-xs uppercase tracking-wider text-gray-400 mb-4">
                        {{ product.brand?.name }} &bull; {{ product.category?.name }}
                    </p>
                </Link>

                <div class="mt-auto">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xl font-black text-slate-900">{{ product.price }} $</span>
                        <span v-if="product.in_stock" class="text-green-600 text-[10px] font-bold uppercase bg-green-50 px-2 py-1 rounded">
                            В наличии
                        </span>
                        <span v-else class="text-red-400 text-[10px] font-bold uppercase bg-red-50 px-2 py-1 rounded">
                            Нет на складе
                        </span>
                    </div>

                    <button
                        @click="addToCart(product.id)"
                        :disabled="!product.in_stock || addingId === product.id"
                        class="w-full bg-slate-900 text-white py-3 rounded-xl font-bold hover:bg-blue-600 transition-colors disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed flex justify-center items-center gap-2"
                    >
                        <span v-if="addingId === product.id" class="animate-pulse">Добавление...</span>
                        <span v-else>В корзину</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ПУСТО -->
        <div v-else class="text-center py-20 text-gray-400">
            <p class="text-6xl mb-4">🔍</p>
            <p class="text-xl">Товары не найдены</p>
            <p class="text-sm mt-2">Попробуйте изменить параметры поиска</p>
        </div>

        <!-- ПАГИНАЦИЯ -->
        <div v-if="products.links && products.links.length > 3" class="flex justify-center gap-2 mt-10">
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
