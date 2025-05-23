<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Link, usePage } from '@inertiajs/vue3';

/**
 * Layout dividido para páginas de autenticación
 * Muestra una sección lateral con fondo oscuro y una sección principal para el contenido
 * @component AuthSplitLayout
 */

/**
 * @interface Quote
 * @property {string} message - Mensaje de la cita
 * @property {string} author - Autor de la cita
 */
interface Quote {
    message: string;
    author: string;
}

/**
 * Estado y propiedades de la página
 */
const page = usePage();
const name = page.props.name as string;
const quote = page.props.quote as Quote;

/**
 * Props del componente
 */
defineProps<{
    title?: string;        // Título principal de la página
    description?: string;  // Descripción corta bajo el título
}>();
</script>

<template>
    <!-- Contenedor principal con grid layout -->
    <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
        <!-- Panel lateral izquierdo (solo visible en desktop) -->
        <div class="relative hidden h-full flex-col bg-muted p-10 text-white dark:border-r lg:flex">
            <div class="absolute inset-0 bg-zinc-900" />
            <!-- Logo y nombre de la aplicación -->
            <Link :href="'/'" class="relative z-20 flex items-center text-lg font-medium">
                <AppLogoIcon class="mr-2 size-8 fill-current text-white" />
                {{ name }}
            </Link>
            <!-- Sección de cita -->
            <div v-if="quote" class="relative z-20 mt-auto">
                <blockquote class="space-y-2">
                    <p class="text-lg">&ldquo;{{ quote.message }}&rdquo;</p>
                    <footer class="text-sm text-neutral-300">{{ quote.author }}</footer>
                </blockquote>
            </div>
        </div>

        <!-- Panel derecho - Contenido principal -->
        <div class="lg:p-8">
            <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                <!-- Encabezado con título y descripción -->
                <div class="flex flex-col space-y-2 text-center">
                    <h1 class="text-xl font-medium tracking-tight" v-if="title">{{ title }}</h1>
                    <p class="text-sm text-muted-foreground" v-if="description">{{ description }}</p>
                </div>
                <!-- Slot para el contenido -->
                <slot />
            </div>
        </div>
    </div>
</template>
