<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';
import { PencilIcon, PlusSquareIcon } from 'lucide-vue-next';
import PostCard from '@/components/PostCard.vue';

const props = defineProps({
    user: Object, // Datos del usuario del perfil que estamos viendo (incluye bio, contadores, is_following_auth_user)
    posts: Object, // Publicaciones paginadas de ese usuario
    authUserId: Number, // ID del usuario autenticado
});

// Lógica para seguir/dejar de seguir
const followForm = useForm({});

const toggleFollow = () => {
    // Si el usuario ya está siguiendo, se ejecuta la acción de "dejar de seguir"
    if (props.user.is_following_auth_user) {
        followForm.delete(route('unfollow', props.user.id), {
            preserveScroll: true, // Mantener la posición de scroll tras la petición
            onSuccess: () => {
                // Actualizar el estado local directamente en la prop 'user'
                props.user.is_following_auth_user = false;
                props.user.followers_count--; // Decrementar el contador de seguidores
            }
        });
    } else {
        // Si el usuario no está siguiendo, se ejecuta la acción de "seguir"
        followForm.post(route('follow', props.user.id), {
            preserveScroll: true, // Mantener la posición de scroll tras la petición
            onSuccess: () => {
                // Actualizar el estado local directamente en la prop 'user'
                props.user.is_following_auth_user = true;
                props.user.followers_count++; // Incrementar el contador de seguidores
            }
        });
    }
};

// Computada para determinar si el perfil que se está viendo es el del usuario autenticado
const isOwnProfile = computed(() => props.authUserId === props.user.id);
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
                                <Link v-if="isOwnProfile" :href="route('profile.edit')">
                                    <Button variant="outline" class="flex items-center space-x-2">
                                        <PencilIcon class="h-4 w-4" />
                                        <span>Editar Perfil</span>
                                    </Button>
                                </Link>

                                <Button v-else-if="authUserId" @click="toggleFollow"
                                        :variant="user.is_following_auth_user ? 'outline' : 'default'">
                                    {{ user.is_following_auth_user ? 'Dejar de seguir' : 'Seguir' }}
                                </Button>
                                <span v-else class="text-gray-500 text-sm">Inicia sesión para interactuar.</span>
                            </div>
                        </div>

                        <div class="flex space-x-6 text-gray-700 dark:text-gray-300 mb-4">
                            <div>
                                <span class="font-bold">{{ user.posts_count }}</span> publicaciones
                            </div>
                            <div>
                                <span class="font-bold">{{ user.followers_count }}</span> seguidores
                            </div>
                            <div>
                                <span class="font-bold">{{ user.following_count }}</span> seguidos
                            </div>
                        </div>

                        <p class="text-gray-700 dark:text-gray-300">{{ user.email }}</p>
                        <p v-if="user.bio" class="mt-2 text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ user.bio }}</p>
                        <p v-else class="mt-2 text-gray-500 dark:text-gray-400">Sin biografía.</p>
                    </div>
                </div>

                <hr class="my-6 border-gray-200 dark:border-gray-700">

                <h3 v-if="posts.data && posts.data.length > 0" class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Publicaciones
                </h3>
                <div v-if="posts.data && posts.data.length > 0"
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <PostCard v-for="post in posts.data" :key="post.id" :post="post" />
                </div>
                <div v-else class="text-center text-gray-500 dark:text-gray-400 p-6">
                    <p>{{ isOwnProfile ? 'Aún no tienes publicaciones.' : `Este usuario aún no tiene publicaciones.` }}</p>
                    <Link v-if="isOwnProfile" :href="route('posts.create')"
                        class="text-blue-500 hover:underline flex items-center justify-center mt-2">
                        <PlusSquareIcon class="h-4 w-4 mr-1" />
                        <span>¡Sé el primero en publicar!</span>
                    </Link>
                </div>

                <div v-if="posts.links && posts.links.length > 3" class="flex justify-center mt-8">
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
    </AppLayout>
</template>
