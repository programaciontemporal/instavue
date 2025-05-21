<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/ui/label/Label.vue';
import PrimaryButton from '@/components/ui/button/Button.vue';
import TextInput from '@/components/ui/input/Input.vue';

import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    image: null,
    caption: '',
});

const submit = () => {
    form.post(route('posts.store'), {
        forceFormData: true,
        onFinish: () => form.reset('image'),
    });
};
</script>

<template>
    <Head title="Crear Publicación" />

    <AppLayout> <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear Nueva Publicación</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div>
                                <InputLabel for="image" value="Imagen" />
                                <input type="file" @input="form.image = $event.target.files[0]" id="image" class="mt-1 block w-full" accept="image/*" />
                                <InputError class="mt-2" :message="form.errors.image" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="caption" value="Descripción (opcional)" />
                                <TextInput
                                    id="caption"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.caption"
                                    autocomplete="caption"
                                />
                                <InputError class="mt-2" :message="form.errors.caption" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Publicar
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>