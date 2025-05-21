<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/ui/label/Label.vue';
import PrimaryButton from '@/components/ui/button/Button.vue';
import TextInput from '@/components/ui/input/Input.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import { ref, watch } from 'vue';
import { toast } from 'vue-sonner'; // Importa toast directamente de vue-sonner

import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    post: Object,
});

const form = useForm({
    _method: 'patch',
    caption: props.post.caption,
});

const imageUrl = ref(props.post.image_path);

const submit = () => {
    form.post(route('posts.update', props.post.id), {
        onSuccess: () => {
            toast.success('Publicación actualizada.', {
                description: 'Tu publicación ha sido modificada con éxito.',
            });
        },
        onError: (errors) => {
            console.error('Error al actualizar:', errors);
            toast.error('Error al actualizar.', {
                description: 'No se pudo actualizar la publicación. Inténtalo de nuevo.',
            });
        },
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="`Editar Publicación de ${post.user.name}`" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Editar Publicación</h2>
        </template>

        <div class="py-12">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <Card class="w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200">
                    <CardHeader>
                        <CardTitle>Editar Publicación</CardTitle>
                        <CardDescription>Modifica la descripción de tu post.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <InputLabel value="Imagen Actual" />
                                <div class="mt-2 flex justify-center">
                                    <img :src="imageUrl" :alt="post.caption" class="max-w-full h-auto max-h-80 rounded-md shadow-md object-contain" />
                                </div>
                            </div>

                            <div class="mt-6">
                                <InputLabel for="caption" value="Descripción" />
                                <Textarea
                                    id="caption"
                                    class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                    v-model="form.caption"
                                    rows="4"
                                />
                                <InputError class="mt-2" :message="form.errors.caption" />
                            </div>

                            <div class="flex items-center justify-end mt-8">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Guardar Cambios
                                </PrimaryButton>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
