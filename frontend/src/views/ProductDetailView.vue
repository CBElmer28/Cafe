<template>
  <div class="flex flex-col gap-6">
    <router-link to="/" class="self-start font-semibold text-coffee-600 dark:text-coffee-300 hover:text-caramel-500 transition-all duration-200 hover:-translate-x-1">
      <i class="fa-solid fa-arrow-left mr-1"></i>
      Volver al Catálogo
    </router-link>

    <div v-if="loading" class="text-center py-20 flex flex-col items-center gap-4">
      <div class="w-10 h-10 border-4 border-coffee-200/25 border-t-caramel-500 rounded-full animate-spin"></div>
      <p class="text-coffee-600 dark:text-coffee-400">Cargando detalles de este café exquisito...</p>
    </div>

    <div v-else-if="error || !product" class="text-center py-20 flex flex-col items-center gap-4">
      <p class="text-red-500">{{ error || 'No se encontró el producto de café solicitado.' }}</p>
      <router-link to="/" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-caramel-500 to-amber-700 hover:from-caramel-600 hover:to-amber-800 text-white font-semibold py-3 px-8 rounded-full shadow-md transition-all hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0">
        Volver al Catálogo
      </router-link>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 rounded-3xl glass-panel relative z-10">
      <div class="relative min-h-[350px] bg-coffee-100 dark:bg-coffee-950 flex items-center justify-center rounded-t-3xl md:rounded-l-3xl md:rounded-tr-none overflow-hidden">
        <span class="absolute top-4 left-4 bg-black/65 text-white px-4 py-1.5 rounded-full text-xs font-semibold z-10 backdrop-blur-sm">
          {{ product.origin }}
        </span>
        <img :src="product.image_url" :alt="product.name" class="w-full h-full object-cover" @error="handleImageError" />
      </div>

      <div class="p-8 md:p-12 flex flex-col gap-5 justify-center">
        <div class="flex justify-between items-center">
          <span :class="['badge', `badge-${product.roast_level}`]">{{ product.roast_level }}</span>
          <span class="text-xs text-coffee-500 dark:text-coffee-400" :class="{ 'text-caramel-500 font-bold': product.stock === 0 }">
            {{ product.stock === 0 ? 'Agotado' : `Stock: ${product.stock} unidades` }}
          </span>
        </div>

        <h1 class="text-3xl md:text-4xl font-extrabold text-coffee-900 dark:text-coffee-50 leading-tight">
          {{ product.name }}
        </h1>
        
        <p class="text-sm text-coffee-600 dark:text-coffee-400">
          <strong>Región de Origen:</strong> {{ product.origin }}
        </p>
        
        <p class="text-3xl font-black text-caramel-500 my-2">
          S/. {{ parseFloat(product.price).toFixed(2) }}
        </p>
        
        <p class="text-coffee-600 dark:text-coffee-300 text-sm md:text-base leading-relaxed">
          {{ product.description }}
        </p>

        <!-- Configuration Form -->
        <div class="flex flex-col gap-6 mt-6 pt-6 border-t border-coffee-200/10 dark:border-coffee-800/10" v-if="product.stock > 0">
          <div class="flex flex-col gap-2">
            <label for="grind-select" class="text-xs font-bold text-coffee-600 dark:text-coffee-400">Tipo de Molienda</label>
            <CoffeeSelect id="grind-select" v-model="selectedGrind" :options="grindOptions" />
          </div>

          <div class="flex flex-col gap-2">
            <label for="qty-input" class="text-xs font-bold text-coffee-600 dark:text-coffee-400">Cantidad</label>
            <div class="flex items-center w-36 bg-coffee-100 dark:bg-coffee-900 rounded-full overflow-hidden border border-coffee-200/10 dark:border-coffee-800/10">
              <button @click="adjustQty(-1)" class="w-11 h-10 bg-transparent border-none text-lg font-bold cursor-pointer text-coffee-900 dark:text-coffee-100 flex items-center justify-center hover:bg-coffee-200 dark:hover:bg-coffee-800 hover:text-caramel-500 transition-colors disabled:opacity-30 disabled:cursor-not-allowed" :disabled="quantity <= 1">-</button>
              <input id="qty-input" type="number" v-model.number="quantity" class="w-12 h-10 text-center border-none bg-transparent font-bold text-sm text-coffee-900 dark:text-coffee-100 outline-none" min="1" :max="product.stock" readonly />
              <button @click="adjustQty(1)" class="w-11 h-10 bg-transparent border-none text-lg font-bold cursor-pointer text-coffee-900 dark:text-coffee-100 flex items-center justify-center hover:bg-coffee-200 dark:hover:bg-coffee-800 hover:text-caramel-500 transition-colors disabled:opacity-30 disabled:cursor-not-allowed" :disabled="quantity >= product.stock">+</button>
            </div>
          </div>

          <button @click="onAddToCart" class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-caramel-500 to-amber-700 hover:from-caramel-600 hover:to-amber-800 text-white font-bold py-3.5 px-8 rounded-full shadow-md transition-all hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0">
            <i class="fa-solid fa-cart-shopping"></i>
            Añadir al Carrito ({{ quantity }} uds)
          </button>
        </div>

        <div v-else class="p-4 bg-caramel-500/10 border border-caramel-500/20 rounded-xl text-caramel-500 text-sm">
          <p>Lo sentimos, este grano de café está temporalmente agotado. Estamos tostando un nuevo lote en este momento.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useProducts } from '../composables/useProducts';
import { useCart } from '../composables/useCart';
import CoffeeSelect from '../components/CoffeeSelect.vue';
import Swal from 'sweetalert2';

export default {
  components: { CoffeeSelect },
  setup() {
    const route = useRoute();
    const { currentProduct: product, loading, error, loadProductById } = useProducts();
    const { addItem } = useCart();

    const selectedGrind = ref('grano');
    const grindOptions = [
      { value: 'grano', label: 'En grano (Ideal para conservar aroma)' },
      { value: 'fino', label: 'Fino (Cafeteras Espresso)' },
      { value: 'medio', label: 'Medio (Cafeteras de Goteo / Filtro)' },
      { value: 'prensa francesa', label: 'Grueso (Prensa Francesa)' }
    ];
    const quantity = ref(1);

    const adjustQty = (amount) => {
      const newQty = quantity.value + amount;
      if (newQty >= 1 && newQty <= (product.value?.stock || 1)) {
        quantity.value = newQty;
      }
    };

    const onAddToCart = () => {
      if (product.value) {
        addItem(product.value, quantity.value, selectedGrind.value);

        // Alerta de éxito con SweetAlert2
        Swal.fire({
          title: '¡Añadido al Carrito!',
          html: `Se han añadido <strong>${quantity.value} bolsa(s)</strong> de <strong>${product.value.name}</strong>.`,
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
      }
    };

    const handleImageError = (e) => {
      e.target.src = 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=600&auto=format&fit=crop';
    };

    onMounted(() => {
      loadProductById(route.params.id);
    });

    return {
      product,
      loading,
      error,
      selectedGrind,
      grindOptions,
      quantity,
      adjustQty,
      onAddToCart,
      handleImageError,
    };
  },
};
</script>
