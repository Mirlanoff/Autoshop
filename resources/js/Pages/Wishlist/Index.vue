<script setup>
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

defineOptions({ layout: AppLayout })

defineProps({
    products: Array,
})

const removing = ref(null)

const removeFromWishlist = (id) => {
    removing.value = id
    router.post('/wishlist/toggle', { product_id: id }, {
        preserveScroll: true,
        onFinish: () => removing.value = null,
    })
}

const addToCart = (id) => {
    router.post('/cart/add', { product_id: id }, { preserveScroll: true })
}
</script>

<template>
    <div class="max-w-6xl mx-auto p-6 font-sans">
        <h1 class="text-3xl font-black mb-8 text-slate-800">Избранное</h1>

        <div v-if="products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div v-for="product in products" :key="product.id" class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm flex flex-col">
                <Link :href="`/products/${product.slug}`" class="block mb-3">
                    <div class="bg-gray-50 rounded-xl h-40 flex items-center justify-center overflow-hidden">
                        <img v-if="product.image" :src="`/storage/${product.image}`" :alt="product.name" class="max-h-full object-contain" />
                        <svg v-else class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </Link>

                <div class="flex-grow">
                    <Link :href="`/products/${product.slug}`">
                        <h2 class="font-bold text-slate-800 leading-tight mb-1 hover:text-blue-600 transition-colors">{{ product.name }}</h2>
                    </Link>
                    <p class="text-xs text-gray-400 mb-3">{{ product.brand?.name }} / {{ product.category?.name }}</p>
                </div>

                <div class="mt-auto">
                    <p class="text-xl font-black text-slate-900 mb-3">{{ product.price }} $</p>
                    <div class="flex gap-2">
                        <button
                            @click="addToCart(product.id)"
                            :disabled="!product.in_stock"
                            class="flex-1 bg-slate-900 text-white py-2 rounded-xl text-sm font-bold hover:bg-blue-600 transition-colors disabled:bg-gray-200 disabled:text-gray-400"
                        >
                            В корзину
                        </button>
                        <button
                            @click="removeFromWishlist(product.id)"
                            :disabled="removing === product.id"
                            class="px-3 py-2 rounded-xl border border-red-200 text-red-500 hover:bg-red-50 transition-colors text-sm"
                        >
                            &#10060;
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
            <p class="text-gray-400 mb-4">В избранном пока ничего нет</p>
            <Link href="/" class="text-blue-600 font-bold hover:underline">Перейти в каталог</Link>
        </div>
    </div>
</template>
