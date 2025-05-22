<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import PostCard from '@/components/PostCard.vue'; // Importar el componente PostCard
import { Head } from '@inertiajs/vue3';

defineProps({
    posts: Array, // Array de publicaciones a mostrar
});
</script>

<template>
    <Head title="Inicio" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Inicio</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="posts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <PostCard v-for="post in posts" :key="post.id" :post="post" />
                </div>
                <div v-else class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500 dark:text-gray-400">
                    <p>No hay publicaciones para mostrar.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
