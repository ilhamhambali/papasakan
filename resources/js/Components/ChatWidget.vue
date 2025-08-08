<script setup>
import { ref } from 'vue';
import axios from 'axios';

const isOpen = ref(false);
const isLoading = ref(false);
const messages = ref([]);
const userMessage = ref('');

const toggleChat = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value && messages.value.length === 0) {
        // Pesan sambutan awal
        messages.value.push({
            id: Date.now(),
            text: 'Halo! Saya Chef Bot, asisten masak Anda. Ada yang bisa saya bantu seputar resep atau tips memasak?',
            sender: 'ai'
        });
    }
};

const sendMessage = async () => {
    if (userMessage.value.trim() === '') return;

    const currentMessage = userMessage.value;
    messages.value.push({ id: Date.now(), text: currentMessage, sender: 'user' });
    userMessage.value = '';
    isLoading.value = true;

    try {
        const response = await axios.post(route('ai.chat'), {
            message: currentMessage
        });
        messages.value.push({ id: Date.now() + 1, text: response.data.reply, sender: 'ai' });
    } catch (error) {
        messages.value.push({ id: Date.now() + 1, text: 'Maaf, terjadi kesalahan. Coba lagi nanti ya.', sender: 'ai' });
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <div class="fixed bottom-5 right-5 z-50">
        <!-- Chat Bubble Button -->
        <button @click="toggleChat" class="bg-indigo-600 text-white w-16 h-16 rounded-full flex items-center justify-center shadow-lg hover:bg-indigo-700 transition-transform transform hover:scale-110">
            <svg v-if="!isOpen" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Chat Window -->
        <div v-if="isOpen" class="absolute bottom-20 right-0 w-80 sm:w-96 bg-white rounded-lg shadow-2xl flex flex-col" style="height: 500px;">
            <!-- Header -->
            <div class="bg-indigo-600 text-white p-4 rounded-t-lg">
                <h3 class="font-semibold text-lg">Chef Bot Assistant</h3>
            </div>

            <!-- Messages -->
            <div class="flex-1 p-4 overflow-y-auto">
                <div v-for="message in messages" :key="message.id" class="mb-4">
                    <div :class="[message.sender === 'ai' ? 'text-left' : 'text-right']">
                        <div class="inline-block p-3 rounded-lg" :class="[message.sender === 'ai' ? 'bg-gray-200 text-gray-800' : 'bg-indigo-500 text-white']">
                            {{ message.text }}
                        </div>
                    </div>
                </div>
                 <div v-if="isLoading" class="text-left">
                    <div class="inline-block p-3 rounded-lg bg-gray-200 text-gray-500">
                        <span class="animate-pulse">Chef Bot sedang mengetik...</span>
                    </div>
                </div>
            </div>

            <!-- Input -->
            <div class="p-4 border-t">
                <form @submit.prevent="sendMessage" class="flex items-center">
                    <input type="text" v-model="userMessage" placeholder="Ketik pesan Anda..." class="flex-1 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" :disabled="isLoading">
                    <button type="submit" class="ml-3 bg-indigo-600 text-white p-2 rounded-lg hover:bg-indigo-700" :disabled="isLoading">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
