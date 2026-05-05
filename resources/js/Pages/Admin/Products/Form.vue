<script setup>
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

defineOptions({ layout: AppLayout })

const props = defineProps({
    product: { type: Object, default: null },
    brands: Array,
    categories: Array,
})

const isEdit = !!props.product

const form = useForm({
    name: props.product?.name || '',
    price: props.product?.price || '',
    stock: props.product?.stock ?? 0,
    brand_id: props.product?.brand_id || '',
    category_id: props.product?.category_id || '',
    description: props.product?.description || '',
    image: null,
})

const imagePreview = ref(props.product?.image ? `/storage/${props.product.image}` : null)

const handleImage = (e) => {
    const file = e.target.files[0]
    if (file) {
        form.image = file
        imagePreview.value = URL.createObjectURL(file)
    }
}

const submit = () => {
    if (isEdit) {
        form.post(`/admin/products/${props.product.id}`, {
            forceFormData: true,
            _method: 'PUT',
        })
    } else {
        form.post('/admin/products', {
            forceFormData: true,
        })
    }
}
</script>

<template>
    <div class="max-w-2xl mx-auto p-6 font-sans">
        <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
            <Link href="/admin/products" class="hover:text-slate-800">Товары</Link>
            <span>/</span>
            <span class="text-slate-600">{{ isEdit ? 'Редактирование' : 'Новый товар' }}</span>
        </nav>

        <h1 class="text-2xl font-black text-slate-800 mb-6">
            {{ isEdit ? `Редактировать: ${product.name}` : 'Добавить товар' }}
        </h1>

        <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm space-y-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Название</label>
                <input v-model="form.name" type="text" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none" />
                <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Цена ($)</label>
                    <input v-model="form.price" type="number" step="0.01" min="0" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none" />
                    <p v-if="form.errors.price" class="text-red-500 text-sm mt-1">{{ form.errors.price }}</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Склад (шт.)</label>
                    <input v-model="form.stock" type="number" min="0" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none" />
                    <p v-if="form.errors.stock" class="text-red-500 text-sm mt-1">{{ form.errors.stock }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Бренд</label>
                    <select v-model="form.brand_id" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-white outline-none">
                        <option value="">Выберите бренд</option>
                        <option v-for="b in brands" :key="b.id" :value="b.id">{{ b.name }}</option>
                    </select>
                    <p v-if="form.errors.brand_id" class="text-red-500 text-sm mt-1">{{ form.errors.brand_id }}</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Категория</label>
                    <select v-model="form.category_id" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-white outline-none">
                        <option value="">Выберите категорию</option>
                        <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                    </select>
                    <p v-if="form.errors.category_id" class="text-red-500 text-sm mt-1">{{ form.errors.category_id }}</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Описание</label>
                <textarea v-model="form.description" rows="4" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none resize-none"></textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Изображение</label>
                <div class="flex items-center gap-4">
                    <div v-if="imagePreview" class="w-20 h-20 rounded-xl overflow-hidden bg-gray-50 border">
                        <img :src="imagePreview" class="w-full h-full object-cover" />
                    </div>
                    <input type="file" accept="image/*" @change="handleImage" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-slate-900 file:text-white file:font-bold file:cursor-pointer hover:file:bg-blue-600" />
                </div>
                <p v-if="form.errors.image" class="text-red-500 text-sm mt-1">{{ form.errors.image }}</p>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button
                    @click="submit"
                    :disabled="form.processing"
                    class="flex-1 bg-slate-900 text-white py-3 rounded-xl font-bold hover:bg-blue-600 transition-colors disabled:bg-gray-300"
                >
                    <span v-if="form.processing">Сохранение...</span>
                    <span v-else>{{ isEdit ? 'Сохранить изменения' : 'Добавить товар' }}</span>
                </button>
                <Link href="/admin/products" class="text-gray-500 hover:text-slate-800 font-medium">Отмена</Link>
            </div>
        </div>
    </div>
</template>
