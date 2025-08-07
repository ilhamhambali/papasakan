<script setup>
import { Head, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import TextareaInput from '@/Components/TextareaInput.vue';

const props = defineProps({
    article: Object,
});

const form = useForm({
    _method: 'PUT',
    title: props.article.title,
    body: props.article.body,
    image: null,
});

const submit = () => {
    form.post(route('articles.update', props.article.id));
};

const destroy = () => {
    if (confirm('Apakah Anda yakin ingin menghapus artikel ini?')) {
        router.delete(route('articles.destroy', props.article.id));
    }
};
</script>

<template>
    <Head title="Edit Artikel" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Artikel</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div>
                                <InputLabel for="title" value="Judul Artikel" />
                                <TextInput id="title" type="text" class="mt-1 block w-full" v-model="form.title" required autofocus />
                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="body" value="Isi Konten" />
                                <TextareaInput id="body" class="mt-1 block w-full h-64" v-model="form.body" required />
                                <InputError class="mt-2" :message="form.errors.body" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="image" value="Ganti Gambar (Opsional)" />
                                <input type="file" @input="form.image = $event.target.files[0]" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"/>
                                <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                    {{ form.progress.percentage }}%
                                </progress>
                                <InputError class="mt-2" :message="form.errors.image" />
                                <div v-if="article.image" class="mt-4">
                                    <p class="text-sm text-gray-600">Gambar saat ini:</p>
                                    <img :src="`/storage/${article.image}`" class="mt-2 h-32 w-auto rounded-md" />
                                </div>
                            </div>

                            <div class="flex items-center justify-between mt-6">
                                <DangerButton type="button" @click="destroy">
                                    Hapus Artikel
                                </DangerButton>
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Perbarui Artikel
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
