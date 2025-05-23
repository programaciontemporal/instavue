<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import PostCard from '@/components/PostCard.vue';
import { Head, Link } from '@inertiajs/vue3';

// Importa los iconos de Lucide necesarios para la paginación
import { ArrowLeftIcon, ArrowRightIcon } from 'lucide-vue-next'; // ¡NUEVA LÍNEA!

defineProps({
    posts: Object, // Ahora 'posts' es un objeto paginado
    authUserId: Number, // Recibimos el ID del usuario autenticado
});
</script>

<template>
    <Head title="Explorar" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Explorar</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="posts.data && posts.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <PostCard v-for="post in posts.data" :key="post.id" :post="post" :auth-user-id="authUserId" />
                </div>
                <div v-else class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500 dark:text-gray-400">
                    <p>No hay publicaciones para mostrar en este momento.</p>
                </div>

                <div v-if="posts.links && posts.links.length > 3" class="flex justify-center mt-8">
                    <template v-for="link in posts.links" :key="link.label"> <Link
                            v-if="link.url"
                            :href="link.url"
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
        </div>
    </AppLayout>
</template>