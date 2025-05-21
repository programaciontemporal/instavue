<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';
import { Heart, MessageSquare, Edit, Trash2 } from 'lucide-vue-next';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps({
    post: Object,
    authUserId: Number,
});

const commentForm = useForm({
    body: '',
    post_id: props.post.id,
});

const likeForm = useForm({});
const deleteForm = useForm({});

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

const toggleLike = () => {
    if (props.post.is_liked_by_auth_user) {
        likeForm.delete(route('likes.destroy', props.post.id), {
            onSuccess: () => {
                props.post.is_liked_by_auth_user = false;
                props.post.likes_count--;
            },
            onError: (errors) => {
                console.error('Error al quitar like:', errors);
            },
            preserveScroll: true,
        });
    } else {
        likeForm.post(route('likes.store', props.post.id), {
            onSuccess: () => {
                props.post.is_liked_by_auth_user = true;
                props.post.likes_count++;
            },
            onError: (errors) => {
                console.error('Error al dar like:', errors);
            },
            preserveScroll: true,
        });
    }
};

const deletePost = () => {
    if (confirm('¿Estás seguro de que quieres eliminar esta publicación? Esta acción no se puede deshacer.')) {
        deleteForm.delete(route('posts.destroy', props.post.id), {
            onSuccess: () => {
                toast.success('Publicación eliminada.', {
                    description: 'La publicación ha sido eliminada con éxito.',
                });
                window.location.href = route('profile.show', props.authUserId);
            },
            onError: (errors) => {
                console.error('Error al eliminar post:', errors);
                toast.error('Error al eliminar.', {
                    description: 'No se pudo eliminar la publicación. Inténtalo de nuevo.',
                });
            },
        });
    }
};
</script>

<template>
    <Head :title="`Publicación de ${post.user.name}`" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Publicación de {{ post.user.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <Avatar class="size-10 overflow-hidden rounded-full border border-gray-200 dark:border-gray-700 mr-3">
                                <AvatarImage v-if="post.user.avatar" :src="post.user.avatar" :alt="post.user.name" class="object-cover w-full h-full" />
                                <AvatarFallback class="rounded-full bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white text-sm">
                                    {{ getInitials(post.user.name) }}
                                </AvatarFallback>
                            </Avatar>
                            <Link :href="route('profile.show', post.user.id)" class="font-bold text-gray-900 dark:text-gray-100">
                                {{ post.user.name }}
                            </Link>

                            <div v-if="authUserId === post.user_id" class="ml-auto flex space-x-2 items-center">
                                <Button as-child variant="ghost" size="icon" class="text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-500" title="Editar publicación">
                                    <Link :href="route('posts.edit', post.id)">
                                        <Edit class="w-6 h-6" /> </Link>
                                </Button>
                                <Button @click="deletePost" variant="ghost" size="icon" class="text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-500" title="Eliminar publicación">
                                    <Trash2 class="w-6 h-6" /> </Button>
                            </div>
                            </div>

                        <img :src="post.image_path" :alt="post.caption" class="w-full object-cover aspect-square rounded-md mb-4 shadow-sm">

                        <div class="flex items-center space-x-4 mb-2">
                            <Button @click="toggleLike" :disabled="likeForm.processing" variant="ghost" size="icon"
                                :class="post.is_liked_by_auth_user ? 'text-red-500 hover:text-red-600' : 'text-gray-600 dark:text-gray-400 hover:text-red-500'">
                                <Heart :fill="post.is_liked_by_auth_user ? 'currentColor' : 'none'" class="w-6 h-6" />
                            </Button>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ post.likes_count }} Me gusta</span>

                            <Button variant="ghost" size="icon" class="text-gray-600 dark:text-gray-400 hover:text-blue-500">
                                <MessageSquare class="w-6 h-6" />
                            </Button>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ post.comments_count }} Comentarios</span>
                        </div>

                        <p class="text-gray-700 dark:text-gray-300 mb-2">
                            <Link :href="route('profile.show', post.user.id)" class="font-bold text-gray-900 dark:text-gray-100 mr-1">
                                {{ post.user.name }}
                            </Link>
                            {{ post.caption }}
                        </p>

                        <hr class="my-4 border-gray-200 dark:border-gray-700">

                        <div class="comments-section">
                            <h4 class="font-semibold text-lg text-gray-900 dark:text-gray-100 mb-2">Comentarios</h4>
                            <div v-if="post.comments && post.comments.length > 0">
                                <div v-for="comment in post.comments" :key="comment.id" class="mb-2">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        <Link :href="route('profile.show', comment.user.id)" class="font-semibold text-gray-900 dark:text-gray-100 mr-1">
                                            {{ comment.user.name }}
                                        </Link>
                                        {{ comment.body }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ comment.created_at }}</p>
                                </div>
                            </div>
                            <div v-else class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                                Sé el primero en comentar.
                            </div>

                            <form @submit.prevent="submitComment" class="mt-4">
                                <Input :id="`comment-body-${post.id}`" type="text" class="mt-1 block w-full"
                                    v-model="commentForm.body" placeholder="Añade un comentario..." />
                                <InputError class="mt-2" :message="commentForm.errors.body" />
                                <Button class="mt-2" :disabled="commentForm.processing">
                                    Comentar
                                </Button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
