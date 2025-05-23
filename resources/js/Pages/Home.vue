/**
 * @file Home.vue
 * @description Página principal de la aplicación que muestra el feed de publicaciones
 * Incluye paginación y manejo de estados vacíos.
 */
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import PostCard from '@/components/PostCard.vue';
import { Head, Link } from '@inertiajs/vue3';

interface Post {
    id: number;
    // ... otros campos del post
}

interface Props {
    posts: {
        data: Post[];
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
    };
    authUserId: number;
    user: Record<string, any>;
}

defineProps<Props>();
</script>

<template>
    <Head title="Inicio" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Feed</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="posts.data && posts.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <PostCard v-for="post in posts.data" :key="post.id" :post="post" :auth-user-id="authUserId" />
                </div>
                <div v-else class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500 dark:text-gray-400">
                    <p>No hay publicaciones para mostrar en tu feed.</p>
                    <p class="mt-2">Empieza a seguir a otros usuarios o <Link :href="route('posts.create')" class="text-blue-500 hover:underline">crea tu primera publicación</Link>.</p>
                </div>

                <div v-if="posts.links && posts.links.length > 3" class="flex justify-center mt-8">
                    <template v-for="link in posts.links" :key="link.label">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            v-html="link.label"
                            class="px-3 py-1 mx-1 border rounded"
                            :class="{ 'bg-blue-500 text-white': link.active, 'text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700': !link.active }"
                        />
                        <span
                            v-else
                            v-html="link.label"
                            class="px-3 py-1 mx-1 border rounded pointer-events-none opacity-50 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700"
                        ></span>
                    </template>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
