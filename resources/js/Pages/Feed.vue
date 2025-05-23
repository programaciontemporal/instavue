/**
 * @file Feed.vue
 * @description Componente que muestra el feed principal del usuario
 * Presenta las publicaciones en un grid responsivo con paginación
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
}

defineProps<Props>();
</script>

<template>
    <Head title="Feed" />
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Tu Feed Principal
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Últimas Publicaciones</h3>

                    <div v-if="posts.data && posts.data.length > 0"
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <PostCard v-for="post in posts.data" :key="post.id" :post="post" />
                    </div>
                    <div v-else class="text-center text-gray-500 dark:text-gray-400">
                        <p>No hay publicaciones disponibles en este momento. ¡Sé el primero en publicar!</p>
                    </div>

                    <!-- Componente de paginación -->
                    <div v-if="posts.links && posts.links.length > 3" class="mt-8 flex justify-center">
                        <template v-for="link in posts.links" :key="link.label">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                class="px-3 py-1 mx-1 border rounded"
                                :class="{
                                    'bg-blue-500 text-white': link.active,
                                    'text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700': !link.active,
                                }"
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
        </div>
    </AppLayout>
</template>
