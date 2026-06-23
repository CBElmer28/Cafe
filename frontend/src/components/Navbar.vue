<template>
  <nav class="navbar sticky top-0 z-50 px-6 py-4 glass-panel transition-all duration-300">
    <div class="max-w-6xl mx-auto flex justify-between items-center w-full">
      <router-link to="/" class="flex items-center gap-2 text-xl font-extrabold">
        <i class="fa-solid fa-mug-hot text-caramel-500 text-2xl"></i>
        <span class="bg-gradient-to-r from-coffee-900 to-caramel-500 bg-clip-text text-transparent dark:from-coffee-50 dark:to-caramel-500">
          Aroma <span class="font-serif">Real</span>
        </span>
      </router-link>

      <div class="flex items-center gap-6">
        <router-link to="/" class="font-medium text-sm text-coffee-600 hover:text-caramel-500 dark:text-coffee-300 dark:hover:text-caramel-500 transition-colors">
          Catálogo
        </router-link>
        
        <router-link to="/cart" class="flex items-center gap-2 bg-coffee-700 dark:bg-coffee-600 text-white hover:bg-caramel-500 dark:hover:bg-caramel-500 px-4 py-1.5 rounded-full font-semibold text-sm transition-all duration-300 hover:scale-105">
          <i class="fa-solid fa-cart-shopping"></i>
          <span>Carrito</span>
          <span v-if="totalCount > 0" class="bg-caramel-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold transition-all duration-300">{{ totalCount }}</span>
        </router-link>

        <!-- Auth Navigation -->
        <div v-if="isAuthenticated" class="relative">
          <button 
            @click="toggleDropdown" 
            class="flex items-center gap-2 font-medium text-sm text-coffee-700 dark:text-coffee-300 hover:text-caramel-500 cursor-pointer bg-transparent border-none outline-none py-1"
          >
            <i class="fa-regular fa-circle-user text-lg"></i>
            <span class="hidden sm:inline">{{ user?.name }}</span>
            <i class="fa-solid fa-chevron-down text-[10px] transition-transform" :class="{ 'rotate-180': dropdownOpen }"></i>
          </button>

          <!-- Dropdown menu -->
          <div 
            v-if="dropdownOpen" 
            class="absolute right-0 mt-2 w-48 rounded-xl glass-panel shadow-lg py-2 z-50 flex flex-col border border-coffee-200/10 dark:border-coffee-800/10"
          >
            <router-link 
              to="/profile" 
              @click="dropdownOpen = false" 
              class="px-4 py-2.5 text-sm text-left text-coffee-800 dark:text-coffee-200 hover:bg-caramel-500/10 hover:text-caramel-500 transition-all font-medium flex items-center gap-2"
            >
              <i class="fa-regular fa-user"></i>
              Mi Perfil
            </router-link>
            <button 
              @click="handleLogout" 
              class="px-4 py-2.5 text-sm text-left text-red-500 hover:bg-red-500/10 transition-all font-bold flex items-center gap-2 bg-transparent border-none cursor-pointer w-full"
            >
              <i class="fa-solid fa-arrow-right-from-bracket"></i>
              Cerrar Sesión
            </button>
          </div>
        </div>

        <div v-else class="flex items-center gap-4">
          <router-link 
            to="/login" 
            class="font-medium text-sm text-coffee-600 hover:text-caramel-500 dark:text-coffee-300 dark:hover:text-caramel-500 transition-colors"
          >
            Iniciar Sesión
          </router-link>
          <router-link 
            to="/register" 
            class="hidden sm:inline-flex items-center justify-center bg-coffee-200/50 dark:bg-coffee-900/50 hover:bg-caramel-500/15 border border-coffee-200/10 dark:border-coffee-800/10 text-coffee-850 dark:text-coffee-150 font-bold py-1.5 px-4 rounded-full text-xs transition-colors"
          >
            Registrarse
          </router-link>
        </div>

        <button @click="$emit('toggle-theme')" class="bg-transparent border-none text-lg cursor-pointer p-1 flex items-center transition-transform hover:rotate-12 hover:scale-110" aria-label="Toggle theme">
          <i v-if="theme === 'light'" class="fa-regular fa-moon text-coffee-900"></i>
          <i v-else class="fa-solid fa-sun text-yellow-500"></i>
        </button>
      </div>
    </div>
  </nav>
</template>

<script>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useCart } from '../composables/useCart';
import { useAuthStore } from '../stores/authStore';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';

export default {
  props: {
    theme: {
      type: String,
      required: true,
    },
  },
  emits: ['toggle-theme'],
  setup() {
    const router = useRouter();
    const { totalCount } = useCart();
    const authStore = useAuthStore();

    const dropdownOpen = ref(false);

    const isAuthenticated = computed(() => authStore.isAuthenticated);
    const user = computed(() => authStore.user);

    const toggleDropdown = (e) => {
      e.stopPropagation();
      dropdownOpen.value = !dropdownOpen.value;
    };

    const handleLogout = async () => {
      dropdownOpen.value = false;
      await authStore.logout();
      Swal.fire({
        title: 'Sesión Cerrada',
        text: 'Vuelve pronto por más café de especialidad.',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false,
        background: document.documentElement.getAttribute('data-theme') === 'dark' ? 'hsl(24, 20%, 12%)' : 'hsl(24, 25%, 98%)',
        color: document.documentElement.getAttribute('data-theme') === 'dark' ? 'hsl(24, 30%, 92%)' : 'hsl(24, 45%, 15%)',
      });
      router.push('/login');
    };

    const closeDropdown = () => {
      dropdownOpen.value = false;
    };

    onMounted(() => {
      window.addEventListener('click', closeDropdown);
    });

    onUnmounted(() => {
      window.removeEventListener('click', closeDropdown);
    });

    return {
      totalCount,
      isAuthenticated,
      user,
      dropdownOpen,
      toggleDropdown,
      handleLogout,
    };
  },
};
</script>
