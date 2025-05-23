<script setup lang="ts">
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger, DropdownMenuItem, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { type SharedData, type User } from '@/types';
import { usePage, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { User as UserIcon, Settings, LogOut, Bookmark } from 'lucide-vue-next';
import { getInitials } from '@/composables/useInitials';

const page = usePage<SharedData>();
const user = computed(() => page.props.auth.user as User);

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <button type="button" class="flex items-center space-x-2 focus:outline-none cursor-pointer">
                <Avatar class="h-8 w-8">
                    <AvatarImage v-if="user.avatar_url && user.avatar_url.length > 0" :src="user.avatar_url" :alt="user.name" />
                    <AvatarFallback>{{ getInitials(user.name) }}</AvatarFallback>
                </Avatar>
                <span v-if="user.name" class="hidden md:block text-gray-700 dark:text-gray-300">{{ user.name }}</span>
                <svg class="ms-2 -me-0.5 h-4 w-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 011.414 1.414l-4 4a1 1 01-1.414 0l-4-4a1 1 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </DropdownMenuTrigger>
        <DropdownMenuContent side="bottom" align="end" :side-offset="4">
            <DropdownMenuItem as-child>
                <Link :href="route('profile.show', { user: user.id })" class="flex items-center w-full">
                    <UserIcon class="mr-2 h-4 w-4" /> Perfil
                </Link>
            </DropdownMenuItem>
            <DropdownMenuItem as-child>
                <Link :href="route('profile.edit')" class="flex items-center w-full">
                    <Settings class="mr-2 h-4 w-4" /> Ajustes
                </Link>
            </DropdownMenuItem>
            <DropdownMenuSeparator />
            <DropdownMenuItem @click="logout" class="w-full text-left">
                <LogOut class="mr-2 h-4 w-4" /> Cerrar Sesión
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>