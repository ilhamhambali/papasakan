<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'; // Layout untuk user login
import GuestLayout from '@/Layouts/GuestLayout.vue'; // Layout untuk tamu
import Pagination from '@/Components/Pagination.vue';

// Menerima props 'recipes' dari controller
defineProps({
    recipes: Object,
});
</script>

<template>
    <Head title="Semua Resep" />

    <!-- Kita bisa menggunakan layout yang berbeda jika perlu,
         tapi untuk sekarang kita pakai GuestLayout sebagai dasar -->
    <GuestLayout>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Jelajahi Resep Kami</h1>

            <!-- Grid untuk menampilkan resep -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <div v-for="recipe in recipes.data" :key="recipe.id" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <Link :href="route('recipes.show', recipe.slug)">
                        <img
                            :src="recipe.image ? `/storage/${recipe.image}` : 'https://placehold.co/600x400/e2e8f0/e2e8f0?text=Resep'"
                            alt="Gambar resep"
                            class="w-full h-48 object-cover"
                        >
                        <div class="p-4">
                            <h2 class="text-lg font-semibold text-gray-900 truncate">{{ recipe.title }}</h2>
                            <p class="text-sm text-gray-600 mt-1">oleh {{ recipe.user.name }}</p>

                            <!-- Menampilkan kategori -->
                            <div class="mt-3 flex flex-wrap gap-2">
                                <span v-for="category in recipe.categories" :key="category.id" class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full">
                                    {{ category.name }}
                                </span>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- Navigasi Paginasi akan kita tambahkan nanti -->
        <Pagination class="mt-8" :links="recipes.links" />
        </div>
    </GuestLayout>
</template>
