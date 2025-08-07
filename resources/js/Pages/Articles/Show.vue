<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineProps({
    article: Object,
});
</script>

<template>
    <Head :title="article.title" />

    <GuestLayout>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
            <div class="max-w-4xl mx-auto">
                <!-- Tombol Kembali -->
                <div class="mb-6">
                    <Link :href="route('articles.index')" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                        &larr; Kembali ke Semua Artikel
                    </Link>
                </div>

                <!-- Konten Artikel -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden p-6 md:p-10">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight">{{ article.title }}</h1>
                    <p class="mt-2 text-md text-gray-600">
                        Ditulis oleh <span class="font-semibold">{{ article.user.name }}</span> pada {{ new Date(article.created_at).toLocaleDateString('id-ID') }}
                    </p>

                    <!-- Gambar Artikel (jika ada) -->
                    <img
                        v-if="article.image"
                        :src="article.image ? `/storage/${article.image}` : 'https://placehold.co/600x400/e2e8f0/e2e8f0?text=Resep'"
                        :alt="article.title"
                        class="w-full h-auto object-cover rounded-lg mt-6"
                    >

                    <!-- Isi Body Artikel -->
                    <div class="mt-8 prose prose-lg max-w-none text-gray-800 leading-relaxed" v-html="article.body">
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<style>
/* Menambahkan sedikit style untuk konten dari v-html agar lebih rapi */
.prose h2 {
    font-size: 1.5em;
    margin-top: 1.5em;
    margin-bottom: 0.5em;
    font-weight: 700;
}
.prose p {
    margin-bottom: 1em;
}
.prose ul {
    list-style-position: inside;
    list-style-type: disc;
    margin-bottom: 1em;
}
</style>
