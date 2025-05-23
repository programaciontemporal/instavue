<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Link } from '@inertiajs/vue3';

/**
 * Layout simple para páginas de autenticación
 * Muestra un logo, título y descripción centrados con el contenido debajo
 * @component AuthSimpleLayout
 */

/**
 * Props del componente
 * @typedef {Object} Props
 * @property {string} [title] - Título principal de la página
 * @property {string} [description] - Descripción corta bajo el título
 */
defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <!-- Contenedor principal -->
    <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10">
        <div class="w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <!-- Sección de encabezado -->
                <div class="flex flex-col items-center gap-4">
                    <!-- Logo y enlace principal -->
                    <Link :href="'/'" class="flex flex-col items-center gap-2 font-medium">
                        <div class="mb-1 flex h-9 w-9 items-center justify-center rounded-md">
                            <AppLogoIcon class="size-9 fill-current text-[var(--foreground)] dark:text-white" />
                        </div>
                        <span class="sr-only">{{ title }}</span>
                    </Link>

                    <!-- Título y descripción -->
                    <div class="space-y-2 text-center">
                        <h1 class="text-xl font-medium">{{ title }}</h1>
                        <p class="text-center text-sm text-muted-foreground">{{ description }}</p>
                    </div>
                </div>

                <!-- Contenido del slot principal -->
                <slot />
            </div>
        </div>
    </div>
</template>
