<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import PostCard from '@/components/PostCard.vue';
import { Head, Link } from '@inertiajs/vue3'; // Importar Link para los enlaces de usuarios

defineProps({
    query: String,
    users: Object, // Ahora espera un objeto paginado de usuarios
    posts: Object, // Ahora espera un objeto paginado de posts
});
</script>

<template>
    <Head :title="`Resultados para '${query}'`" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Resultados de búsqueda para: <span class="font-bold">{{ query }}</span>
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h3 v-if="users.data && users.data.length > 0" class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    Usuarios encontrados:
                </h3>
                <div v-if="users.data && users.data.length > 0" class="mb-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="user in users.data" :key="user.id" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4 flex items-center space-x-4">
                        <Link :href="route('profile.show', user.id)">
                            <img :src="user.avatar || '/images/default-avatar.png'" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
                        </Link>
                        <div>
                            <Link :href="route('profile.show', user.id)" class="text-gray-900 dark:text-gray-100 font-semibold hover:underline">
                                {{ user.name }}
                            </Link>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ user.email }}</p>
                        </div>
                    </div>
                </div>
                <div v-if="users.data && users.data.length > 0" class="mb-8">
                    <div class="flex justify-center mt-4">
                        <Link v-for="link in users.links" :key="link.url"
                              :href="link.url"
                              v-html="link.label"
                              class="px-3 py-1 mx-1 border rounded"
                              :class="{ 'bg-blue-500 text-white': link.active, 'text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700': !link.active, 'pointer-events-none opacity-50': !link.url }"
                        />
                    </div>
                </div>


                <h3 v-if="posts.data && posts.data.length > 0" class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 mt-8">
                    Publicaciones encontradas:
                </h3>
                <div v-if="posts.data && posts.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <PostCard v-for="post in posts.data" :key="post.id" :post="post" />
                </div>
                <div v-if="posts.data && posts.data.length > 0" class="mt-8">
                    <div class="flex justify-center mt-4">
                        <Link v-for="link in posts.links" :key="link.url"
                              :href="link.url"
                              v-html="link.label"
                              class="px-3 py-1 mx-1 border rounded"
                              :class="{ 'bg-blue-500 text-white': link.active, 'text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700': !link.active, 'pointer-events-none opacity-50': !link.url }"
                        />
                    </div>
                </div>

                <div v-if="(users.data && users.data.length === 0) && (posts.data && posts.data.length === 0)"
                     class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500 dark:text-gray-400">
                    <p>No se encontraron usuarios ni publicaciones que coincidan con "{{ query }}".</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
