<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps({
    articles: Object,
});
</script>

<template>
    <Head title="Artikel Masakan" />

    <GuestLayout>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Artikel & Tips Memasak</h1>

            <div class="max-w-4xl mx-auto space-y-8">
                <!-- Loop untuk setiap artikel -->
                <div v-for="article in articles.data" :key="article.id" class="bg-white rounded-lg shadow-md overflow-hidden">
                    <Link :href="route('articles.show', article.slug)">
                        <div class="p-6">
                            <h2 class="text-2xl font-semibold text-gray-900 hover:text-indigo-600 transition-colors">{{ article.title }}</h2>
                            <p class="text-sm text-gray-600 mt-2">
                                oleh {{ article.user.name }} - {{ new Date(article.created_at).toLocaleDateString('id-ID') }}
                            </p>
                            <p class="mt-4 text-gray-700 line-clamp-3">
                                {{ article.body }}
                            </p>
                            <div class="mt-4 text-indigo-600 font-semibold hover:underline">
                                Baca Selengkapnya &rarr;
                            </div>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- Paginasi -->
            <Pagination class="mt-8" :links="articles.links" />
        </div>
    </GuestLayout>
</template>
