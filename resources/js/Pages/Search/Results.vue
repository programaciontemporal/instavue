<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import PostCard from '@/components/PostCard.vue';
import { Head, Link } from '@inertiajs/vue3';
// Asegúrate de importar los iconos de Lucide
import { ArrowLeftIcon, ArrowRightIcon } from 'lucide-vue-next';

defineProps({
    query: String,
    users: Object, // Ahora espera un objeto paginado de usuarios
    posts: Object, // Ahora espera un objeto paginado de posts
});

// Función auxiliar para generar URLs de paginación que incluyan el término de búsqueda
const getPaginationUrl = (url, currentQuery) => {
    if (!url) return null;

    const urlObj = new URL(url);
    if (currentQuery && !urlObj.searchParams.has('query')) {
        urlObj.searchParams.set('query', currentQuery);
    }
    return urlObj.toString();
};
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
                <div v-if="!query" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500 dark:text-gray-400">
                    <p>Por favor, introduce un término de búsqueda en la barra superior.</p>
                </div>

                <div v-else>
                    <h3 v-if="users.data && users.data.length > 0" class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                        Usuarios encontrados:
                    </h3>
                    <div v-if="users.data && users.data.length > 0" class="mb-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div v-for="user in users.data" :key="user.id" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4 flex items-center space-x-4">
                            <Link :href="route('profile.show', user.id)">
                                <img :src="user.avatar_url || '/images/default-avatar.png'" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
                            </Link>
                            <div>
                                <Link :href="route('profile.show', user.id)" class="text-gray-900 dark:text-gray-100 font-semibold hover:underline">
                                    {{ user.name }}
                                </Link>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ user.email }}</p>
                            </div>
                        </div>
                    </div>
                    <div v-if="users.links && users.links.length > 3" class="mb-8">
                        <div class="flex justify-center mt-4">
                            <template v-for="link in users.links" :key="link.label">
                                <Link
                                    v-if="link.url"
                                    :href="getPaginationUrl(link.url, query)"
                                    class="px-3 py-1 mx-1 border rounded flex items-center justify-center"
                                    :class="{ 'bg-blue-500 text-white': link.active, 'text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700': !link.active }"
                                >
                                    <template v-if="link.label === 'pagination.previous'">
                                        <ArrowLeftIcon class="size-4" />
                                    </template>
                                    <template v-else-if="link.label === 'pagination.next'">
                                        <ArrowRightIcon class="size-4" />
                                    </template>
                                    <span v-else v-html="link.label"></span>
                                </Link>
                                <span
                                    v-else
                                    class="px-3 py-1 mx-1 border rounded pointer-events-none opacity-50 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 flex items-center justify-center"
                                >
                                    <template v-if="link.label === 'pagination.previous'">
                                        <ArrowLeftIcon class="size-4" />
                                    </template>
                                    <template v-else-if="link.label === 'pagination.next'">
                                        <ArrowRightIcon class="size-4" />
                                    </template>
                                    <span v-else v-html="link.label"></span>
                                </span>
                            </template>
                        </div>
                    </div>

                    <hr v-if="(users.data && users.data.length > 0) && (posts.data && posts.data.length > 0)" class="my-6 border-gray-200 dark:border-gray-700">

                    <h3 v-if="posts.data && posts.data.length > 0" class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4" :class="{'mt-8': users.data && users.data.length === 0}">
                        Publicaciones encontradas:
                    </h3>
                    <div v-if="posts.data && posts.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <PostCard v-for="post in posts.data" :key="post.id" :post="post" />
                    </div>
                    <div v-if="posts.links && posts.links.length > 3" class="mt-8">
                        <div class="flex justify-center mt-4">
                            <template v-for="link in posts.links" :key="link.label">
                                <Link
                                    v-if="link.url"
                                    :href="getPaginationUrl(link.url, query)"
                                    class="px-3 py-1 mx-1 border rounded flex items-center justify-center"
                                    :class="{ 'bg-blue-500 text-white': link.active, 'text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700': !link.active }"
                                >
                                    <template v-if="link.label === 'pagination.previous'">
                                        <ArrowLeftIcon class="size-4" />
                                    </template>
                                    <template v-else-if="link.label === 'pagination.next'">
                                        <ArrowRightIcon class="size-4" />
                                    </template>
                                    <span v-else v-html="link.label"></span>
                                </Link>
                                <span
                                    v-else
                                    class="px-3 py-1 mx-1 border rounded pointer-events-none opacity-50 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 flex items-center justify-center"
                                >
                                    <template v-if="link.label === 'pagination.previous'">
                                        <ArrowLeftIcon class="size-4" />
                                    </template>
                                    <template v-else-if="link.label === 'pagination.next'">
                                        <ArrowRightIcon class="size-4" />
                                    </template>
                                    <span v-else v-html="link.label"></span>
                                </span>
                            </template>
                        </div>
                    </div>

                    <div v-if="(users.data && users.data.length === 0) && (posts.data && posts.data.length === 0) && query"
                         class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500 dark:text-gray-400">
                        <p>No se encontraron usuarios ni publicaciones que coincidan con "{{ query }}".</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>