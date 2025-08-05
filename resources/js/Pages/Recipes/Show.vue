<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue'; // Tambahkan ref
import { router } from '@inertiajs/vue3'; // Tambahkan router
import GuestLayout from '@/Layouts/GuestLayout.vue';
const props = defineProps({
    recipe: Object,
    isFavorited: Boolean, // Terima prop baru
});

// Buat state lokal agar tombol bisa langsung berubah
const favorited = ref(props.isFavorited);

const user = computed(() => usePage().props.auth.user);
const canUpdate = computed(() => {
    if (!user.value) return false;
    return user.value.id === props.recipe.user_id;
});

// Fungsi untuk toggle favorit
const toggleFavorite = () => {
    if (!user.value) {
        return router.get(route('login'));
    }
    router.post(route('favorites.toggle', props.recipe.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            favorited.value = !favorited.value;
        }
    });
};
</script>

<template>
    <Head :title="recipe.title" />

    <GuestLayout>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
                <img
                    :src="recipe.image ? `/storage/${recipe.image}` : 'https://placehold.co/600x400/e2e8f0/e2e8f0?text=Resep'"
                    :alt="recipe.title"
                    class="w-full h-64 object-cover"
                >
                <div class="p-6 md:p-8 relative">
                    <button @click="toggleFavorite" class="absolute top-4 right-4 p-2 rounded-full bg-white/70 backdrop-blur-sm shadow-md hover:bg-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" :class="[favorited ? 'text-red-500' : 'text-gray-400']" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <!-- Kategori -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span v-for="category in recipe.categories" :key="category.id" class="px-3 py-1 bg-indigo-100 text-indigo-800 text-sm font-semibold rounded-full">
                            {{ category.name }}
                        </span>
                    </div>

                    <!-- Judul dan Penulis -->
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">{{ recipe.title }}</h1>
                    <p class="mt-2 text-md text-gray-600">Dibuat oleh: <span class="font-semibold">{{ recipe.user.name }}</span> pada {{ new Date(recipe.created_at).toLocaleDateString('id-ID') }}</p>

                    <!-- Deskripsi -->
                    <p class="mt-6 text-gray-700 text-lg leading-relaxed">
                        {{ recipe.description }}
                    </p>

                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Bahan-bahan -->
                        <div class="md:col-span-1">
                            <h2 class="text-2xl font-bold text-gray-800 border-b-2 border-indigo-500 pb-2">Bahan-bahan</h2>
                            <div class="mt-4 text-gray-700 whitespace-pre-line prose">
                                {{ recipe.ingredients }}
                            </div>
                        </div>

                        <!-- Langkah-langkah -->
                        <div class="md:col-span-2">
                            <h2 class="text-2xl font-bold text-gray-800 border-b-2 border-indigo-500 pb-2">Langkah-langkah</h2>
                            <div class="mt-4 text-gray-700 whitespace-pre-line prose">
                                {{ recipe.instructions }}
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Kembali -->
                    <div class="mt-10 flex justify-center items-center gap-4">
                        <Link :href="route('home')" class="inline-block px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition-colors">
                            &larr; Semua Resep
                        </Link>
                        <Link v-if="canUpdate" :href="route('recipes.edit', recipe.id)" class="inline-block px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors">
                            Edit Resep
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<style scoped>
/* Menambahkan styling untuk format teks dari database */
.prose {
    line-height: 1.75;
}
</style>
