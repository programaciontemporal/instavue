<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import AppHeader from '@/components/AppHeader.vue';
import NavUser from '@/components/NavUser.vue'; // Importa NavUser.vue

// Función de logout
const logout = () => {
    router.post(route('logout'));
};

const page = usePage();
const auth = computed(() => page.props.auth);
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <AppHeader>
            <template #logo>
                <Link :href="route('feed')">
                    <img src="/images/instavue-logo.png" alt="InstaVue Logo" class="h-8 w-auto">
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

                <NavUser :user="auth.user" />

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