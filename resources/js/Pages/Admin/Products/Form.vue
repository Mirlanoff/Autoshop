<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

const props = defineProps({
    product: { type: Object, default: null },
    brands: Array,
    categories: Array,
})

const isEditing = computed(() => !!props.product)

const form = useForm({
    _method: isEditing.value ? 'PUT' : 'POST',
    name: props.product?.name || '',
    brand_id: props.product?.brand_id || '',
    category_id: props.product?.category_id || '',
    price: props.product?.price || '',
    stock: props.product?.stock ?? 0,
    description: props.product?.description || '',
    image: null,
})

const imagePreview = ref(props.product?.image || null)

const onImageChange = (e) => {
    const file = e.target.files[0]
    if (!file) return

    form.image = file

    const reader = new FileReader()
    reader.onload = (ev) => {
        imagePreview.value = ev.target.result
    }
    reader.readAsDataURL(file)
}

const removeImage = () => {
    form.image = null
    imagePreview.value = null
}

const submit = () => {
    if (isEditing.value) {
        form.post(`/admin/products/${props.product.id}`, {
            forceFormData: true,
        })
    } else {
        form.post('/admin/products', {
            forceFormData: true,
        })
    }
}
</script>

<template>
    <div class="max-w-3xl mx-auto p-6">
        <Link href="/admin/products" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6 transition-colors">
            &larr; Назад к товарам
        </Link>

        <h1 class="text-3xl font-black mb-8 text-slate-800">
            {{ isEditing ? 'Редактирование товара' : 'Новый товар' }}
        </h1>

        <form @submit.prevent="submit" class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-5">
            <!-- Изображение -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Изображение товара</label>
                <div class="flex items-start gap-6">
                    <div class="w-40 h-40 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center overflow-hidden">
                        <img
                            v-if="imagePreview"
                            :src="imagePreview"
                            alt="Preview"
                            class="w-full h-full object-cover"
                        />
                        <span v-else class="text-gray-300 text-4xl">📷</span>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="cursor-pointer bg-slate-900 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-600 transition-colors inline-block text-center">
                            {{ imagePreview ? 'Заменить фото' : 'Загрузить фото' }}
                            <input
                                type="file"
                                accept="image/*"
                                @change="onImageChange"
                                class="hidden"
                            />
                        </label>
                        <button
                            v-if="imagePreview"
                            type="button"
                            @click="removeImage"
                            class="text-red-500 hover:text-red-700 text-sm font-medium transition-colors"
                        >
                            Удалить фото
                        </button>
                        <p class="text-xs text-gray-400">JPG, PNG до 2 МБ</p>
                    </div>
                </div>
                <p v-if="form.errors.image" class="text-red-500 text-sm mt-1">{{ form.errors.image }}</p>
            </div>

            <!-- Название -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Название *</label>
                <input
                    v-model="form.name"
                    type="text"
                    placeholder="Масляный фильтр OE"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                />
                <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
            </div>

            <!-- Бренд и Категория -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Бренд *</label>
                    <select
                        v-model="form.brand_id"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-white outline-none cursor-pointer"
                    >
                        <option value="" disabled>Выберите бренд</option>
                        <option v-for="b in brands" :key="b.id" :value="b.id">{{ b.name }}</option>
                    </select>
                    <p v-if="form.errors.brand_id" class="text-red-500 text-sm mt-1">{{ form.errors.brand_id }}</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Категория *</label>
                    <select
                        v-model="form.category_id"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-white outline-none cursor-pointer"
                    >
                        <option value="" disabled>Выберите категорию</option>
                        <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                    </select>
                    <p v-if="form.errors.category_id" class="text-red-500 text-sm mt-1">{{ form.errors.category_id }}</p>
                </div>
            </div>

            <!-- Цена и Склад -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Цена ($) *</label>
                    <input
                        v-model="form.price"
                        type="number"
                        step="0.01"
                        min="0.01"
                        placeholder="45.00"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                    />
                    <p v-if="form.errors.price" class="text-red-500 text-sm mt-1">{{ form.errors.price }}</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">На складе (шт.) *</label>
                    <input
                        v-model="form.stock"
                        type="number"
                        min="0"
                        placeholder="100"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                    />
                    <p v-if="form.errors.stock" class="text-red-500 text-sm mt-1">{{ form.errors.stock }}</p>
                </div>
            </div>

            <!-- Описание -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Описание</label>
                <textarea
                    v-model="form.description"
                    rows="4"
                    placeholder="Подробное описание товара..."
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition-all resize-none"
                ></textarea>
                <p v-if="form.errors.description" class="text-red-500 text-sm mt-1">{{ form.errors.description }}</p>
            </div>

            <!-- Кнопка -->
            <div class="flex gap-4">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="flex-1 bg-slate-900 text-white py-4 rounded-xl font-bold text-lg hover:bg-blue-600 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed"
                >
                    <span v-if="form.processing" class="animate-pulse">Сохранение...</span>
                    <span v-else>{{ isEditing ? 'Сохранить изменения' : 'Создать товар' }}</span>
                </button>
                <Link
                    href="/admin/products"
                    class="px-8 py-4 border border-gray-200 rounded-xl font-bold text-slate-600 hover:bg-gray-50 transition-colors flex items-center"
                >
                    Отмена
                </Link>
            </div>
        </form>
    </div>
</template>
