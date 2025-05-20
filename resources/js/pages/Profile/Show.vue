<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
    posts: Array,
});

const page = usePage();
const authUser = page.props.auth.user; // Acceder al usuario autenticado

// Determinar si el usuario autenticado ya sigue a este perfil
const isFollowing = props.user.followers.some(follower => follower.id === authUser.id);

const followForm = useForm({});

const toggleFollow = () => {
    if (isFollowing) {
        followForm.delete(route('unfollow', props.user.id));
    } else {
        followForm.post(route('follow', props.user.id));
    }
};

</script>

<template>

    <Head :title="`${user.name}'s Profile`" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Perfil de {{ user.name }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center space-x-4 mb-6">
                            <img src="https://via.placeholder.com/150" alt="Avatar"
                                class="rounded-full w-24 h-24 object-cover">
                            <div>
                                <h3 class="text-2xl font-bold">{{ user.name }}</h3>
                                <p class="text-gray-600">{{ user.email }}</p>
                                <div class="flex space-x-4 mt-2">
                                    <span><strong>{{ posts.length }}</strong> publicaciones</span>
                                    <span><strong>{{ user.followers.length }}</strong> seguidores</span>
                                    <span><strong>{{ user.following.length }}</strong> seguidos</span>
                                </div>
                                <div v-if="authUser.id !== user.id" class="mt-4">
                                    <button @click="toggleFollow"
                                        :class="{ 'bg-blue-500 hover:bg-blue-600 text-white': !isFollowing, 'bg-gray-300 hover:bg-gray-400 text-gray-800': isFollowing }"
                                        class="py-2 px-4 rounded-md text-sm font-semibold transition duration-200"
                                        :disabled="followForm.processing">
                                        {{ isFollowing ? 'Dejar de Seguir' : 'Seguir' }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <hr class="my-6">

                        <div v-if="posts.length > 0" class="grid grid-cols-3 gap-4">
                            <div v-for="post in posts" :key="post.id" class="relative group">
                                <img :src="`/storage/${post.image_path}`" :alt="post.caption"
                                    class="w-full h-full object-cover aspect-square">
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <p class="text-white text-center p-2">{{ post.caption }}</p>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center text-gray-500">
                            Este usuario aún no tiene publicaciones.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>