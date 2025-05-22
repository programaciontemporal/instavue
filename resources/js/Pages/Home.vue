<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import PostCard from '@/components/PostCard.vue';
import { Head, Link } from '@inertiajs/vue3'; // Importar Link para la paginación

defineProps({
    posts: Object, // Ahora 'posts' es un objeto paginado
    authUserId: Number,
    user: Object, // Prop para el usuario autenticado (si es necesario para el layout o el componente)
});
</script>

<template>
    <Head title="Inicio" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Feed</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="posts.data && posts.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <PostCard v-for="post in posts.data" :key="post.id" :post="post" />
                </div>
                <div v-else class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500 dark:text-gray-400">
                    <p>No hay publicaciones para mostrar en tu feed.</p>
                    <p class="mt-2">Empieza a seguir a otros usuarios o crea tu primera publicación.</p>
                </div>

                <div v-if="posts.links && posts.links.length > 3" class="flex justify-center mt-8">
                    <Link v-for="link in posts.links" :key="link.url"
                          :href="link.url"
                          v-html="link.label"
                          class="px-3 py-1 mx-1 border rounded"
                          :class="{ 'bg-blue-500 text-white': link.active, 'text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700': !link.active, 'pointer-events-none opacity-50': !link.url }"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
