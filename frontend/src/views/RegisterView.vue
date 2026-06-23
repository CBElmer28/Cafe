<template>
  <div class="flex items-center justify-center min-h-[75vh] py-12 px-4 relative">
    <!-- Glow spots -->
    <div class="glow-spot top-[10%] right-[20%]"></div>
    <div class="glow-spot bottom-[10%] left-[20%]"></div>

    <div class="w-full max-w-md p-8 rounded-3xl glass-panel relative z-10 flex flex-col gap-6">
      <div class="text-center flex flex-col gap-2">
        <i class="fa-solid fa-mug-hot text-4xl text-caramel-500"></i>
        <h1 class="text-3xl font-extrabold text-coffee-900 dark:text-coffee-50 leading-tight mt-2">
          Crear Cuenta
        </h1>
        <p class="text-coffee-600 dark:text-coffee-400 text-sm">
          Únete a Aroma Real para registrar tus pedidos de café
        </p>
      </div>

      <form @submit.prevent="handleSubmit" class="flex flex-col gap-5">
        <div class="flex flex-col gap-2">
          <label for="name-input" class="text-xs font-bold text-coffee-600 dark:text-coffee-400">Nombre Completo</label>
          <input 
            id="name-input" 
            type="text" 
            v-model="name" 
            class="w-full p-3 bg-coffee-100/50 dark:bg-coffee-900/50 border border-coffee-200/10 dark:border-coffee-800/10 rounded-xl text-coffee-900 dark:text-coffee-100 outline-none focus:border-caramel-500 focus:ring-4 focus:ring-caramel-500/15 transition-all" 
            required 
            placeholder="Ana López" 
          />
        </div>

        <div class="flex flex-col gap-2">
          <label for="email-input" class="text-xs font-bold text-coffee-600 dark:text-coffee-400">Correo Electrónico</label>
          <input 
            id="email-input" 
            type="email" 
            v-model="email" 
            class="w-full p-3 bg-coffee-100/50 dark:bg-coffee-900/50 border border-coffee-200/10 dark:border-coffee-800/10 rounded-xl text-coffee-900 dark:text-coffee-100 outline-none focus:border-caramel-500 focus:ring-4 focus:ring-caramel-500/15 transition-all" 
            required 
            placeholder="ana.lopez@example.com" 
          />
        </div>

        <div class="flex flex-col gap-2">
          <label for="password-input" class="text-xs font-bold text-coffee-600 dark:text-coffee-400">Contraseña</label>
          <input 
            id="password-input" 
            type="password" 
            v-model="password" 
            class="w-full p-3 bg-coffee-100/50 dark:bg-coffee-900/50 border border-coffee-200/10 dark:border-coffee-800/10 rounded-xl text-coffee-900 dark:text-coffee-100 outline-none focus:border-caramel-500 focus:ring-4 focus:ring-caramel-500/15 transition-all" 
            required 
            placeholder="Mínimo 6 caracteres" 
          />
        </div>

        <div class="flex flex-col gap-2">
          <label for="confirm-password-input" class="text-xs font-bold text-coffee-600 dark:text-coffee-400">Confirmar Contraseña</label>
          <input 
            id="confirm-password-input" 
            type="password" 
            v-model="confirmPassword" 
            class="w-full p-3 bg-coffee-100/50 dark:bg-coffee-900/50 border border-coffee-200/10 dark:border-coffee-800/10 rounded-xl text-coffee-900 dark:text-coffee-100 outline-none focus:border-caramel-500 focus:ring-4 focus:ring-caramel-500/15 transition-all" 
            required 
            placeholder="••••••••" 
          />
        </div>

        <div class="bg-red-500/10 text-red-500 border border-red-500/20 p-3 rounded-lg text-sm" v-if="errorMsg">
          <p>{{ errorMsg }}</p>
        </div>

        <button 
          type="submit" 
          class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-caramel-500 to-amber-700 hover:from-caramel-600 hover:to-amber-800 text-white font-bold py-3.5 px-8 rounded-full shadow-md transition-all hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0 disabled:opacity-75 disabled:cursor-not-allowed" 
          :disabled="loading"
        >
          <i class="fa-solid fa-user-plus"></i>
          {{ loading ? 'Registrando Cuenta...' : 'Registrarse' }}
        </button>
      </form>

      <div class="h-[1px] bg-coffee-200/10 dark:bg-coffee-800/10"></div>

      <div class="text-center text-sm text-coffee-600 dark:text-coffee-400">
        ¿Ya tienes cuenta? 
        <router-link to="/login" class="text-caramel-500 font-bold hover:underline ml-1">
          Inicia sesión aquí
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '../stores/authStore';
import Swal from 'sweetalert2';

export default {
  setup() {
    const router = useRouter();
    const route = useRoute();
    const authStore = useAuthStore();

    const name = ref('');
    const email = ref('');
    const password = ref('');
    const confirmPassword = ref('');
    const loading = ref(false);
    const errorMsg = ref('');

    const handleSubmit = async () => {
      if (password.value !== confirmPassword.value) {
        errorMsg.value = 'Las contraseñas no coinciden.';
        return;
      }
      if (password.value.length < 6) {
        errorMsg.value = 'La contraseña debe tener al menos 6 caracteres.';
        return;
      }

      loading.value = true;
      errorMsg.value = '';

      try {
        await authStore.register({
          name: name.value,
          email: email.value,
          password: password.value,
        });

        // Toast de bienvenida exitoso
        Swal.fire({
          title: '¡Cuenta Registrada!',
          text: `Te damos la bienvenida a Aroma Real, ${authStore.user?.name}`,
          icon: 'success',
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          background: document.documentElement.getAttribute('data-theme') === 'dark' ? 'hsl(24, 20%, 12%)' : 'hsl(24, 25%, 98%)',
          color: document.documentElement.getAttribute('data-theme') === 'dark' ? 'hsl(24, 30%, 92%)' : 'hsl(24, 45%, 15%)',
          iconColor: 'hsl(28, 65%, 45%)',
        });

        // Redirigir según corresponda
        const nextRoute = route.query.redirect || '/';
        router.push(nextRoute);
      } catch (err) {
        if (err.response?.data?.msg) {
          // El validador del backend devuelve mensajes
          errorMsg.value = err.response.data.msg;
        } else {
          errorMsg.value = err.message || 'Error al registrar la cuenta.';
        }
      } finally {
        loading.value = false;
      }
    };

    return {
      name,
      email,
      password,
      confirmPassword,
      loading,
      errorMsg,
      handleSubmit,
    };
  },
};
</script>
