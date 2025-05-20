<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import AppHeader from '@/components/AppHeader.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppContent from '@/components/AppContent.vue';
// import AppShell from '@/components/AppShell.vue';
import Button from '@/components/ui/button/Button.vue';
import DropdownMenu from '@/components/ui/dropdown-menu/DropdownMenu.vue';
import DropdownMenuContent from '@/components/ui/dropdown-menu/DropdownMenuContent.vue';
import DropdownMenuItem from '@/components/ui/dropdown-menu/DropdownMenuItem.vue';
import DropdownMenuTrigger from '@/components/ui/dropdown-menu/DropdownMenuTrigger.vue';

// Función de logout
const logout = () => {
    router.post(route('logout'));
};

// Puedes usar usePage para acceder a props globales si es necesario,
// por ejemplo, para el ID del usuario autenticado en el enlace de perfil.
const page = usePage();
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <AppHeader>
            <template #logo>
                <Link :href="route('feed')">
                    <img src="/images/instavue-logo.svg" alt="Instavue Logo" class="h-8">
                </Link>
            </template>
            <template #navigation>
                <Link :href="route('feed')"
                    class="me-4 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                Feed
                </Link>
                <Link :href="route('explore')"
                    class="me-4 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                Explorar
                </Link>
            </template>
            <template #user-actions>
                <Link :href="route('posts.create')"
                    class="me-4 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </Link>

                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button variant="ghost" class="relative h-8 w-8 rounded-full">
                            <img src="https://via.placeholder.com/50" alt="Avatar"
                                class="rounded-full h-8 w-8 object-cover">
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent class="w-56" align="end">
                        <DropdownMenuItem>
                            <Link :href="route('profile.show', page.props.auth.user.id)">
                            Perfil
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem>
                            <Link :href="route('profile.edit')">
                            Ajustes
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem @click="logout" as="button">
                            Cerrar Sesión
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </template>
        </AppHeader>

        <header v-if="$slots.header" class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <main>
            <slot />
        </main>
    </div>
</template>
