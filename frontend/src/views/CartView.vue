<template>
  <div class="flex flex-col gap-8">
    <h1 class="text-3xl font-extrabold text-coffee-900 dark:text-coffee-100">Tu Carrito de Café</h1>

    <div v-if="items.length === 0" class="text-center py-20 flex flex-col items-center gap-4 rounded-3xl glass-panel">
      <i class="fa-solid fa-mug-hot text-5xl text-coffee-300 dark:text-coffee-700"></i>
      <h2 class="text-xl font-bold text-coffee-900 dark:text-coffee-100">Tu carrito está vacío</h2>
      <p class="text-coffee-500 dark:text-coffee-400 max-w-sm mb-4">Explora nuestra exclusiva selección de café de especialidad y añade tus favoritos.</p>
      <router-link to="/" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-caramel-500 to-amber-700 hover:from-caramel-600 hover:to-amber-800 text-white font-semibold py-3 px-8 rounded-full shadow-md transition-all hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0">
        Ver Catálogo
      </router-link>
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
      <!-- Items List -->
      <div class="lg:col-span-2 flex flex-col gap-6">
        <div v-for="item in items" :key="item.product_id + '-' + item.grind_type" class="flex gap-6 p-5 rounded-2xl glass-panel group transition-all duration-300 hover:-translate-y-0.5">
          <div class="w-24 h-24 rounded-xl bg-coffee-100 dark:bg-coffee-950 overflow-hidden flex items-center justify-center flex-shrink-0">
            <img :src="item.image_url" :alt="item.name" class="w-full h-full object-cover" @error="handleImageError" />
          </div>

          <div class="flex-1 flex flex-col gap-1">
            <div class="flex justify-between items-start">
              <h3 class="text-base font-bold text-coffee-900 dark:text-coffee-100">{{ item.name }}</h3>
              <button @click="removeItem(item.product_id, item.grind_type)" class="bg-transparent border-none text-2xl text-coffee-300 hover:text-caramel-500 cursor-pointer line-none transition-colors" title="Quitar item">×</button>
            </div>
            
            <p class="text-xs text-coffee-500 dark:text-coffee-400"><strong>Origen:</strong> {{ item.origin }}</p>
            <p class="text-xs text-coffee-500 dark:text-coffee-400"><strong>Molienda:</strong> {{ formatGrind(item.grind_type) }}</p>

            <div class="flex justify-between items-center mt-auto pt-3 border-t border-coffee-200/5 dark:border-coffee-800/5">
              <div class="flex items-center bg-coffee-100 dark:bg-coffee-900 rounded-full overflow-hidden border border-coffee-200/10 dark:border-coffee-800/10">
                <button @click="updateQuantity(item.product_id, item.grind_type, item.quantity - 1)" class="w-8 h-8 bg-transparent border-none text-base font-bold cursor-pointer flex items-center justify-center text-coffee-900 dark:text-coffee-100 hover:bg-coffee-200 dark:hover:bg-coffee-800 hover:text-caramel-500" :disabled="item.quantity <= 1">-</button>
                <span class="w-9 text-center text-sm font-bold">{{ item.quantity }}</span>
                <button @click="updateQuantity(item.product_id, item.grind_type, item.quantity + 1)" class="w-8 h-8 bg-transparent border-none text-base font-bold cursor-pointer flex items-center justify-center text-coffee-900 dark:text-coffee-100 hover:bg-coffee-200 dark:hover:bg-coffee-800 hover:text-caramel-500" :disabled="item.quantity >= item.maxStock">+</button>
              </div>

              <div class="flex flex-col items-end">
                <span class="text-[10px] text-coffee-400 dark:text-coffee-500">S/. {{ item.price.toFixed(2) }} ud.</span>
                <span class="text-base font-bold text-caramel-500">S/. {{ (item.price * item.quantity).toFixed(2) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Cart Summary -->
      <div class="flex flex-col gap-6">
        <div class="p-8 rounded-2xl glass-panel flex flex-col gap-5 sticky top-28">
          <h2 class="text-lg font-bold text-coffee-900 dark:text-coffee-100">Resumen del Pedido</h2>
          
          <div class="flex justify-between text-sm text-coffee-600 dark:text-coffee-400">
            <span>Bolsas de café</span>
            <span>{{ totalCount }} uds</span>
          </div>
          
          <div class="flex justify-between text-sm text-coffee-600 dark:text-coffee-400">
            <span>Envío</span>
            <span class="text-caramel-500 font-semibold">Gratis</span>
          </div>

          <div class="h-[1px] bg-coffee-200/10 dark:bg-coffee-800/10"></div>

          <div class="flex justify-between text-lg font-extrabold text-coffee-900 dark:text-coffee-50">
            <span>Total</span>
            <span class="text-caramel-500">S/. {{ totalPrice.toFixed(2) }}</span>
          </div>

          <router-link to="/checkout" class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-caramel-500 to-amber-700 hover:from-caramel-600 hover:to-amber-800 text-white font-bold py-3.5 px-8 rounded-full shadow-md transition-all hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0">
            Proceder al Checkout
            <i class="fa-solid fa-credit-card"></i>
          </router-link>
          
          <router-link to="/" class="w-full inline-flex items-center justify-center gap-2 border-2 border-coffee-700 dark:border-coffee-500 hover:bg-coffee-100 dark:hover:bg-coffee-900 text-coffee-900 dark:text-coffee-100 font-semibold py-2.5 px-6 rounded-full transition-all hover:-translate-y-0.5">
            Seguir Comprando
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useCart } from '../composables/useCart';

export default {
  setup() {
    const { items, totalPrice, totalCount, updateQuantity, removeItem } = useCart();

    const formatGrind = (grind) => {
      const mapping = {
        'grano': 'En grano',
        'fino': 'Fino (Espresso)',
        'medio': 'Medio (Filtro)',
        'prensa francesa': 'Grueso (Prensa Francesa)'
      };
      return mapping[grind] || grind;
    };

    const handleImageError = (e) => {
      e.target.src = 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=150&auto=format&fit=crop';
    };

    return {
      items,
      totalPrice,
      totalCount,
      updateQuantity,
      removeItem,
      formatGrind,
      handleImageError,
    };
  },
};
</script>
