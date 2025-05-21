<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>

    <Head title="Bienvenido a Instavue" />

    <div
        class="relative min-h-screen bg-gray-100 flex items-center justify-center dark:bg-gray-900 selection:bg-indigo-500 selection:text-white">
        <div class="text-center p-6 bg-white shadow-lg rounded-lg max-w-md w-full">
            <div class="flex justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-20 h-20 text-indigo-500">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175M4.916 4.665c-.07.666-.07 1.333 0 2m-.852 1.488a23.894 23.894 0 0 1 2.457-1.047M16.125 18.665a2.31 2.31 0 0 0 1.833.456 2.31 2.31 0 0 0 1.833-.456M2.4 8.525a23.882 23.882 0 0 1 18.8 0M12 17.25c4.142 0 7.5-3.358 7.5-7.5S16.142 2.25 12 2.25 4.5 5.608 4.5 9.75c0 1.815.688 3.5 1.833 4.793m-1.833 2.622V21m18.333-4.135V21M12 21.75V15" />
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Bienvenido a Instavue</h1>
            <p class="text-gray-600 mb-8">Comparte tus momentos con el mundo. ¡Únete a nuestra comunidad!</p>
            <div class="space-x-4">
                <Link v-if="canLogin" :href="route('login')"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Iniciar Sesión
                </Link>
                <Link v-if="canRegister" :href="route('register')"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                Registrarse
                </Link>
            </div>
        </div>
    </div>
</template>