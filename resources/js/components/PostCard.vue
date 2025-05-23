<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button'; // Corregido
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'; // Corregido
import { getInitials } from '@/composables/useInitials';
import { HeartIcon, MessageCircleIcon, BookmarkIcon } from 'lucide-vue-next';

const props = defineProps({
    post: Object,
    authUserId: {
        type: Number,
        default: null,
    },
});

// Lógica para Likes
const likeForm = useForm({});

const toggleLike = () => {
    if (!props.authUserId) {
        alert('Debes iniciar sesión para dar "Me gusta".');
        return;
    }

    if (props.post.is_liked_by_auth_user) {
        likeForm.delete(route('likes.destroy', props.post.id), {
            preserveScroll: true,
            onSuccess: () => {
                props.post.is_liked_by_auth_user = false;
                props.post.likes_count--;
            }
        });
    } else {
        likeForm.post(route('likes.store', props.post.id), {
            preserveScroll: true,
            onSuccess: () => {
                props.post.is_liked_by_auth_user = true;
                props.post.likes_count++;
            }
        });
    }
};

// Lógica para Guardar/Desguardar
const saveForm = useForm({});

const toggleSave = () => {
    if (!props.authUserId) {
        alert('Debes iniciar sesión para guardar publicaciones.');
        return;
    }

    if (props.post.is_saved_by_auth_user) {
        saveForm.delete(route('posts.unsave', props.post.id), {
            preserveScroll: true,
            onSuccess: () => {
                props.post.is_saved_by_auth_user = false;
            }
        });
    } else {
        saveForm.post(route('posts.save', props.post.id), {
            preserveScroll: true,
            onSuccess: () => {
                props.post.is_saved_by_auth_user = true;
            }
        });
    }
};
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
        <div class="flex items-center p-4 border-b border-gray-200 dark:border-gray-700">
            <Link :href="route('profile.show', post.user.id)" class="flex items-center">
                <Avatar class="size-10 overflow-hidden rounded-full border border-gray-200 dark:border-gray-700">
                    <AvatarImage :src="post.user.avatar_url" :alt="post.user.name" class="object-cover w-full h-full" />
                    <AvatarFallback class="rounded-full bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white text-base">
                        {{ getInitials(post.user.name) }}
                    </AvatarFallback>
                </Avatar>
                <span class="ml-3 font-semibold text-gray-900 dark:text-gray-100">{{ post.user.name }}</span>
            </Link>
        </div>

        <Link :href="route('posts.show', post.id)">
            <img :src="post.image_path" :alt="post.caption" class="w-full object-cover max-h-96" loading="lazy">
        </Link>

        <div class="p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-4">
                    <Button variant="ghost" size="icon" @click="toggleLike"
                        :class="{ 'text-red-500': post.is_liked_by_auth_user, 'text-gray-500 dark:text-gray-400': !post.is_liked_by_auth_user }">
                        <HeartIcon :fill="post.is_liked_by_auth_user ? 'currentColor' : 'none'" />
                    </Button>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ post.likes_count }}</span>

                    <Link :href="route('posts.show', post.id)">
                        <Button variant="ghost" size="icon" class="text-gray-500 dark:text-gray-400">
                            <MessageCircleIcon />
                        </Button>
                    </Link>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ post.comments_count }}</span>
                </div>

                <div v-if="authUserId">
                    <Button variant="ghost" size="icon" @click="toggleSave"
                        :class="{ 'text-blue-500': post.is_saved_by_auth_user, 'text-gray-500 dark:text-gray-400': !post.is_saved_by_auth_user }">
                        <BookmarkIcon :fill="post.is_saved_by_auth_user ? 'currentColor' : 'none'" />
                    </Button>
                </div>
            </div>

            <p class="text-sm text-gray-800 dark:text-gray-200 mb-2 whitespace-pre-wrap">{{ post.caption }}</p>

            <p class="text-xs text-gray-500 dark:text-gray-400">Publicado {{ post.created_at }}</p>
        </div>
    </div>
</template>