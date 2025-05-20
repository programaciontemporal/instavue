<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    posts: Array,
});
</script>

<template>

    <Head title="Explorar" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Explorar</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="posts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <div v-for="post in posts" :key="post.id" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <Link :href="route('profile.show', post.user.id)">
                        <img :src="`/storage/${post.image_path}`" :alt="post.caption" class="w-full h-80 object-cover">
                        </Link>
                        <div class="p-4">
                            <Link :href="route('profile.show', post.user.id)"
                                class="font-bold text-gray-900 block mb-2">
                            {{ post.user.name }}
                            </Link>
                            <p class="text-gray-700 text-sm">{{ post.caption.substring(0, 50) }}...</p>
                            <div class="mt-2 text-xs text-gray-500">
                                {{ post.likes.length }} Me gusta • {{ post.comments.length }} Comentarios
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center text-gray-500 bg-white p-6 rounded-lg shadow-sm">
                    No hay publicaciones disponibles para explorar en este momento.
                </div>
            </div>
        </div>
    </AppLayout>
</template>