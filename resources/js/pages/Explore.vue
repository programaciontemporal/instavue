<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'; // Importar Avatar
import { getInitials } from '@/composables/useInitials'; // Importar getInitials
import { Heart, MessageSquare } from 'lucide-vue-next'; // Importar iconos de lucide
import { Button } from '@/components/ui/button'; // Importar Button
import { computed } from 'vue'; // Importar computed

const props = defineProps({
    posts: Array,
    authUserId: Number, // Recibir el ID del usuario autenticado
});

const likeForm = useForm({});

const toggleLike = (post) => {
    if (post.is_liked_by_auth_user) {
        likeForm.delete(route('likes.destroy', post.id), {
            onSuccess: () => {
                post.is_liked_by_auth_user = false;
                post.likes_count--;
            },
            onError: (errors) => {
                console.error('Error al quitar like:', errors);
            },
            preserveScroll: true,
        });
    } else {
        likeForm.post(route('likes.store', post.id), {
            onSuccess: () => {
                post.is_liked_by_auth_user = true;
                post.likes_count++;
            },
            onError: (errors) => {
                console.error('Error al dar like:', errors);
            },
            preserveScroll: true,
        });
    }
};
</script>

<template>

    <Head title="Explorar" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Explorar</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="posts && posts.length > 0"
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div v-for="post in posts" :key="post.id"
                        class="relative group aspect-square overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
                        <Link :href="route('profile.show', post.user.id)">
                        <img :src="post.image_path" :alt="post.caption"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                        </Link>
                        <div
                            class="absolute inset-0 bg-black bg-opacity-40 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 p-4">
                            <Link :href="route('profile.show', post.user.id)" class="flex items-center mb-2">
                            <Avatar
                                class="size-8 overflow-hidden rounded-full border border-gray-200 dark:border-gray-700 mr-2">
                                <AvatarImage v-if="post.user.avatar" :src="post.user.avatar" :alt="post.user.name"
                                    class="object-cover w-full h-full" />
                                <AvatarFallback
                                    class="rounded-full bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white text-sm">
                                    {{ getInitials(post.user.name) }}
                                </AvatarFallback>
                            </Avatar>
                            <span class="font-bold text-white">{{ post.user.name }}</span>
                            </Link>

                            <div class="flex space-x-4 text-white font-bold">
                                <div class="flex items-center">
                                    <Heart :fill="post.is_liked_by_auth_user ? 'currentColor' : 'none'"
                                        class="w-5 h-5 mr-1" />
                                    <span>{{ post.likes_count }}</span>
                                </div>
                                <div class="flex items-center">
                                    <MessageSquare class="w-5 h-5 mr-1" />
                                    <span>{{ post.comments_count }}</span>
                                </div>
                            </div>
                            <!-- <Button @click="toggleLike(post)" :disabled="likeForm.processing" variant="ghost" size="icon"
                                    :class="post.is_liked_by_auth_user ? 'text-red-500 hover:text-red-600' : 'text-white hover:text-red-500'">
                                <Heart :fill="post.is_liked_by_auth_user ? 'currentColor' : 'none'" class="w-6 h-6" />
                            </Button> -->
                        </div>
                    </div>
                </div>
                <div v-else
                    class="text-center text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    No hay publicaciones disponibles para explorar en este momento.
                </div>
            </div>
        </div>
    </AppLayout>
</template>
