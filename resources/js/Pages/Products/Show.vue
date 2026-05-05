<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

const props = defineProps({
    product: Object,
    related: Array,
})

const adding = ref(false)

const addToCart = () => {
    if (adding.value) return
    adding.value = true
    router.post('/cart/add', { product_id: props.product.id }, {
        preserveScroll: true,
        onFinish: () => adding.value = false,
    })
}
</script>

<template>
    <div class="max-w-6xl mx-auto p-6 font-sans">
        <!-- Хлебные крошки -->
        <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
            <Link href="/" class="hover:text-slate-800 transition-colors">Каталог</Link>
            <span>/</span>
            <span class="text-slate-600">{{ product.name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <!-- Изображение -->
            <div class="bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-center min-h-[400px] overflow-hidden">
                <img
                    v-if="product.image"
                    :src="`/storage/${product.image}`"
                    :alt="product.name"
                    class="max-w-full max-h-[500px] object-contain"
                />
                <div v-else class="text-center text-gray-300">
                    <svg class="w-24 h-24 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-sm">Фото скоро появится</p>
                </div>
            </div>

            <!-- Информация -->
            <div>
                <div class="mb-2">
                    <span class="text-xs uppercase tracking-wider text-gray-400 font-bold">
                        {{ product.brand?.name }} / {{ product.category?.name }}
                    </span>
                </div>

                <h1 class="text-3xl font-black text-slate-800 mb-4">{{ product.name }}</h1>

                <div class="flex items-center gap-4 mb-6">
                    <span class="text-3xl font-black text-slate-900">{{ product.price }} $</span>
                    <span
                        v-if="product.stock > 0"
                        class="text-green-600 text-xs font-bold uppercase bg-green-50 px-3 py-1 rounded-lg"
                    >
                        В наличии ({{ product.stock }} шт.)
                    </span>
                    <span
                        v-else
                        class="text-red-400 text-xs font-bold uppercase bg-red-50 px-3 py-1 rounded-lg"
                    >
                        Нет на складе
                    </span>
                </div>

                <div v-if="product.description" class="mb-8">
                    <h3 class="font-bold text-slate-700 mb-2">Описание</h3>
                    <p class="text-gray-600 leading-relaxed">{{ product.description }}</p>
                </div>

                <button
                    @click="addToCart"
                    :disabled="product.stock <= 0 || adding"
                    class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold text-lg hover:bg-blue-600 transition-colors disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed"
                >
                    <span v-if="adding">Добавление...</span>
                    <span v-else-if="product.stock <= 0">Нет в наличии</span>
                    <span v-else>Добавить в корзину — {{ product.price }} $</span>
                </button>
            </div>
        </div>

        <!-- Похожие товары -->
        <div v-if="related.length > 0" class="mt-16">
            <h2 class="text-2xl font-black text-slate-800 mb-6">Похожие товары</h2>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <Link
                    v-for="item in related"
                    :key="item.id"
                    :href="`/products/${item.slug}`"
                    class="bg-white border border-gray-100 rounded-2xl p-4 hover:shadow-md transition-shadow block"
                >
                    <div class="bg-gray-50 rounded-xl h-32 flex items-center justify-center mb-3 overflow-hidden">
                        <img v-if="item.image" :src="`/storage/${item.image}`" :alt="item.name" class="max-h-full object-contain" />
                        <svg v-else class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm leading-tight mb-1">{{ item.name }}</h3>
                    <p class="text-xs text-gray-400 mb-2">{{ item.brand?.name }}</p>
                    <p class="font-black text-slate-900">{{ item.price }} $</p>
                </Link>
            </div>
        </div>
    </div>
</template>
