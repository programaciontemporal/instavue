<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue'; // Importar computed
import { Button } from '@/components/ui/button'; // Importar Button de shadcn/ui
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'; // Importar Avatar de shadcn/ui
import { getInitials } from '@/composables/useInitials'; // Asumiendo que tienes esta función
import { PencilIcon, PlusSquareIcon } from 'lucide-vue-next'; // Importar iconos de lucide-vue-next

const props = defineProps({
    user: Object,
    posts: Array,
    postsCount: Number,
    followersCount: Number,
    followingCount: Number,
    isFollowing: Boolean,
    canEdit: Boolean, // Propiedad para saber si el usuario puede editar este perfil
});

const followForm = useForm({});

const toggleFollow = () => {
    if (props.isFollowing) {
        followForm.delete(route('unfollow', props.user.id));
    } else {
        followForm.post(route('follow', props.user.id));
    }
};

// Computed para el texto del botón de seguir
const followButtonText = computed(() => {
    return props.isFollowing ? 'Dejar de seguir' : 'Seguir';
});
</script>

<template>

    <Head :title="`Perfil de ${user.name}`" />

    <AppLayout>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center space-x-8 mb-8">
                    <Avatar
                        class="size-28 sm:size-32 md:size-40 overflow-hidden rounded-full border-2 border-gray-300 dark:border-gray-600">
                        <AvatarImage v-if="user.avatar" :src="user.avatar" :alt="user.name"
                            class="object-cover w-full h-full" />
                        <AvatarFallback
                            class="rounded-full bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white text-4xl">
                            {{ getInitials(user.name) }}
                        </AvatarFallback>
                    </Avatar>

                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ user.name }}</h2>
                            <div class="flex space-x-2">
                                <Link v-if="canEdit" :href="route('profile.edit')">
                                <Button variant="outline" class="flex items-center space-x-2">
                                    <PencilIcon class="h-4 w-4" />
                                    <span>Editar Perfil</span>
                                </Button>
                                </Link>
                                <Button v-if="!canEdit" @click="toggleFollow"
                                    :variant="isFollowing ? 'outline' : 'default'">
                                    {{ followButtonText }}
                                </Button>
                            </div>
                        </div>

                        <div class="flex space-x-6 text-gray-700 dark:text-gray-300 mb-4">
                            <div>
                                <span class="font-bold">{{ postsCount }}</span> publicaciones
                            </div>
                            <div>
                                <span class="font-bold">{{ followersCount }}</span> seguidores
                            </div>
                            <div>
                                <span class="font-bold">{{ followingCount }}</span> seguidos
                            </div>
                        </div>

                        <p class="text-gray-700 dark:text-gray-300">{{ user.email }}</p>
                    </div>
                </div>

                <hr class="my-6 border-gray-200 dark:border-gray-700">

                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Publicaciones</h3>
                <div v-if="posts.length > 0"
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div v-for="post in posts" :key="post.id"
                        class="relative group aspect-square overflow-hidden rounded-lg shadow-md">
                        <img :src="post.image_path" :alt="post.caption"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                        <div
                            class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="flex space-x-4 text-white font-bold">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.815 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                    </svg>
                                    <span>{{ post.likes_count }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.756 3 12.25c0 1.993.826 3.89 2.245 5.31l-.224.224H3.75a.75.75 0 0 0-.53 1.28L6.75 21l3.5-3.5a.75.75 0 0 0-.53-1.28H7.756c-1.42-.82-2.245-2.317-2.245-4.01V12.25c0-3.036 2.69-5.5 6-5.5s6 2.464 6 5.5-2.69 5.5-6 5.5Z" />
                                    </svg>
                                    <span>{{ post.comments_count }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center text-gray-500 dark:text-gray-400">
                    Este usuario aún no tiene publicaciones.
                    <Link v-if="canEdit" :href="route('posts.create')"
                        class="text-blue-500 hover:underline flex items-center justify-center mt-2">
                    <PlusSquareIcon class="h-4 w-4 mr-1" />
                    <span>¡Sé el primero en publicar!</span>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
