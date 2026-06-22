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

        <button @click="$emit('toggle-theme')" class="bg-transparent border-none text-lg cursor-pointer p-1 flex items-center transition-transform hover:rotate-12 hover:scale-110" aria-label="Toggle theme">
          <i v-if="theme === 'light'" class="fa-regular fa-moon text-coffee-900"></i>
          <i v-else class="fa-solid fa-sun text-yellow-500"></i>
        </button>
      </div>
    </div>
  </nav>
</template>

<script>
import { useCart } from '../composables/useCart';

export default {
  props: {
    theme: {
      type: String,
      required: true,
    },
  },
  emits: ['toggle-theme'],
  setup() {
    const { totalCount } = useCart();
    return { totalCount };
  },
};
</script>
