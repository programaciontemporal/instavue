<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Recuperar Contraseña" />

    <div class="centered-container">
        <div class="container">
            <div class="form-container sign-in-container">
                <form @submit.prevent="submit">
                    <h1>Recuperar Contraseña</h1>

                    <p class="help-text-paragraph">¿Necesitas ayuda?</p>
                    <p class="help-text-paragraph">Te enviaremos un enlace a tu correo electrónico para que puedas restablecer tu contraseña fácilmente.</p>

                    <span>Introduce tu correo electrónico para recibir un enlace de restablecimiento.</span>

                    <div v-if="status" class="status-message">
                        {{ status }}
                    </div>

                    <input
                        type="email"
                        placeholder="Correo Electrónico"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    >
                    <InputError :message="form.errors.email" class="mt-2" />

                    <button type="submit" :disabled="form.processing">
                        Enviar Enlace de Restablecimiento
                    </button>

                    <Link :href="route('auth.combined')" class="text-link mt-4">Volver al inicio de sesión</Link>
                </form>
            </div>
            </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css?family=Montserrat:400,800');

.centered-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    width: 100vw;
    background: #f6f5f7;
    margin: 0;
    padding: 0;
    font-family: 'Montserrat', sans-serif;
}

body {
    margin: 0;
    padding: 0;
}

h1 {
    font-weight: bold;
    margin: 0;
    font-size: 2.2em; /* Tamaño de H1 original */
    margin-bottom: 10px;
}

p {
    font-size: 14px;
    font-weight: 100;
    line-height: 20px;
    letter-spacing: 0.5px;
    margin: 20px 0 30px;
}

/* Estilo específico para los nuevos párrafos de ayuda */
.help-text-paragraph {
    font-size: 14px; /* Tamaño similar al original */
    font-weight: normal; /* No bold */
    line-height: 18px;
    letter-spacing: 0.2px;
    color: #555; /* Color más suave */
    margin: 5px 0; /* Margen ajustado para pegarse más */
}

span {
    font-size: 12px;
    color: #555;
}

a {
    color: #333;
    font-size: 14px;
    text-decoration: none;
    margin: 15px 0;
}

button {
    border-radius: 20px;
    border: 1px solid #FF4B2B;
    background-color: #FF4B2B;
    color: #FFFFFF;
    font-size: 12px;
    font-weight: bold;
    padding: 12px 45px;
    letter-spacing: 1px;
    text-transform: uppercase;
    transition: transform 80ms ease-in;
    cursor: pointer;
    margin-top: 15px;
}

button:active {
    transform: scale(0.95);
}

button:focus {
    outline: none;
}

form {
    background-color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 0 50px;
    height: 100%;
    text-align: center;
}

input {
    background-color: #eee;
    border: none;
    padding: 12px 15px;
    margin: 8px 0;
    width: 100%;
    text-align: center
}

.container {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25),
        0 10px 10px rgba(0, 0, 0, 0.22);
    position: relative;
    overflow: hidden;
    width: 768px; /* Ancho completo como en el diseño original */
    max-width: 100%;
    min-height: 480px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.form-container {
    width: 100%; /* Ahora ocupa el 100% del contenedor ya que no hay overlay lateral */
    position: static;
    height: auto;
    transition: none;
    opacity: 1;
    z-index: 1;
}

.sign-in-container {
    left: 0;
    z-index: 2;
}

/* Se han eliminado todas las reglas CSS relacionadas con el overlay y sus animaciones. */

.status-message {
    margin-bottom: 1rem;
    text-align: center;
    font-size: 0.875rem;
    line-height: 1.25rem;
    font-weight: 500;
    color: #34d399;
}

.text-link {
    color: #333;
    font-size: 14px;
    text-decoration: none;
    margin-top: 15px;
    transition: color 0.3s ease;
}

.text-link:hover {
    color: #FF4B2B;
}
</style>