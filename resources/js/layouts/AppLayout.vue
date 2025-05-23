<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

// Components
import AppHeader from '@/components/AppHeader.vue';
import NavUser from '@/components/NavUser.vue';
import Sonner from '@/components/ui/sonner/Sonner.vue';

/**
 * Estado y propiedades calculadas
 */
const page = usePage();
const auth = computed(() => page.props.auth);
const globalSearchQuery = ref('');

/**
 * Manejadores de eventos de búsqueda
 */
/**
 * Maneja la acción de búsqueda cuando se confirma
 * @param {string} query - Término de búsqueda introducido por el usuario
 */
const handleSearchTriggered = (query) => {
    // TODO: Implementar lógica de búsqueda
};

/**
 * Actualiza el estado global de búsqueda cuando cambia el input
 * @param {string} query - Término de búsqueda actual
 */
const handleSearchQueryChanged = (query) => {
    globalSearchQuery.value = query;
};
</script>

<template>
    <!-- Layout principal -->
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Cabecera de la aplicación -->
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

        <!-- Cabecera opcional para páginas específicas -->
        <header v-if="$slots.header" class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Contenido principal -->
        <main>
            <slot :searchQuery="globalSearchQuery" />
        </main>
    </div>

    <!-- Sistema de notificaciones -->
    <Sonner richColors />
</template>
