<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps({
    recipes: Object,
});
</script>

<template>
    <Head title="Resep Saya" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Resep Saya</h2>
        </template>

        <div class="py-12">
            <div class="container mx-auto sm:px-6 lg:px-8">
                <div v-if="!recipes.data.length" class="text-center bg-white p-8 rounded-lg shadow-sm">
                    <p class="text-gray-600 text-lg">Anda belum membuat resep apapun.</p>
                    <Link :href="route('recipes.create')" class="mt-4 inline-block text-indigo-600 hover:underline font-semibold">
                        Buat Resep Pertama Anda
                    </Link>
                </div>

                <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <!-- Kartu resep (kode sama persis dengan halaman favorit) -->
                    <div v-for="recipe in recipes.data" :key="recipe.id" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <Link :href="route('recipes.show', recipe.slug)">
                            <img :src="recipe.image ? `/storage/${recipe.image}` : 'https://placehold.co/600x400/e2e8f0/e2e8f0?text=Resep'" alt="Gambar resep" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h2 class="text-lg font-semibold text-gray-900 truncate">{{ recipe.title }}</h2>
                                <p class="text-sm text-gray-600 mt-1">oleh {{ recipe.user.name }}</p>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span v-for="category in recipe.categories" :key="category.id" class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full">
                                        {{ category.name }}
                                    </span>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>
                <Pagination class="mt-8" :links="recipes.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
