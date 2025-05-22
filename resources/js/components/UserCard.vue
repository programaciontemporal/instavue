<script setup>
import { Link } from '@inertiajs/vue3';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/Avatar.vue'; // Ajusta la ruta si es diferente
import { getInitials } from '@/composables/useInitials'; // O la función que uses para las iniciales

const props = defineProps({
    user: Object,
});
</script>

<template>
    <div class="bg-white border rounded-lg p-4 flex items-center space-x-4">
        <Link :href="route('profile.show', props.user.username)">
            <Avatar class="h-12 w-12">
                <AvatarImage :src="props.user.profile_picture_url" :alt="props.user.username" />
                <AvatarFallback>{{ getInitials(props.user.username) }}</AvatarFallback>
            </Avatar>
        </Link>
        <div>
            <Link :href="route('profile.show', props.user.username)" class="font-bold text-lg hover:underline">
                {{ props.user.username }}
            </Link>
            <p class="text-gray-600 text-sm">{{ props.user.name }}</p>
        </div>
    </div>
</template>
