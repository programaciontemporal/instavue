/**
 * @file Posts/Show.vue
 * @description Componente para mostrar una publicación individual
 * Incluye la funcionalidad de comentarios, likes y acciones de edición/eliminación
 * para el propietario de la publicación
 */
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';
import { Heart, MessageSquare, Edit, Trash2 } from 'lucide-vue-next';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';

interface User {
    id: number;
    name: string;
    profile_photo_url: string | null;
    avatar_url: string | null;
}

interface Post {
    id: number;
    user_id: number;
    caption: string;
    image_path: string;
    image_url: string;
    created_at: string;
    is_liked_by_auth_user: boolean;
    user: User;
    comments: Comment[];
    likes_count: number;
    comments_count: number;
}

interface Comment {
    id: number;
    body: string;
    created_at: string;
    user: User;
}

interface Props {
    post: Post;
    authUserId: number | null;
}

const props = defineProps<Props>();

const commentForm = useForm({
    body: '',
    post_id: props.post.id,
});

const likeForm = useForm({});
const deleteForm = useForm({});

/**
 * Maneja el envío de un nuevo comentario
 */
const submitComment = () => {
    commentForm.post(route('comments.store', props.post.id), {
        onSuccess: () => {
            commentForm.reset('body');
            toast.success('Comentario añadido.', {
                description: 'Tu comentario ha sido publicado.',
            });
        },
        onError: (errors) => {
            console.error('Error al comentar:', errors);
            toast.error('Error al comentar.', {
                description: 'No se pudo añadir tu comentario. Inténtalo de nuevo.',
            });
        },
        preserveScroll: true,
    });
};

/**
 * Maneja la acción de dar/quitar like a una publicación
 */
const toggleLike = () => {
    if (!props.authUserId) {
        toast.error('Acción no permitida', {
            description: 'Debes iniciar sesión para dar "Me gusta".',
        });
        return;
    }

    if (props.post.is_liked_by_auth_user) {
        likeForm.delete(route('likes.destroy', props.post.id), {
            preserveScroll: true,
        });
    } else {
        likeForm.post(route('likes.store', props.post.id), {
            preserveScroll: true,
        });
    }
};

/**
 * Maneja la eliminación de una publicación
 */
const deletePost = () => {
    if (confirm('¿Estás seguro de que quieres eliminar esta publicación?')) {
        deleteForm.delete(route('posts.destroy', props.post.id), {
            onSuccess: () => {
                toast.success('Publicación eliminada', {
                    description: 'La publicación ha sido eliminada correctamente.',
                });
            },
        });
    }
};
</script>

<template>
    <Head :title="post.caption" />

    <AppLayout>
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                <!-- Encabezado de la publicación -->
                <div class="p-4 border-b dark:border-gray-700 flex items-center">
                    <Avatar class="h-10 w-10">
                        <AvatarImage
                            v-if="post.user.avatar_url"
                            :src="post.user.avatar_url"
                            :alt="post.user.name"
                            class="object-cover w-full h-full"
                        />
                        <AvatarFallback>{{ getInitials(post.user.name) }}</AvatarFallback>
                    </Avatar>

                    <div class="ml-3">
                        <Link :href="route('profile.show', post.user.id)" class="font-semibold text-gray-900 dark:text-gray-100 hover:underline">
                            {{ post.user.name }}
                        </Link>
                    </div>

                    <!-- Botones de edición/eliminación -->
                    <div v-if="authUserId === post.user_id" class="ml-auto flex space-x-2 items-center">
                        <Link :href="route('posts.edit', post.id)">
                            <Button variant="ghost" size="icon">
                                <Edit class="h-5 w-5" />
                            </Button>
                        </Link>
                        <Button variant="ghost" size="icon" @click="deletePost">
                            <Trash2 class="h-5 w-5" />
                        </Button>
                    </div>
                </div>

                <!-- Imagen de la publicación -->
                <div class="relative">
                    <img :src="post.image_path" :alt="post.caption" class="w-full object-cover aspect-square rounded-md mb-4 shadow-sm">
                </div>

                <!-- Acciones y estadísticas -->
                <div class="px-4 py-3 flex items-center space-x-4">
                    <Button variant="ghost" size="icon" @click="toggleLike" :class="{ 'text-red-500': post.is_liked_by_auth_user }">
                        <Heart class="h-6 w-6" :fill="post.is_liked_by_auth_user ? 'currentColor' : 'none'" />
                    </Button>
                    <div class="flex space-x-4">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ post.likes_count }} Me gusta</span>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ post.comments_count }} Comentarios</span>
                    </div>
                </div>

                <!-- Pie de foto -->
                <div class="px-4 py-3 border-t dark:border-gray-700">
                    <p class="text-gray-800 dark:text-gray-200">{{ post.caption }}</p>
                </div>

                <!-- Sección de comentarios -->
                <div class="px-4 py-3 border-t dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Comentarios</h3>

                    <!-- Formulario de comentarios -->
                    <form @submit.prevent="submitComment" class="mb-6">
                        <div class="flex space-x-2">
                            <Input
                                v-model="commentForm.body"
                                type="text"
                                placeholder="Añade un comentario..."
                                class="flex-1"
                            />
                            <Button type="submit" :disabled="commentForm.processing">
                                Comentar
                            </Button>
                        </div>
                        <InputError :message="commentForm.errors.body" class="mt-2" />
                    </form>

                    <!-- Lista de comentarios -->
                    <div class="space-y-4">
                        <div v-for="comment in post.comments" :key="comment.id" class="flex space-x-3">
                            <Avatar class="h-8 w-8">
                                <AvatarImage
                                    v-if="comment.user.avatar_url"
                                    :src="comment.user.avatar_url"
                                    :alt="comment.user.name"
                                    class="object-cover w-full h-full"
                                />
                                <AvatarFallback>{{ getInitials(comment.user.name) }}</AvatarFallback>
                            </Avatar>
                            <div class="flex-1">
                                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3">
                                    <Link :href="route('profile.show', comment.user.id)" class="font-semibold text-gray-900 dark:text-gray-100 hover:underline">
                                        {{ comment.user.name }}
                                    </Link>
                                    <p class="text-gray-800 dark:text-gray-200">{{ comment.body }}</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ comment.created_at }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
