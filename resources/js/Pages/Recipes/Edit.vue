<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextareaInput from '@/Components/TextareaInput.vue';

const props = defineProps({
    recipe: Object,
    categories: Array,
});

const form = useForm({
    _method: 'PUT', // Method spoofing untuk request PUT
    title: props.recipe.title,
    description: props.recipe.description,
    ingredients: props.recipe.ingredients,
    instructions: props.recipe.instructions,
    categories: props.recipe.categories.map(c => c.id), // Ambil ID dari kategori yang sudah ada
    image: null,
});

const submit = () => {
    form.post(route('recipes.update', props.recipe.id));
};
const destroy = () => {
    if (confirm('Apakah Anda yakin ingin menghapus resep ini? Aksi ini tidak dapat dibatalkan.')) {
        router.delete(route('recipes.destroy', props.recipe.id));
    }
};
</script>

<template>
    <Head title="Edit Resep" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Resep: {{ recipe.title }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <!-- Form input sama persis dengan Create.vue -->
                            <div class="mt-4">
                                <InputLabel for="image" value="Ganti Gambar (Opsional)" />
                                <input type="file" @input="form.image = $event.target.files[0]" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"/>
                                <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                    {{ form.progress.percentage }}%
                                </progress>
                                <InputError class="mt-2" :message="form.errors.image" />
                                <div v-if="recipe.image" class="mt-4">
                                    <p class="text-sm text-gray-600">Gambar saat ini:</p>
                                    <img :src="`/storage/${recipe.image}`" class="mt-2 h-32 w-auto rounded-md" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="title" value="Judul Resep" />
                                <TextInput id="title" type="text" class="mt-1 block w-full" v-model="form.title" required autofocus />
                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="description" value="Deskripsi Singkat" />
                                <TextareaInput id="description" class="mt-1 block w-full" v-model="form.description" required />
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="ingredients" value="Bahan-bahan (pisahkan dengan baris baru)" />
                                <TextareaInput id="ingredients" class="mt-1 block w-full h-40" v-model="form.ingredients" required />
                                <InputError class="mt-2" :message="form.errors.ingredients" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="instructions" value="Langkah-langkah (pisahkan dengan baris baru)" />
                                <TextareaInput id="instructions" class="mt-1 block w-full h-64" v-model="form.instructions" required />
                                <InputError class="mt-2" :message="form.errors.instructions" />
                            </div>

                            <div class="mt-4">
                                <InputLabel value="Kategori" />
                                <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-4">
                                    <label v-for="category in categories" :key="category.id" class="flex items-center">
                                        <input type="checkbox" :value="category.id" v-model="form.categories" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                                        <span class="ml-2 text-sm text-gray-600">{{ category.name }}</span>
                                    </label>
                                </div>
                                <InputError class="mt-2" :message="form.errors.categories" />
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <DangerButton type="button" @click="destroy">
                                    Hapus Resep
                                </DangerButton>

                                <!-- Tombol Perbarui di kanan -->
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Perbarui Resep
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
