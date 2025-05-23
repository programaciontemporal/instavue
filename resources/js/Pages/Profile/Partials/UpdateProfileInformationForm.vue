<script setup>
import InputError from '@/components/InputError.vue'; // Corregido
import InputLabel from '@/components/InputLabel.vue'; // Corregido
import PrimaryButton from '@/components/PrimaryButton.vue'; // Corregido
import TextInput from '@/components/TextInput.vue'; // Corregido
import { Link, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    user: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    avatar: null,
    remove_avatar: false,
    bio: props.user.bio || '',
});

const avatarPreviewUrl = ref(props.user.avatar_url);

watch(() => form.avatar, (newAvatar) => {
    if (newAvatar instanceof File) {
        avatarPreviewUrl.value = URL.createObjectURL(newAvatar);
        form.remove_avatar = false;
    } else if (newAvatar === null && form.remove_avatar === false) {
        avatarPreviewUrl.value = props.user.avatar_url;
    }
});

const handleAvatarChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.avatar = file;
        form.remove_avatar = false;
    } else {
        form.avatar = null;
        avatarPreviewUrl.value = props.user.avatar_url;
    }
};

const removeAvatar = () => {
    form.avatar = null;
    form.remove_avatar = true;
    avatarPreviewUrl.value = "https://ui-avatars.com/api/?name=" + encodeURIComponent(props.user.name) + "&color=ffffff&background=007bff&size=128";
};

const submit = () => {
    form.post(route('profile.update'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            window.location.reload();
        },
        onError: (errors) => {
            console.error('Validation errors:', errors);
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Información del Perfil</h2>

            <p class="mt-1 text-sm text-gray-600">
                Actualiza la información de perfil de tu cuenta y la dirección de correo electrónico.
            </p>
        </header>

        <form @submit.prevent="submit" class="mt-6 space-y-6">
            <div>
                <InputLabel for="avatar" value="Avatar" />

                <div class="mt-2 flex items-center space-x-4">
                    <img
                        :src="avatarPreviewUrl"
                        alt="Avatar Preview"
                        class="w-20 h-20 rounded-full object-cover"
                    />
                    <input
                        type="file"
                        id="avatar"
                        @change="handleAvatarChange"
                        accept="image/*"
                        class="hidden"
                        ref="avatarInput"
                    />
                    <PrimaryButton type="button" @click="$refs.avatarInput.click()">
                        Seleccionar Avatar
                    </PrimaryButton>
                    <PrimaryButton
                        v-if="props.user.avatar || form.avatar"
                        type="button"
                        @click="removeAvatar"
                        class="ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Eliminar Avatar
                    </PrimaryButton>
                </div>
                <InputError class="mt-2" :message="form.errors.avatar" />
                <InputError class="mt-2" :message="form.errors.remove_avatar" />
            </div>

            <div>
                <InputLabel for="name" value="Nombre" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="bio" value="Biografía" />
                <textarea
                    id="bio"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                    v-model="form.bio"
                    rows="3"
                ></textarea>
                <InputError class="mt-2" :message="form.errors.bio" />
            </div>

            <div v-if="props.mustVerifyEmail && props.user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Tu dirección de correo electrónico no está verificada.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Haz clic aquí para volver a enviar el correo de verificación.
                    </Link>
                </p>

                <div
                    v-show="props.status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600"
                >
                    Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Guardar</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Guardado.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>