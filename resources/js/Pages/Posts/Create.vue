/**
 * @file Posts/Create.vue
 * @description Componente para la creación de nuevas publicaciones
 * Permite subir una imagen y añadir un pie de foto
 * Incluye previsualización de imagen y manejo de formularios con Inertia
 */
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/ui/label/Label.vue';
import PrimaryButton from '@/components/ui/button/Button.vue'; // Asegúrate de que esto apunta a tu botón de estilo primario
import TextInput from '@/components/ui/input/Input.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import { ref, watch } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';

interface PostFormData {
    image: File | null;
    caption: string;
}

const form = useForm({
    image: null as File | null,
    caption: '',
});

// Referencia para la previsualización de la imagen
const imageUrl = ref<string | null>(null);

// Referencia al input de tipo file oculto
const fileInput = ref<HTMLInputElement | null>(null);

// Actualiza la previsualización cuando se selecciona una imagen
watch(() => form.image, (newImage) => {
    if (newImage) {
        const reader = new FileReader();
        reader.onload = (e) => {
            if (e.target) {
                imageUrl.value = e.target.result as string;
            }
        };
        reader.readAsDataURL(newImage);
    } else {
        imageUrl.value = null;
    }
});

/**
 * Maneja el envío del formulario
 * Realiza la subida usando FormData y reinicia el formulario al terminar
 */
const submit = () => {
    form.post(route('posts.store'), {
        forceFormData: true,
        onFinish: () => {
            form.reset();
            imageUrl.value = null;
            // Limpia el input de archivo también
            if (fileInput.value) {
                fileInput.value.value = '';
            }
        },
    });
};

const handleFileInput = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        form.image = target.files[0];
    }
};

// Función para activar el input de archivo al hacer clic en el botón personalizado
const triggerFileInput = () => {
    fileInput.value?.click();
};
</script>

<template>
    <Head title="Crear Publicación" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Crear Nueva Publicación
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card class="max-w-xl mx-auto">
                    <CardHeader>
                        <CardTitle>Crear Publicación</CardTitle>
                        <CardDescription>Comparte un momento especial con tus seguidores</CardDescription>
                    </CardHeader>

                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <InputLabel for="image" value="Imagen" />
                                <div class="relative mt-1">
                                    <PrimaryButton type="button" @click="triggerFileInput">
                                        Seleccionar Imagen
                                    </PrimaryButton>

                                    <input
                                        ref="fileInput"
                                        type="file"
                                        @change="handleFileInput"
                                        id="image"
                                        accept="image/*"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    />
                                </div>
                                <InputError class="mt-2" :message="form.errors.image" />

                                <div v-if="imageUrl" class="mt-4">
                                    <img :src="imageUrl" alt="Preview" class="max-w-full h-auto rounded-lg" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="caption" value="Pie de foto" />
                                <Textarea
                                    id="caption"
                                    v-model="form.caption"
                                    class="mt-1 block w-full"
                                    placeholder="Escribe un pie de foto..."
                                />
                                <InputError class="mt-2" :message="form.errors.caption" />
                            </div>

                            <div class="flex items-center justify-end">
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
