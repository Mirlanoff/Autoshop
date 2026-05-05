<script setup>
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

const props = defineProps({
    items: Object,
    total: Number,
})

const updateQuantity = (id, quantity) => {
    if (quantity < 1) return
    router.post(`/cart/${id}/update`, { quantity }, {
        preserveScroll: true,
    })
}

const removeItem = (id) => {
    router.post(`/cart/${id}/remove`, {}, {
        preserveScroll: true,
    })
}
</script>

<template>
    <div class="max-w-4xl mx-auto p-6 font-sans">
        <h1 class="text-3xl font-black mb-8 text-slate-800">Корзина</h1>

        <div v-if="Object.keys(items).length > 0">
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                <div
                    v-for="(item, id) in items"
                    :key="id"
                    class="flex items-center justify-between p-5 border-b last:border-0 hover:bg-gray-50 transition-colors"
                >
                    <div class="flex-1">
                        <h3 class="font-bold text-slate-800">{{ item.name }}</h3>
                        <p class="text-sm text-gray-500">{{ item.price }} $ за шт.</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button
                            @click="updateQuantity(id, item.quantity - 1)"
                            :disabled="item.quantity <= 1"
                            class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center font-bold text-slate-700 transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
                        >
                            −
                        </button>

                        <span class="w-8 text-center font-bold text-slate-800">
                            {{ item.quantity }}
                        </span>

                        <button
                            @click="updateQuantity(id, item.quantity + 1)"
                            class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center font-bold text-slate-700 transition-colors"
                        >
                            +
                        </button>
                    </div>

                    <div class="w-28 text-right">
                        <span class="font-black text-slate-900">
                            {{ (item.price * item.quantity).toFixed(2) }} $
                        </span>
                    </div>

                    <button
                        @click="removeItem(id)"
                        class="ml-4 text-red-400 hover:text-red-600 transition-colors text-sm font-medium"
                    >
                        Удалить
                    </button>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between bg-gray-50 p-6 rounded-2xl border border-gray-100">
                <div>
                    <span class="text-gray-500">Итого:</span>
                    <span class="text-2xl font-black text-slate-900 ml-3">{{ total.toFixed(2) }} $</span>
                </div>

                <Link
                    href="/checkout"
                    class="bg-slate-900 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-600 transition-colors"
                >
                    Оформить заказ
                </Link>
            </div>
        </div>

        <div v-else class="text-center py-20">
            <p class="text-gray-400 text-lg mb-6">Корзина пуста</p>
            <Link
                href="/"
                class="bg-slate-900 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-600 transition-colors"
            >
                Перейти в каталог
            </Link>
        </div>
    </div>
</template>
