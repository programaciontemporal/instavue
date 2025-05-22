<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger, DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input'; // Importar el componente Input
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';
import { Link, usePage, router } from '@inertiajs/vue3';
import { PlusSquare, Compass, Home, User, Settings, LogOut, Search, Menu } from 'lucide-vue-next';
import { computed, ref } from 'vue';

// Función para obtener iniciales, asumiendo que no depende de un composable específico de Jetstream
const getInitials = (name: string | null | undefined): string => {
    if (!name) return '';
    const parts = name.split(' ');
    let initials = '';
    for (const part of parts) {
        if (part.length > 0) {
            initials += part[0].toUpperCase();
        }
    }
    return initials;
};

const page = usePage();
const auth = computed(() => page.props.auth);

const isCurrentRoute = computed(() => (url: string) => page.url === url);

const activeItemStyles = computed(
    () => (url: string) => (isCurrentRoute.value(url) ? 'text-neutral-900 dark:bg-neutral-800 dark:text-neutral-100' : ''),
);

const logout = () => {
    router.post(route('logout'));
};

// Lógica para la barra de búsqueda
const searchQuery = ref('');

const handleSearch = () => {
    if (searchQuery.value.trim()) {
        router.get(route('search.results', { query: searchQuery.value }));
    }
};
</script>

<template>
    <div>
        <div class="border-b border-sidebar-border/80">
            <div class="mx-auto flex h-16 items-center px-4 md:max-w-7xl">
                <div class="lg:hidden">
                    <Sheet>
                        <SheetTrigger :as-child="true">
                            <Button variant="ghost" size="icon" class="mr-2 h-9 w-9">
                                <Menu class="h-5 w-5" />
                            </Button>
                        </SheetTrigger>
                        <SheetContent side="left" class="w-[300px] p-6">
                            <SheetTitle class="sr-only">Menú de Navegación</SheetTitle>
                            <SheetHeader class="flex justify-start text-left">
                                <img src="/images/instavue-logo.png" alt="InstaVue Logo" class="h-8 w-auto">
                            </SheetHeader>
                            <div class="flex h-full flex-1 flex-col justify-between space-y-4 py-6">
                                <nav class="-mx-3 space-y-1">
                                    <Link :href="route('feed')"
                                        class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-accent"
                                        :class="activeItemStyles(route('feed'))">
                                    <Home class="h-5 w-5" /> Feed
                                    </Link>
                                    <Link :href="route('explore')"
                                        class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-accent"
                                        :class="activeItemStyles(route('explore'))">
                                    <Compass class="h-5 w-5" /> Explorar
                                    </Link>
                                    <Link :href="route('posts.create')"
                                        class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-accent">
                                    <PlusSquare class="h-5 w-5" /> Añadir Post
                                    </Link>
                                    <Link :href="route('profile.show', auth.user.username)"
                                        class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-accent">
                                    <User class="h-5 w-5" /> Perfil
                                    </Link>
                                    <Link :href="route('profile.edit')"
                                        class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-accent">
                                    <Settings class="h-5 w-5" /> Ajustes
                                    </Link>
                                    <button @click="logout"
                                        class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-accent w-full text-left">
                                        <LogOut class="h-5 w-5" /> Cerrar Sesión
                                    </button>
                                </nav>
                            </div>
                        </SheetContent>
                    </Sheet>
                </div>

                <slot name="logo">
                    <Link :href="route('feed')" class="flex items-center gap-x-2">
                        <img src="/images/instavue-logo.png" alt="InstaVue Logo" class="h-8 w-auto">
                    </Link>
                </slot>

                <div class="relative ml-auto flex items-center space-x-1 lg:ml-10 lg:flex-1 lg:justify-start">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <Search class="h-5 w-5 text-gray-400" />
                        </span>
                        <Input
                            type="text"
                            placeholder="Buscar..."
                            class="pl-10 pr-4 py-2 rounded-md border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full max-w-xs"
                            v-model="searchQuery"
                            @keyup.enter="handleSearch"
                        />
                    </div>
                </div>

                <div class="hidden lg:flex items-center space-x-4 ml-auto">
                    <Link :href="route('feed')" class="text-gray-600 hover:text-gray-900">
                        <Home class="h-6 w-6" />
                    </Link>
                    <Link :href="route('explore')" class="text-gray-600 hover:text-gray-900">
                        <Compass class="h-6 w-6" />
                    </Link>
                    <Link :href="route('posts.create')" class="text-gray-600 hover:text-gray-900">
                        <PlusSquare class="h-6 w-6" />
                    </Link>

                    <DropdownMenu>
                        <DropdownMenuTrigger class="focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-full">
                            <Avatar class="h-8 w-8">
                                <AvatarImage :src="auth.user.profile_picture_url" :alt="auth.user.username" />
                                <AvatarFallback>{{ getInitials(auth.user.username) }}</AvatarFallback>
                            </Avatar>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" class="w-56">
                            <DropdownMenuItem as-child>
                                <Link :href="route('profile.show', auth.user.username)" class="flex items-center w-full">
                                    <User class="mr-2 h-4 w-4" />
                                    <span>Perfil</span>
                                </Link>
                            </DropdownMenuItem>
                            <DropdownMenuItem as-child>
                                <Link :href="route('profile.edit')" class="flex items-center w-full">
                                    <Settings class="mr-2 h-4 w-4" />
                                    <span>Ajustes</span>
                                </Link>
                            </DropdownMenuItem>
                            <DropdownMenuItem as-child>
                                <Link :href="route('posts.saved')" class="flex items-center w-full">
                                    <Bookmark class="mr-2 h-4 w-4" />
                                    <span>Guardado</span>
                                </Link>
                            </DropdownMenuItem>
                            <DropdownMenuItem @click="logout">
                                <LogOut class="mr-2 h-4 w-4" />
                                <span>Cerrar Sesión</span>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </div>
        </div>
    </div>
</template>
