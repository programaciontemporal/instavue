<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
// Importamos AuthLayout porque es nuestro layout para usuarios autenticados
import AuthLayout from '@/Layouts/AuthLayout.vue';

const props = defineProps({
    user: Object, // Incluye el objeto de usuario que ProfileController@show pasa
    posts: Object, // Paginación de posts
    authUserId: Number, // ID del usuario autenticado
});

// Para manejar el estado del seguimiento reactivamente
import { ref } from 'vue';
const isFollowing = ref(props.user.is_following_auth_user); // Inicializa con el prop

// Formulario para seguir
const followForm = useForm({});

const toggleFollow = () => {
    if (isFollowing.value) {
        // Si ya lo está siguiendo, entonces es unfollow
        followForm.delete(route('unfollow', props.user.id), {
            preserveScroll: true,
            onSuccess: () => {
                isFollowing.value = false;
                // Opcional: Actualizar el contador de seguidores si lo tienes en el front
                props.user.followers_count--; // Asumiendo que quieres actualizarlo en tiempo real
            },
            onError: (errors) => {
                console.error("Error al dejar de seguir:", errors);
            }
        });
    } else {
        // Si no lo está siguiendo, entonces es follow
        followForm.post(route('follow', props.user.id), {
            preserveScroll: true,
            onSuccess: () => {
                isFollowing.value = true;
                // Opcional: Actualizar el contador de seguidores
                props.user.followers_count++;
            },
            onError: (errors) => {
                console.error("Error al seguir:", errors);
            }
        });
    }
};
</script>

<template>
    <Head :title="`${user.name}'s Profile`" />

    <AuthLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Perfil de {{ user.name }}
            </h2>
        </template>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex items-center space-x-4 mb-6">
                    <img :src="user.avatar" alt="Avatar" class="w-24 h-24 rounded-full object-cover">
                    <div>
                        <h1 class="text-3xl font-bold">{{ user.name }}</h1>
                        <p class="text-gray-600">{{ user.bio }}</p>
                        <div class="flex space-x-4 mt-2">
                            <span>{{ user.posts_count }} publicaciones</span>
                            <span>{{ user.followers_count }} seguidores</span>
                            <span>{{ user.following_count }} siguiendo</span>
                        </div>

                        <div v-if="user.id !== authUserId" class="mt-4">
                            <button
                                @click="toggleFollow"
                                :class="{
                                    'bg-blue-500 hover:bg-blue-600 text-white': !isFollowing,
                                    'bg-gray-300 hover:bg-gray-400 text-gray-800': isFollowing
                                }"
                                class="px-4 py-2 rounded-md transition duration-200"
                            >
                                {{ isFollowing ? 'Dejar de seguir' : 'Seguir' }}
                            </button>
                        </div>
                    </div>
                </div>

                <h2 class="text-2xl font-bold mb-4">Publicaciones</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="post in posts.data" :key="post.id" class="border rounded-lg overflow-hidden shadow-sm">
                        <Link :href="route('posts.show', post.id)">
                            <img :src="post.image_path" :alt="post.caption" class="w-full h-48 object-cover">
                        </Link>
                        <div class="p-4">
                            <p class="text-gray-700">{{ post.caption }}</p>
                            <div class="flex items-center text-sm text-gray-500 mt-2">
                                <span>{{ post.likes_count }} likes</span>
                                <span class="ml-4">{{ post.comments_count }} comentarios</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex justify-center">
                    <template v-for="link in posts.links" :key="link.label">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            v-html="link.label"
                            class="px-3 py-1 mx-1 rounded-md"
                            :class="{
                                'bg-blue-500 text-white': link.active,
                                'bg-gray-200 text-gray-700': !link.active,
                            }"
                            :preserve-scroll="true"
                        />
                        <span
                            v-else
                            v-html="link.label"
                            class="px-3 py-1 mx-1 rounded-md opacity-50 cursor-not-allowed bg-gray-200 text-gray-700"
                            :class="{
                                'bg-blue-500 text-white': link.active,
                                'bg-gray-200 text-gray-700': !link.active,
                            }"
                        />
                    </template>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>