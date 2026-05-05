<script setup>
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

const form = useForm({
    customer_name: '',
    phone: '',
})

const submit = () => {
    form.post('/checkout')
}
</script>

<template>
    <div class="max-w-xl mx-auto p-6 font-sans">
        <h1 class="text-3xl font-black mb-8 text-slate-800">Оформление заказа</h1>

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

            <div class="mb-8">
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

            <div class="flex items-center gap-4">
                <button
                    @click="submit"
                    :disabled="form.processing"
                    class="flex-1 bg-slate-900 text-white py-3 rounded-xl font-bold hover:bg-blue-600 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed"
                >
                    <span v-if="form.processing">Оформляем...</span>
                    <span v-else>Оформить заказ</span>
                </button>

                <Link
                    href="/cart"
                    class="text-gray-500 hover:text-slate-800 font-medium transition-colors"
                >
                    Назад в корзину
                </Link>
            </div>
        </div>
    </div>
</template>
