<script setup lang="ts">
import UserInfo from '@/components/UserInfo.vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger, DropdownMenuItem, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
// Asegúrate de que estas importaciones no se usen si no están en tu proyecto:
// import { SidebarMenu, SidebarMenuButton, SidebarMenuItem, useSidebar } from '@/components/ui/sidebar';
import { type SharedData, type User } from '@/types';
import { usePage, Link } from '@inertiajs/vue3';
import { ChevronsUpDown } from 'lucide-vue-next';

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

// Asegúrate de que esta línea no exista si quitaste useSidebar()
// const { isMobile, state } = useSidebar();

// Función para obtener las iniciales de forma segura
const getInitials = (name: string): string => {
    if (!name) return '??'; // Si el nombre es nulo o vacío
    const parts = name.split(' ');
    if (parts.length === 1) {
        return parts[0].charAt(0).toUpperCase();
    }
    return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase();
};
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <button class="flex items-center space-x-2 focus:outline-none">
                <Avatar class="h-8 w-8">
                    <AvatarImage v-if="user.avatar" :src="user.avatar" :alt="user.name" />
                    <AvatarFallback>{{ getInitials(user.name) }}</AvatarFallback>
                </Avatar>
                <span class="hidden md:block text-gray-700 dark:text-gray-300">{{ user.name }}</span>
                <svg
                    class="ms-2 -me-0.5 h-4 w-4 text-gray-500 dark:text-gray-400"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                    />
                </svg>
            </button>
        </DropdownMenuTrigger>
        <DropdownMenuContent
            class="w-(--reka-dropdown-menu-trigger-width) min-w-56 rounded-lg"
            side="bottom"       align="end"
            :side-offset="4"
        >
            <DropdownMenuItem as-child>
                <Link :href="route('profile.show', user.id)">Ver Perfil</Link>
            </DropdownMenuItem>
            <DropdownMenuItem as-child>
                <Link :href="route('profile.edit')">Configuración del Perfil</Link>
            </DropdownMenuItem>
            <DropdownMenuSeparator />
            <DropdownMenuItem as-child>
                <Link :href="route('logout')" method="post" as="button" class="w-full text-left">
                    Cerrar Sesión
                </Link>
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>