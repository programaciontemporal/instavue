<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue'; // Mantener si AppLogo es un componente que renderiza el logo
import AppLogoIcon from '@/components/AppLogoIcon.vue'; // Mantener si AppLogoIcon es un componente que renderiza el logo
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger, DropdownMenuItem } from '@/components/ui/dropdown-menu';
import {
    NavigationMenu,
    NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
    navigationMenuTriggerStyle,
} from '@/components/ui/navigation-menu';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { getInitials } from '@/composables/useInitials';
import type { NavItem } from '@/types';
import { Link, usePage, router } from '@inertiajs/vue3';
import { PlusSquare, Compass, Home, User, Settings, LogOut, Search, Menu } from 'lucide-vue-next';
import { computed } from 'vue';

const page = usePage();
const auth = computed(() => page.props.auth);

const isCurrentRoute = computed(() => (url: string) => page.url === url);

const activeItemStyles = computed(
    () => (url: string) => (isCurrentRoute.value(url) ? 'text-neutral-900 dark:bg-neutral-800 dark:text-neutral-100' : ''),
);

const mainNavItems: NavItem[] = [
    {
        title: 'Feed',
        href: route('feed'),
        icon: Home,
    },
    {
        title: 'Explorar',
        href: route('explore'),
        icon: Compass,
    },
];

const logout = () => {
    router.post(route('logout'));
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
                                    <Link :href="route('profile.show', auth.user.id)"
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

                <div class="hidden h-full lg:flex lg:flex-1">
                    <NavigationMenu class="ml-10 flex h-full items-stretch">
                        <NavigationMenuList class="flex h-full items-stretch space-x-2">
                            <NavigationMenuItem class="relative flex h-full items-center">
                                <slot name="navigation"></slot>
                            </NavigationMenuItem>
                        </NavigationMenuList>
                    </NavigationMenu>
                </div>

                <div class="ml-auto flex items-center space-x-2">
                    <div class="relative flex items-center space-x-1">
                        <Button variant="ghost" size="icon" class="group h-9 w-9 cursor-pointer">
                            <Search class="size-5 opacity-80 group-hover:opacity-100" />
                        </Button>
                    </div>

                    <slot name="user-actions"></slot>
                </div>
            </div>
        </div>
    </div>
</template>
