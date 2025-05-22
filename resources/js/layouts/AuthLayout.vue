<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import AppHeader from '@/components/AppHeader.vue';
import NavUser from '@/components/NavUser.vue';
import Sonner from '@/components/ui/sonner/Sonner.vue';

const page = usePage();
const auth = computed(() => page.props.auth);

// Almacena la consulta de búsqueda globalmente en AppLayout
const globalSearchQuery = ref('');

// Maneja el evento cuando se dispara una búsqueda desde AppHeader
const handleSearchTriggered = (query) => {
    // Aquí puedes añadir la lógica para iniciar una búsqueda de contenido
    // en la página actual. `query` contiene el término de búsqueda.
};

// Maneja el evento cuando el texto de búsqueda cambia en AppHeader
const handleSearchQueryChanged = (query) => {
    globalSearchQuery.value = query;
    // Puedes usar `globalSearchQuery.value` para filtrar resultados
    // en tiempo real si lo deseas.
};
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <AppHeader
            @search-triggered="handleSearchTriggered"
            @search-query-changed="handleSearchQueryChanged"
        >
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

        <main class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <slot :searchQuery="globalSearchQuery" />
            </div>
        </main>
    </div>
    <Sonner richColors />
</template>