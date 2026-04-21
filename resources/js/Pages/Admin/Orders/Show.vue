<script setup>
import { router } from '@inertiajs/vue3'

const props = defineProps({
    order: Object
})

const updateStatus = (status) => {
    router.post(`/admin/orders/${props.order.id}/status`, {
        status
    })
}
</script>

<template>
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">
            Заказ #{{ order.id }}
        </h1>

        ```
        <div class="mb-6">
            <p><b>Имя:</b> {{ order.customer_name }}</p>
            <p><b>Телефон:</b> {{ order.phone }}</p>
            <p><b>Сумма:</b> {{ order.total }} $</p>
        </div>

        <!-- Товары -->
        <div class="mb-6">
            <h2 class="font-bold mb-2">Товары</h2>

            <div v-for="item in order.items" :key="item.id">
                {{ item.name }} — {{ item.quantity }} × {{ item.price }}
            </div>
        </div>

        <!-- Статус -->
        <div class="flex gap-3">
            <button
                @click="updateStatus('pending')"
                class="px-3 py-2 bg-gray-200 rounded"
            >
                Pending
            </button>

            <button
                @click="updateStatus('completed')"
                class="px-3 py-2 bg-green-600 text-white rounded"
            >
                Completed
            </button>
        </div>
        ```

    </div>
</template>
