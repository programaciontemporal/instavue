<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button'; // Usar Button de shadcn/ui
import { Input } from '@/components/ui/input'; // Usar Input de shadcn/ui
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'; // Importar Avatar
import { getInitials } from '@/composables/useInitials'; // Importar getInitials
import { Heart, MessageSquare } from 'lucide-vue-next'; // Importar iconos de lucide
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue'; // Importar computed

const props = defineProps({
    posts: Array,
    authUserId: Number,
});

// Función para inicializar el formulario de comentario para cada post
const getCommentForm = (postId: number) => {
    return useForm({
        body: '',
        post_id: postId,
    });
};

const submitComment = (form: any, postId: number) => {
    form.post(route('comments.store', postId), {
        onSuccess: () => {
            form.reset('body');
            // Opcional: Recargar la página o actualizar el post para ver el nuevo comentario
            // router.reload({ only: ['posts'] });
        },
        onError: (errors: any) => {
            console.error(errors);
        },
        preserveScroll: true,
    });
};

const likeForm = useForm({});

const toggleLike = (post: any) => {
    if (post.is_liked_by_auth_user) {
        likeForm.delete(route('likes.destroy', post.id), {
            onSuccess: () => {
                post.is_liked_by_auth_user = false;
                post.likes_count--; // Decrementar contador
            },
            onError: (errors: any) => {
                console.error('Error al quitar like:', errors);
            },
            preserveScroll: true,
        });
    } else {
        likeForm.post(route('likes.store', post.id), {
            onSuccess: () => {
                post.is_liked_by_auth_user = true;
                post.likes_count++; // Incrementar contador
            },
            onError: (errors: any) => {
                console.error('Error al dar like:', errors);
            },
            preserveScroll: true,
        });
    }
};
</script>

<template>

    <Head title="Feed Principal" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Feed</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="posts && posts.length > 0">
                    <div v-for="post in posts" :key="post.id"
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <Avatar
                                    class="size-10 overflow-hidden rounded-full border border-gray-200 dark:border-gray-700 mr-3">
                                    <AvatarImage v-if="post.user.avatar" :src="post.user.avatar" :alt="post.user.name"
                                        class="object-cover w-full h-full" />
                                    <AvatarFallback
                                        class="rounded-full bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white text-sm">
                                        {{ getInitials(post.user.name) }}
                                    </AvatarFallback>
                                </Avatar>
                                <Link :href="route('profile.show', post.user.id)"
                                    class="font-bold text-gray-900 dark:text-gray-100">
                                {{ post.user.name }}
                                </Link>
                            </div>

                            <img :src="post.image_path" :alt="post.caption"
                                class="w-full object-cover aspect-square rounded-md mb-4">

                            <div class="flex items-center space-x-4 mb-2">
                                <Button @click="toggleLike(post)" :disabled="likeForm.processing" variant="ghost"
                                    size="icon"
                                    :class="post.is_liked_by_auth_user ? 'text-red-500 hover:text-red-600' : 'text-gray-600 dark:text-gray-400 hover:text-red-500'">
                                    <Heart :fill="post.is_liked_by_auth_user ? 'currentColor' : 'none'"
                                        class="w-6 h-6" />
                                </Button>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ post.likes_count
                                    }} Me
                                    gusta</span>

                                <Button variant="ghost" size="icon"
                                    class="text-gray-600 dark:text-gray-400 hover:text-blue-500">
                                    <MessageSquare class="w-6 h-6" />
                                </Button>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{
                                    post.comments_count }}
                                    Comentarios</span>
                            </div>

                            <p class="text-gray-700 dark:text-gray-300 mb-2">
                                <Link :href="route('profile.show', post.user.id)"
                                    class="font-bold text-gray-900 dark:text-gray-100 mr-1">
                                {{ post.user.name }}
                                </Link>
                                {{ post.caption }}
                            </p>

                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">{{ post.created_at }}</p>

                            <hr class="my-4 border-gray-200 dark:border-gray-700">

                            <div class="comments-section">
                                <h4 class="font-semibold text-lg text-gray-900 dark:text-gray-100 mb-2">Comentarios</h4>
                                <div v-if="post.comments && post.comments.length > 0">
                                    <div v-for="comment in post.comments" :key="comment.id" class="mb-2">
                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                            <Link :href="route('profile.show', comment.user.id)"
                                                class="font-semibold text-gray-900 dark:text-gray-100 mr-1">
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

                                <form @submit.prevent="submitComment(getCommentForm(post.id), post.id)" class="mt-4">
                                    <Input :id="`comment-body-${post.id}`" type="text" class="mt-1 block w-full"
                                        v-model="getCommentForm(post.id).body" placeholder="Añade un comentario..." />
                                    <InputError class="mt-2" :message="getCommentForm(post.id).errors.body" />
                                    <Button class="mt-2" :disabled="getCommentForm(post.id).processing">
                                        Comentar
                                    </Button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else
                    class="text-center text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    No hay publicaciones en tu feed. ¡Sigue a algunos usuarios para ver sus publicaciones!
                </div>
            </div>
        </div>
    </AppLayout>
</template>