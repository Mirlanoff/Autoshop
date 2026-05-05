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
            <div class="text-6xl mb-6">&#10060;</div>

            <h1 class="text-3xl font-black text-slate-800 mb-3">
                Оплата отменена
            </h1>

            <p class="text-gray-500 mb-8">
                Вы отменили оплату. Заказ сохранён, вы можете попробовать оплатить позже.
            </p>

            <div class="flex justify-center gap-4">
                <Link
                    href="/"
                    class="bg-slate-900 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-600 transition-colors"
                >
                    В каталог
                </Link>

                <Link
                    v-if="order"
                    href="/checkout"
                    class="bg-gray-100 text-slate-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors"
                >
                    Попробовать снова
                </Link>
            </div>
        </div>
    </div>
</template>
