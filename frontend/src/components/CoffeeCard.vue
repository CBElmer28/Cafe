<template>
  <div class="flex flex-col h-full overflow-hidden rounded-2xl glass-panel group transition-all duration-300 hover:-translate-y-1 hover:shadow-premium-lg">
    <div class="relative h-48 bg-coffee-100 dark:bg-coffee-950 overflow-hidden flex items-center justify-center">
      <span class="absolute top-3 left-3 bg-black/65 text-white px-3 py-1 rounded-full text-xs font-semibold z-10 backdrop-blur-sm">
        {{ coffee.origin }}
      </span>
      <img :src="coffee.image_url" :alt="coffee.name" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-108" @error="handleImageError" />
    </div>
    
    <div class="p-5 flex flex-col flex-1">
      <div class="flex justify-between items-center mb-2">
        <span :class="['badge', `badge-${coffee.roast_level}`]">{{ coffee.roast_level }}</span>
        <span class="text-xs text-coffee-500 dark:text-coffee-400" :class="{ 'text-caramel-500 font-semibold': coffee.stock <= 20 }">
          Stock: {{ coffee.stock }}
        </span>
      </div>
      
      <h3 class="text-base font-bold text-coffee-900 dark:text-coffee-100 mb-2">{{ coffee.name }}</h3>
      <p class="text-xs text-coffee-600 dark:text-coffee-300 mb-6 flex-1">{{ truncateText(coffee.description, 90) }}</p>
      
      <div class="flex flex-col gap-3 border-t border-coffee-200/10 dark:border-coffee-800/10 pt-4 mt-auto">
        <div class="flex justify-between items-center">
          <span class="text-lg font-extrabold text-caramel-500">S/. {{ parseFloat(coffee.price).toFixed(2) }}</span>
          
          <!-- Quantity selector in card -->
          <div class="flex items-center bg-coffee-100 dark:bg-coffee-900 rounded-full overflow-hidden border border-coffee-200/10 dark:border-coffee-800/10" v-if="coffee.stock > 0">
            <button @click="adjustQty(-1)" class="w-7 h-7 bg-transparent border-none text-xs font-bold cursor-pointer flex items-center justify-center text-coffee-900 dark:text-coffee-100 hover:bg-coffee-200 dark:hover:bg-coffee-800 hover:text-caramel-500" :disabled="quantity <= 1">-</button>
            <span class="w-6 text-center text-xs font-bold text-coffee-900 dark:text-coffee-100">{{ quantity }}</span>
            <button @click="adjustQty(1)" class="w-7 h-7 bg-transparent border-none text-xs font-bold cursor-pointer flex items-center justify-center text-coffee-900 dark:text-coffee-100 hover:bg-coffee-200 dark:hover:bg-coffee-800 hover:text-caramel-500" :disabled="quantity >= coffee.stock">+</button>
          </div>
        </div>

        <div class="flex justify-between items-center gap-2">
          <router-link :to="`/product/${coffee.id}`" class="flex-1 inline-flex items-center justify-center gap-2 border border-coffee-700 dark:border-coffee-500 hover:bg-coffee-100 dark:hover:bg-coffee-900 text-coffee-900 dark:text-coffee-100 font-semibold py-1.5 rounded-full text-xs transition-colors duration-200" title="Ver detalles">
            <i class="fa-solid fa-eye text-xs"></i> Ver Detalles
          </router-link>
          
          <button @click="onAddToCart" class="flex-1 bg-coffee-700 hover:bg-caramel-500 text-white border-none py-1.5 px-4 rounded-full font-semibold text-xs transition-all duration-200 disabled:bg-coffee-200 disabled:text-coffee-400 dark:disabled:bg-coffee-900 dark:disabled:text-coffee-600 disabled:cursor-not-allowed flex items-center justify-center gap-1.5" :disabled="coffee.stock === 0">
            <i class="fa-solid fa-cart-plus text-xs"></i>
            {{ coffee.stock === 0 ? 'Sin Stock' : 'Añadir' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import { useCart } from '../composables/useCart';
import Swal from 'sweetalert2';

export default {
  props: {
    coffee: {
      type: Object,
      required: true,
    },
  },
  setup(props) {
    const { addItem } = useCart();
    const quantity = ref(1);

    const truncateText = (text, limit) => {
      if (text.length <= limit) return text;
      return text.substring(0, limit) + '...';
    };

    const adjustQty = (amount) => {
      const newQty = quantity.value + amount;
      if (newQty >= 1 && newQty <= props.coffee.stock) {
        quantity.value = newQty;
      }
    };

    const onAddToCart = () => {
      addItem(props.coffee, quantity.value, 'grano');

      // Alerta de éxito con SweetAlert2
      Swal.fire({
        title: '¡Añadido al Carrito!',
        html: `Se han añadido <strong>${quantity.value} bolsa(s)</strong> de <strong>${props.coffee.name}</strong>.`,
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

      quantity.value = 1; // Restablecer cantidad a 1
    };

    const handleImageError = (e) => {
      e.target.src = 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=300&auto=format&fit=crop';
    };

    return {
      quantity,
      truncateText,
      adjustQty,
      onAddToCart,
      handleImageError,
    };
  },
};
</script>
