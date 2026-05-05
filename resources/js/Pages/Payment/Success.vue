<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

defineProps({
    order: Object,
})
</script>

<template>
    <div class="max-w-lg mx-auto p-6 font-sans text-center py-20">
        <div class="bg-white border border-gray-100 rounded-2xl p-10 shadow-sm">
            <div class="text-6xl mb-6">&#9989;</div>

            <h1 class="text-3xl font-black text-slate-800 mb-3">
                Оплата прошла успешно!
            </h1>

            <p class="text-gray-500 mb-2">
                Заказ <span class="font-bold text-slate-800">#{{ order.id }}</span> оплачен
            </p>

            <p class="text-2xl font-black text-green-600 mb-8">
                {{ order.total.toFixed(2) }} $
            </p>

            <p class="text-sm text-gray-400 mb-8">
                Мы свяжемся с вами для уточнения деталей доставки.
            </p>

            <Link
                href="/"
                class="inline-block bg-slate-900 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-600 transition-colors"
            >
                Вернуться в каталог
            </Link>
        </div>
    </div>
</template>
