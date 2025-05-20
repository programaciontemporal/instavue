<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import InputError from '@/components/InputError.vue';
import PrimaryButton from '@/components/ui/button/Button.vue';
import TextInput from '@/components/ui/input/Input.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue'; // Importar computed para propiedades reactivas

const props = defineProps({
    posts: Array,
    authUserId: Number, // Recibir el ID del usuario autenticado
});

// Función para inicializar el formulario de comentario para cada post
const getCommentForm = (postId) => {
    return useForm({
        body: '',
        post_id: postId,
    });
};

const submitComment = (form, postId) => {
    form.post(route('comments.store', postId), {
        onSuccess: () => {
            form.reset('body');
        },
        onError: (errors) => {
            console.error(errors);
        },
        preserveScroll: true, // Mantener la posición de scroll después del comentario
    });
};

// Formulario para manejar el like/unlike
const likeForm = useForm({}); // Un formulario vacío es suficiente para POST/DELETE

const toggleLike = (post) => {
    if (post.is_liked_by_auth_user) {
        // Si ya le dio like, quitárselo (DELETE)
        likeForm.delete(route('likes.destroy', post.id), {
            onSuccess: () => {
                // Actualizar el estado del post localmente
                post.is_liked_by_auth_user = false;
                post.likes = post.likes.filter(like => like.user_id !== props.authUserId);
            },
            onError: (errors) => {
                console.error('Error al quitar like:', errors);
            },
            preserveScroll: true,
        });
    } else {
        // Si no le dio like, dárselo (POST)
        likeForm.post(route('likes.store', post.id), {
            onSuccess: () => {
                // Actualizar el estado del post localmente
                post.is_liked_by_auth_user = true;
                // Añadir un "like" ficticio para que el conteo se actualice
                // En una app más grande, podrías recargar el post o parte de él.
                // Para este ejemplo, simplemente añadimos un objeto like con el user_id
                post.likes.push({ user_id: props.authUserId });
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
    <Head title="Feed Principal" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Feed</h2>
        </template>

        <div class="py-12">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <div v-if="posts.length > 0">
                    <div v-for="post in posts" :key="post.id" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <img src="https://via.placeholder.com/50" alt="Avatar" class="rounded-full w-10 h-10 object-cover mr-3">
                                <Link :href="route('profile.show', post.user.id)" class="font-bold text-gray-900">
                                    {{ post.user.name }}
                                </Link>
                            </div>

                            <img :src="`/storage/${post.image_path}`" :alt="post.caption" class="w-full object-cover mb-4">

                            <div class="flex items-center space-x-4 mb-2">
                                <button @click="toggleLike(post)" :disabled="likeForm.processing"
                                        class="flex items-center text-gray-600 hover:text-red-500 transition-colors">
                                    <svg v-if="post.is_liked_by_auth_user" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-red-500">
                                        <path d="M11.645 20.917c-.183.182-.398.243-.607.243-.209 0-.424-.06-.607-.243L1.107 11.758C.411 11.06 0 10.107 0 9.1c0-2.42 1.76-4.552 4.093-4.945C5.474 3.791 6.88 3.737 8.272 4.197 9.663 4.656 10.957 5.753 12 7.027c1.043-1.274 2.337-2.371 3.728-2.83C17.12 3.737 18.526 3.791 19.907 4.155 22.24 4.552 24 6.68 24 9.1c0 1.007-.411 1.96-.993 2.658L12 20.917z" />
                                    </svg>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                    </svg>
                                    <span class="ms-1 text-sm font-semibold">{{ post.likes.length }} Me gusta</span>
                                </button>

                                <button class="flex items-center text-gray-600 hover:text-blue-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c-1.89 0-3.7-.333-5.46-.98C3.593 18.5 2.25 17.072 2.25 15.25c0-1.82 1.343-3.25 4.29-4.01C8.288 10.493 9.7 10.25 11.25 10.25c1.89 0 3.7.333 5.46.98 2.897.647 4.29 2.077 4.29 3.897 0 1.82-1.343 3.25-4.29 4.01-1.76.392-3.57.647-5.46.647ZM12 20.25a.75.75 0 0 0 .75-.75h-.75V10.5h.75a.75.75 0 0 0 .75-.75H12V20.25Z" />
                                    </svg>
                                    <span class="ms-1 text-sm font-semibold">{{ post.comments.length }} Comentarios</span>
                                </button>
                            </div>


                            <p class="text-gray-700 mb-2">
                                <Link :href="route('profile.show', post.user.id)" class="font-bold text-gray-900 mr-1">
                                    {{ post.user.name }}
                                </Link>
                                {{ post.caption }}
                            </p>

                            <p class="text-xs text-gray-500 mb-4">{{ new Date(post.created_at).toLocaleDateString() }}</p>

                            <hr class="my-4">

                            <div class="comments-section">
                                <h4 class="font-semibold text-lg mb-2">Comentarios</h4>
                                <div v-if="post.comments.length > 0">
                                    <div v-for="comment in post.comments" :key="comment.id" class="mb-2">
                                        <p class="text-sm">
                                            <Link :href="route('profile.show', comment.user.id)" class="font-semibold text-gray-900 mr-1">
                                                {{ comment.user.name }}
                                            </Link>
                                            {{ comment.body }}
                                        </p>
                                    </div>
                                </div>
                                <div v-else class="text-sm text-gray-500 mb-2">
                                    Sé el primero en comentar.
                                </div>

                                <form @submit.prevent="submitComment(getCommentForm(post.id), post.id)" class="mt-4">
                                    <TextInput
                                        :id="`comment-body-${post.id}`"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="getCommentForm(post.id).body"
                                        placeholder="Añade un comentario..."
                                    />
                                    <InputError class="mt-2" :message="getCommentForm(post.id).errors.body" />
                                    <PrimaryButton class="mt-2" :class="{ 'opacity-25': getCommentForm(post.id).processing }" :disabled="getCommentForm(post.id).processing">
                                        Comentar
                                    </PrimaryButton>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center text-gray-500 bg-white p-6 rounded-lg shadow-sm">
                    No hay publicaciones en tu feed. ¡Sigue a algunos usuarios para ver sus publicaciones!
                </div>
            </div>
        </div>
    </AppLayout>
</template>