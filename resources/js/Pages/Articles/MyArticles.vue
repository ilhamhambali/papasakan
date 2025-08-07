<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps({
    articles: Object,
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
                <div v-if="!articles.data.length" class="text-center bg-white p-8 rounded-lg shadow-sm">
                    <p class="text-gray-600 text-lg">Anda belum membuat resep apapun.</p>
                    <Link :href="route('articles.create')" class="mt-4 inline-block text-indigo-600 hover:underline font-semibold">
                        Buat Resep Pertama Anda
                    </Link>
                </div>

                <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <!-- Kartu resep (kode sama persis dengan halaman favorit) -->
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
                <Pagination class="mt-8" :links="articles.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
