<script setup>
import { ref } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';

const isRightPanelActive = ref(false);

const props = defineProps({
    canResetPassword: {
        type: Boolean,
        default: false,
    },
    status: {
        type: String,
    },
});

const loginForm = useForm({
    email: '',
    password: '',
    remember: false,
});

const registerForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submitLogin = () => {
    loginForm.post(route('login'), {
        onFinish: () => loginForm.reset('password'),
    });
};

const submitRegister = () => {
    registerForm.post(route('register'), {
        onFinish: () => registerForm.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head :title="isRightPanelActive ? 'Registro' : 'Inicio de Sesión'" />

    <div class="centered-container"> 
        <div :class="['container', { 'right-panel-active': isRightPanelActive }]">
            <div class="form-container sign-up-container">
                <form @submit.prevent="submitRegister">
                    <h1>Crear Cuenta</h1>
                    <input type="text" placeholder="Nombre" v-model="registerForm.name" required autofocus autocomplete="name">
                    <InputError :message="registerForm.errors.name" class="mt-2" />

                    <input type="email" placeholder="Correo Electrónico" v-model="registerForm.email" required autocomplete="username">
                    <InputError :message="registerForm.errors.email" class="mt-2" />

                    <input type="password" placeholder="Contraseña" v-model="registerForm.password" required autocomplete="new-password">
                    <InputError :message="registerForm.errors.password" class="mt-2" />

                    <input type="password" placeholder="Confirmar Contraseña" v-model="registerForm.password_confirmation" required autocomplete="new-password">
                    <InputError :message="registerForm.errors.password_confirmation" class="mt-2" />

                    <button type="submit" :disabled="registerForm.processing">Registrarse</button>
                </form>
            </div>

            <div class="form-container sign-in-container">
                <form @submit.prevent="submitLogin">
                    <h1>Iniciar Sesión</h1>
                    <input type="email" placeholder="Correo Electrónico" v-model="loginForm.email" required autocomplete="username">
                    <InputError :message="loginForm.errors.email" class="mt-2" />

                    <input type="password" placeholder="Contraseña" v-model="loginForm.password" required autocomplete="current-password">
                    <InputError :message="loginForm.errors.password" class="mt-2" />

                    <Link v-if="canResetPassword" :href="route('password.request')">¿Olvidaste tu contraseña?</Link>
                    <button type="submit" :disabled="loginForm.processing">Iniciar Sesión</button>
                </form>
            </div>

            <div class="overlay-container">
                <div class="overlay">
                    <div class="overlay-panel overlay-left">
                        <h1>¡Bienvenido de Nuevo!</h1>
                        <p>Para mantenerte conectado, por favor inicia sesión con tu información personal.</p>
                        <button class="ghost" @click="isRightPanelActive = false">Iniciar Sesión</button>
                    </div>
                    <div class="overlay-panel overlay-right">
                        <h1>¡Hola, Amigo!</h1>
                        <p>Introduce tus datos personales y comienza tu viaje con nosotros.</p>
                        <button class="ghost" @click="isRightPanelActive = true">Registrarse</button>
                    </div>
                </div>
            </div>
        </div>
    </div> </template>

<style scoped>
@import url('https://fonts.googleapis.com/css?family=Montserrat:400,800');

/* Nuevo estilo para centrar el contenedor principal */
.centered-container {
    display: flex;
    justify-content: center; /* Centrado horizontal */
    align-items: center;   /* Centrado vertical */
    min-height: 100vh;      /* Asegura que ocupe al menos toda la altura de la vista */
    width: 100vw;           /* Asegura que ocupe toda la anchura de la vista */
    background: #f6f5f7;    /* Color de fondo que tenías en body */
    /* Eliminar margin/padding del body si los tienes para evitar scrollbars */
    margin: 0; 
    padding: 0;
    font-family: 'Montserrat', sans-serif; /* Mover la fuente global aquí */
}

/* Mover estilos globales al contenedor principal o a una hoja de estilos global */
body {
    /* Estos estilos ya no son necesarios aquí si usas .centered-container */
    /* background: #f6f5f7; */
    /* display: flex; */
    /* justify-content: center; */
    /* align-items: center; */
    /* flex-direction: column; */
    /* font-family: 'Montserrat', sans-serif; */
    /* height: 100vh; */
    margin: 0; /* Asegurarse de que el body no tenga margen para el centrado */
    padding: 0; /* Asegurarse de que el body no tenga padding */
}

h1 {
    font-weight: bold;
    margin: 0;
    /* Añadido para hacer el H1 más grande y contrarrestar posible normalización de Tailwind */
    font-size: 2.2em; /* Puedes ajustar este valor */
}

h2 {
    text-align: center;
}

p {
    font-size: 14px;
    font-weight: 100;
    line-height: 20px;
    letter-spacing: 0.5px;
    margin: 20px 0 30px;
}

span {
    font-size: 12px;
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
}

button:active {
    transform: scale(0.95);
}

button:focus {
    outline: none;
}

button.ghost {
    background-color: transparent;
    border-color: #FFFFFF;
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
}

.container {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25),
        0 10px 10px rgba(0, 0, 0, 0.22);
    position: relative;
    overflow: hidden;
    width: 768px;
    max-width: 100%;
    min-height: 480px;
}

.form-container {
    position: absolute;
    top: 0;
    height: 100%;
    transition: all 0.6s ease-in-out;
}

.sign-in-container {
    left: 0;
    width: 50%;
    z-index: 2;
}

.container.right-panel-active .sign-in-container {
    transform: translateX(100%);
}

.sign-up-container {
    left: 0;
    width: 50%;
    opacity: 0;
    z-index: 1;
}

.container.right-panel-active .sign-up-container {
    transform: translateX(100%);
    opacity: 1;
    z-index: 5;
    animation: show 0.6s;
}

@keyframes show {
    0%,
    49.99% {
        opacity: 0;
        z-index: 1;
    }

    50%,
    100% {
        opacity: 1;
        z-index: 5;
    }
}

.overlay-container {
    position: absolute;
    top: 0;
    left: 50%;
    width: 50%;
    height: 100%;
    overflow: hidden;
    transition: transform 0.6s ease-in-out;
    z-index: 100;
}

.container.right-panel-active .overlay-container {
    transform: translateX(-100%);
}

.overlay {
    background: #FF416C;
    background: -webkit-linear-gradient(to right, #FF4B2B, #FF416C);
    background: linear-gradient(to right, #FF4B2B, #FF416C);
    background-repeat: no-repeat;
    background-size: cover;
    background-position: 0 0;
    color: #FFFFFF;
    position: relative;
    left: -100%;
    height: 100%;
    width: 200%;
    transform: translateX(0);
    transition: transform 0.6s ease-in-out;
}

.container.right-panel-active .overlay {
    transform: translateX(50%);
}

.overlay-panel {
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 0 40px;
    text-align: center;
    top: 0;
    height: 100%;
    width: 50%;
    transform: translateX(0);
    transition: transform 0.6s ease-in-out;
}

.overlay-left {
    transform: translateX(-20%);
}

.container.right-panel-active .overlay-left {
    transform: translateX(0);
}

.overlay-right {
    right: 0;
    transform: translateX(0);
}

.container.right-panel-active .overlay-right {
    transform: translateX(20%);
}

.social-container {
    margin: 20px 0;
}

.social-container a {
    border: 1px solid #DDDDDD;
    border-radius: 50%;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    margin: 0 5px;
    height: 40px;
    width: 40px;
}
</style>