<script setup>
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'

defineOptions({
    layout: AppLayout
})

const form = reactive({
    customer_name: '',
    phone: '',
})

const submit = () => {
    router.post('/checkout', form)
}
const form = useForm({
    customer_name: '',
    phone: '',
})

const submit = () => {
    form.post('/checkout')
}
</script>

<template>
    <div class="max-w-xl mx-auto p-6">
        <h1 class="text-2xl mb-6">Оформление заказа</h1>

        <input
            v-model="form.customer_name"
            placeholder="Имя"
            class="border p-2 w-full mb-4"
        />

        <input
            v-model="form.phone"
            placeholder="Телефон"
            class="border p-2 w-full mb-4"
        />

        <button
            @click="submit"
            class="bg-black text-white px-4 py-2 rounded"
        >
            Оформить заказ
        </button>
    </div>
</template>
