<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    post: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="relative group aspect-square overflow-hidden rounded-lg shadow-md">
        <Link :href="route('posts.show', post.id)">
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
        </Link>
    </div>
</template>
