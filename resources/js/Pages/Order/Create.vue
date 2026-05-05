<script setup>
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

const props = defineProps({
    cartItems: Object,
    cartTotal: Number,
    stripeEnabled: Boolean,
})

const form = useForm({
    customer_name: '',
    phone: '',
    payment_method: 'cash',
})

const submit = () => {
    form.post('/checkout')
}
</script>

<template>
    <div class="max-w-2xl mx-auto p-6 font-sans">
        <h1 class="text-3xl font-black mb-8 text-slate-800">Оформление заказа</h1>

        <!-- Сводка заказа -->
        <div class="bg-gray-50 border border-gray-100 rounded-2xl p-6 mb-6">
            <h2 class="font-bold text-slate-700 mb-3">Ваш заказ</h2>
            <div v-for="(item, id) in cartItems" :key="id" class="flex justify-between py-2 border-b border-gray-200 last:border-0">
                <span class="text-slate-700">{{ item.name }} <span class="text-gray-400">x{{ item.quantity }}</span></span>
                <span class="font-bold text-slate-800">{{ (item.price * item.quantity).toFixed(2) }} $</span>
            </div>
            <div class="flex justify-between mt-4 pt-3 border-t-2 border-gray-200">
                <span class="font-bold text-slate-800">Итого:</span>
                <span class="text-xl font-black text-slate-900">{{ cartTotal?.toFixed(2) }} $</span>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm">
            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Ваше имя</label>
                <input
                    v-model="form.customer_name"
                    type="text"
                    placeholder="Введите ваше имя"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                    :class="{ 'border-red-400': form.errors.customer_name }"
                />
                <p v-if="form.errors.customer_name" class="text-red-500 text-sm mt-1">
                    {{ form.errors.customer_name }}
                </p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Телефон</label>
                <input
                    v-model="form.phone"
                    type="tel"
                    placeholder="+7 (___) ___-__-__"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                    :class="{ 'border-red-400': form.errors.phone }"
                />
                <p v-if="form.errors.phone" class="text-red-500 text-sm mt-1">
                    {{ form.errors.phone }}
                </p>
            </div>

            <!-- Способ оплаты -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-3">Способ оплаты</label>
                <div class="grid grid-cols-2 gap-3">
                    <label
                        class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all"
                        :class="form.payment_method === 'cash'
                            ? 'border-slate-900 bg-slate-50'
                            : 'border-gray-200 hover:border-gray-300'"
                    >
                        <input
                            type="radio"
                            v-model="form.payment_method"
                            value="cash"
                            class="accent-slate-900"
                        />
                        <div>
                            <p class="font-bold text-slate-800">&#128176; Наличные</p>
                            <p class="text-xs text-gray-400">Оплата при получении</p>
                        </div>
                    </label>

                    <label
                        class="flex items-center gap-3 p-4 border-2 rounded-xl transition-all"
                        :class="[
                            !stripeEnabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer',
                            form.payment_method === 'online' && stripeEnabled
                                ? 'border-blue-600 bg-blue-50'
                                : 'border-gray-200 hover:border-gray-300'
                        ]"
                    >
                        <input
                            type="radio"
                            v-model="form.payment_method"
                            value="online"
                            :disabled="!stripeEnabled"
                            class="accent-blue-600"
                        />
                        <div>
                            <p class="font-bold text-slate-800">&#128179; Онлайн</p>
                            <p class="text-xs text-gray-400">
                                {{ stripeEnabled ? 'Банковская карта (Stripe)' : 'Скоро будет доступно' }}
                            </p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button
                    @click="submit"
                    :disabled="form.processing"
                    class="flex-1 text-white py-3 rounded-xl font-bold transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed"
                    :class="form.payment_method === 'online'
                        ? 'bg-blue-600 hover:bg-blue-700'
                        : 'bg-slate-900 hover:bg-blue-600'"
                >
                    <span v-if="form.processing">Оформляем...</span>
                    <span v-else-if="form.payment_method === 'online'">Перейти к оплате — {{ cartTotal?.toFixed(2) }} $</span>
                    <span v-else>Оформить заказ — {{ cartTotal?.toFixed(2) }} $</span>
                </button>

                <Link
                    href="/cart"
                    class="text-gray-500 hover:text-slate-800 font-medium transition-colors"
                >
                    Назад
                </Link>
            </div>
        </div>
    </div>
</template>
