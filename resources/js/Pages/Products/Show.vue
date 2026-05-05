<script setup>
import { router, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

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
    <div class="max-w-7xl mx-auto p-6">
        <Link href="/" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6 transition-colors">
            &larr; Назад в каталог
        </Link>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-12">
            <div class="bg-white border border-gray-100 rounded-2xl p-8 flex items-center justify-center min-h-[400px]">
                <img
                    v-if="product.image"
                    :src="product.image"
                    :alt="product.name"
                    class="max-w-full max-h-[350px] object-contain"
                />
                <div v-else class="text-gray-300 text-center">
                    <p class="text-8xl mb-4">🔧</p>
                    <p class="text-sm">Изображение отсутствует</p>
                </div>
            </div>

            <div>
                <div class="mb-2">
                    <span class="text-xs uppercase tracking-wider text-gray-400 font-bold">
                        {{ product.brand?.name }} &bull; {{ product.category?.name }}
                    </span>
                </div>

                <h1 class="text-3xl font-black text-slate-800 mb-4">{{ product.name }}</h1>

                <div class="flex items-center gap-3 mb-6">
                    <span class="text-3xl font-black text-slate-900">{{ product.price }} $</span>
                    <span
                        v-if="product.stock > 0"
                        class="text-green-600 text-xs font-bold uppercase bg-green-50 px-3 py-1 rounded-full"
                    >
                        В наличии ({{ product.stock }} шт.)
                    </span>
                    <span v-else class="text-red-400 text-xs font-bold uppercase bg-red-50 px-3 py-1 rounded-full">
                        Нет на складе
                    </span>
                </div>

                <p v-if="product.description" class="text-gray-600 leading-relaxed mb-8">
                    {{ product.description }}
                </p>

                <button
                    @click="addToCart"
                    :disabled="product.stock <= 0 || adding"
                    class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold text-lg hover:bg-blue-600 transition-colors disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed"
                >
                    <span v-if="adding" class="animate-pulse">Добавляем...</span>
                    <span v-else-if="product.stock <= 0">Нет на складе</span>
                    <span v-else>Добавить в корзину</span>
                </button>
            </div>
        </div>

        <div v-if="related && related.length > 0">
            <h2 class="text-2xl font-black text-slate-800 mb-6">Похожие товары</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <Link
                    v-for="item in related"
                    :key="item.id"
                    :href="`/products/${item.slug}`"
                    class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow block"
                >
                    <h3 class="font-bold text-slate-800 leading-tight mb-1">{{ item.name }}</h3>
                    <p class="text-xs uppercase tracking-wider text-gray-400 mb-3">
                        {{ item.brand?.name }} &bull; {{ item.category?.name }}
                    </p>
                    <span class="text-xl font-black text-slate-900">{{ item.price }} $</span>
                </Link>
            </div>
        </div>
    </div>
</template>
