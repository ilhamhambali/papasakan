<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ChatWidget from '@/Components/ChatWidget.vue';

// Cek status login dari props yang dibagikan oleh Inertia
const user = computed(() => usePage().props.auth.user);
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-100 shadow-sm">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <Link :href="route('home')">
                            <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                        </Link>
                    </div>

                    <!-- Link Navigasi Utama -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <div class="flex items-center space-x-8">
                            <Link :href="route('home')" class="text-gray-600 hover:text-gray-900 font-semibold">
                                Resep
                            </Link>
                            <Link :href="route('articles.index')" class="text-gray-600 hover:text-gray-900 font-semibold">
                                Artikel
                            </Link>
                        </div>

                        <!-- [BARU] Navigasi Kanan yang Dinamis -->
                        <div class="ms-6">
                            <!-- Jika Pengguna Sudah Login -->
                            <Dropdown v-if="user" align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                            {{ user.name }}
                                            <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>
                                <template #content>
                                    <DropdownLink :href="route('dashboard')"> Dashboard </DropdownLink>
                                    <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                    <DropdownLink :href="route('favorites.index')"> Favorit Saya </DropdownLink>
                                    <DropdownLink :href="route('recipes.mine')"> Resep Saya </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>

                            <!-- Jika Pengguna Belum Login -->
                            <div v-else>
                                <Link :href="route('login')" class="font-semibold text-gray-600 hover:text-gray-900">Log in</Link>
                                <Link :href="route('register')" class="ms-4 font-semibold text-gray-600 hover:text-gray-900">Register</Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Konten Halaman -->
        <main>
            <slot />
        </main>
        <ChatWidget />
    </div>
</template>
