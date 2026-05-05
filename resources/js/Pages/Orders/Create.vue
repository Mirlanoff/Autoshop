<script setup>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

const props = defineProps({
    items: Object,
    total: Number,
})

const form = useForm({
    customer_name: '',
    phone: '',
    address: '',
})

const submit = () => {
    form.post('/checkout')
}

const itemsArray = Object.values(props.items || {})
</script>

<template>
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-3xl font-black mb-8 text-slate-800">Оформление заказа</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <form @submit.prevent="submit" class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Ваше имя *</label>
                        <input
                            v-model="form.customer_name"
                            type="text"
                            placeholder="Иван Иванов"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                        />
                        <p v-if="form.errors.customer_name" class="text-red-500 text-sm mt-1">{{ form.errors.customer_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Телефон *</label>
                        <input
                            v-model="form.phone"
                            type="tel"
                            placeholder="+996 XXX XXX XXX"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                        />
                        <p v-if="form.errors.phone" class="text-red-500 text-sm mt-1">{{ form.errors.phone }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Адрес доставки</label>
                        <textarea
                            v-model="form.address"
                            rows="3"
                            placeholder="Город, улица, дом, квартира"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition-all resize-none"
                        ></textarea>
                        <p v-if="form.errors.address" class="text-red-500 text-sm mt-1">{{ form.errors.address }}</p>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold text-lg hover:bg-blue-600 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed"
                    >
                        <span v-if="form.processing" class="animate-pulse">Оформляем...</span>
                        <span v-else>Подтвердить заказ</span>
                    </button>
                </form>
            </div>

            <div>
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm sticky top-24">
                    <h3 class="font-bold text-slate-800 mb-4">Ваш заказ</h3>

                    <div class="space-y-3 mb-4">
                        <div v-for="item in itemsArray" :key="item.product_id" class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ item.name }} × {{ item.quantity }}</span>
                            <span class="font-bold text-slate-800">{{ (item.price * item.quantity).toFixed(2) }} $</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <div class="flex justify-between">
                            <span class="font-bold text-slate-800">Итого:</span>
                            <span class="text-xl font-black text-slate-900">{{ total.toFixed(2) }} $</span>
                        </div>
                    </div>

                    <Link href="/cart" class="block text-center text-sm text-blue-600 hover:text-blue-800 mt-4 transition-colors">
                        Вернуться в корзину
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
