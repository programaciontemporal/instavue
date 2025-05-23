<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import PostCard from '@/components/PostCard.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

// Importa los componentes de avatar
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';

// Importa los iconos de Lucide necesarios para la paginación
import { ArrowLeftIcon, ArrowRightIcon } from 'lucide-vue-next';

const props = defineProps({
    user: Object,
    posts: Object,
    authUserId: Number,
});

// Calcula la URL del avatar del usuario del perfil
const profileAvatarUrl = computed(() => {
    // AHORA USA 'avatar_url' QUE ES EL ATRIBUTO GENERADO POR EL ACCESOR EN EL MODELO User
    return props.user.avatar_url;
});
</script>

<template>
    <Head :title="user.name" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Perfil de {{ user.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-8 flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
                    <Avatar class="size-24 md:size-32 overflow-hidden rounded-full border border-gray-200 dark:border-gray-700 flex-shrink-0">
                        <AvatarImage :src="profileAvatarUrl" :alt="user.name" class="object-cover w-full h-full" />
                        <AvatarFallback class="rounded-full bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white text-3xl md:text-5xl">
                            {{ getInitials(user.name) }}
                        </AvatarFallback>
                    </Avatar>

                    <div class="text-center md:text-left">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ user.name }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">{{ user.bio || 'Sin biografía.' }}</p>

                        <div class="flex justify-center md:justify-start space-x-6 text-gray-700 dark:text-gray-300 mb-4">
                            <div><span class="font-bold">{{ user.posts_count }}</span> publicaciones</div>
                            <div><span class="font-bold">{{ user.followers_count }}</span> seguidores</div>
                            <div><span class="font-bold">{{ user.following_count }}</span> seguidos</div>
                        </div>

                        <div v-if="authUserId && authUserId !== user.id">
                            <button
                                @click="user.is_following_auth_user ? $inertia.delete(route('users.unfollow', user.id)) : $inertia.post(route('users.follow', user.id))"
                                class="px-4 py-2 rounded-md font-semibold text-sm transition-colors duration-200"
                                :class="user.is_following_auth_user ? 'bg-red-500 hover:bg-red-600 text-white' : 'bg-blue-500 hover:bg-blue-600 text-white'"
                            >
                                {{ user.is_following_auth_user ? 'Dejar de seguir' : 'Seguir' }}
                            </button>
                        </div>
                        <div v-else-if="authUserId === user.id">
                            <Link :href="route('profile.edit')" class="inline-block bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-md font-semibold text-sm transition-colors duration-200">
                                Editar Perfil
                            </Link>
                        </div>
                    </div>
                </div>

                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 text-center">Publicaciones</h3>
                <div v-if="posts.data && posts.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <PostCard v-for="post in posts.data" :key="post.id" :post="post" :auth-user-id="authUserId" />
                </div>
                <div v-else class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500 dark:text-gray-400">
                    <p>{{ user.id === authUserId ? 'Aún no has hecho ninguna publicación.' : 'Este usuario aún no tiene publicaciones.' }}</p>
                    <p v-if="user.id === authUserId" class="mt-2">
                        <Link :href="route('posts.create')" class="text-blue-500 hover:underline">Crea tu primera publicación</Link>.
                    </p>
                </div>

                <div v-if="posts.links && posts.links.length > 3" class="flex justify-center mt-8">
                    <template v-for="link in posts.links" :key="link.label">
                        <Link
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