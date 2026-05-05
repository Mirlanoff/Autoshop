<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

const props = defineProps({
    items: Object,
    total: Number,
})

const updatingId = ref(null)
const removingId = ref(null)

const updateQuantity = (id, quantity) => {
    if (quantity < 1) return
    updatingId.value = id
    router.post(`/cart/${id}/update`, { quantity }, {
        preserveScroll: true,
        onFinish: () => updatingId.value = null,
    })
}

const removeItem = (id) => {
    removingId.value = id
    router.post(`/cart/${id}/remove`, {}, {
        preserveScroll: true,
        onFinish: () => removingId.value = null,
    })
}

const itemsArray = Object.values(props.items || {})
</script>

<template>
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-3xl font-black mb-8 text-slate-800">Корзина</h1>

        <div v-if="itemsArray.length === 0" class="text-center py-20">
            <p class="text-6xl mb-4">🛒</p>
            <p class="text-xl text-gray-500 mb-6">Корзина пуста</p>
            <Link href="/" class="inline-block bg-slate-900 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-600 transition-colors">
                Перейти в каталог
            </Link>
        </div>

        <div v-else>
            <div class="space-y-4 mb-8">
                <div
                    v-for="item in itemsArray"
                    :key="item.product_id"
                    class="bg-white border border-gray-100 rounded-2xl p-5 flex items-center justify-between shadow-sm"
                >
                    <div class="flex-grow">
                        <h3 class="font-bold text-slate-800">{{ item.name }}</h3>
                        <p class="text-gray-500 text-sm">{{ item.price }} $ за шт.</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                            <button
                                @click="updateQuantity(item.product_id, item.quantity - 1)"
                                :disabled="item.quantity <= 1 || updatingId === item.product_id"
                                class="px-3 py-2 hover:bg-gray-100 transition-colors disabled:opacity-50"
                            >−</button>
                            <span class="px-4 py-2 font-bold text-slate-800 min-w-[40px] text-center">
                                {{ item.quantity }}
                            </span>
                            <button
                                @click="updateQuantity(item.product_id, item.quantity + 1)"
                                :disabled="updatingId === item.product_id"
                                class="px-3 py-2 hover:bg-gray-100 transition-colors disabled:opacity-50"
                            >+</button>
                        </div>

                        <span class="font-black text-slate-900 min-w-[80px] text-right">
                            {{ (item.price * item.quantity).toFixed(2) }} $
                        </span>

                        <button
                            @click="removeItem(item.product_id)"
                            :disabled="removingId === item.product_id"
                            class="text-red-400 hover:text-red-600 transition-colors p-2"
                        >
                            <span v-if="removingId === item.product_id" class="animate-pulse">...</span>
                            <span v-else>✕</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <span class="text-lg text-gray-600">Итого:</span>
                    <span class="text-2xl font-black text-slate-900">{{ total.toFixed(2) }} $</span>
                </div>

                <div class="flex gap-4">
                    <Link href="/" class="flex-1 text-center border border-gray-200 text-slate-700 py-3 rounded-xl font-bold hover:bg-gray-50 transition-colors">
                        Продолжить покупки
                    </Link>
                    <Link href="/checkout" class="flex-1 text-center bg-slate-900 text-white py-3 rounded-xl font-bold hover:bg-blue-600 transition-colors">
                        Оформить заказ
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
