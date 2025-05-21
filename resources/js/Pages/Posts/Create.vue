<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/ui/label/Label.vue';
import PrimaryButton from '@/components/ui/button/Button.vue';
import TextInput from '@/components/ui/input/Input.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
// ¡¡¡LÍNEA CORREGIDA AQUÍ!!!
import Textarea from '@/components/ui/textarea/Textarea.vue';
import { ref, watch } from 'vue';

import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    image: null,
    caption: '',
});

// Para la previsualización de la imagen
const imageUrl = ref(null);

// Watcher para actualizar la previsualización cuando se selecciona una imagen
watch(() => form.image, (newImage) => {
    if (newImage) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imageUrl.value = e.target.result;
        };
        reader.readAsDataURL(newImage);
    } else {
        imageUrl.value = null;
    }
});

const submit = () => {
    form.post(route('posts.store'), {
        forceFormData: true,
        onFinish: () => {
            form.reset('image', 'caption');
            imageUrl.value = null;
        },
    });
};
</script>

<template>

    <Head title="Crear Publicación" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Crear Nueva Publicación
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <Card class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200">
                    <CardHeader>
                        <CardTitle>Nueva Publicación</CardTitle>
                        <CardDescription>Sube una imagen y añade una descripción a tu post.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <InputLabel for="image" value="Imagen" />
                                <input type="file" @input="form.image = $event.target.files[0]" id="image" class="mt-1 block w-full text-gray-900 dark:text-gray-100
                                           file:mr-4 file:py-2 file:px-4
                                           file:rounded-full file:border-0
                                           file:text-sm file:font-semibold
                                           file:bg-violet-50 file:text-violet-700
                                           hover:file:bg-violet-100 dark:file:bg-violet-900 dark:file:text-violet-200
                                           dark:hover:file:bg-violet-800" accept="image/*" />
                                <InputError class="mt-2" :message="form.errors.image" />

                                <div v-if="imageUrl" class="mt-4 flex justify-center">
                                    <img :src="imageUrl" alt="Previsualización de la imagen"
                                        class="max-w-full h-auto max-h-80 rounded-md shadow-md object-contain" />
                                </div>
                            </div>

                            <div class="mt-6">
                                <InputLabel for="caption" value="Descripción (opcional)" />
                                <Textarea id="caption"
                                    class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                    v-model="form.caption" rows="4"
                                    placeholder="Escribe algo sobre tu publicación..." />
                                <InputError class="mt-2" :message="form.errors.caption" />
                            </div>

                            <div class="flex items-center justify-end mt-8">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Publicar
                                </PrimaryButton>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
