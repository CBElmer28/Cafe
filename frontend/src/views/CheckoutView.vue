<template>
  <div class="flex flex-col gap-8">
    <h1 class="text-3xl font-extrabold text-coffee-900 dark:text-coffee-100">Procesar Pedido</h1>

    <div v-if="orderSuccess" class="text-center py-16 px-8 flex flex-col items-center gap-6 max-w-2xl mx-auto rounded-3xl glass-panel">
      <i class="fa-solid fa-circle-check text-5xl text-caramel-500 animate-bounce"></i>
      <h2 class="text-2xl font-bold text-caramel-500">¡Pedido Confirmado con Éxito!</h2>
      <p class="text-coffee-600 dark:text-coffee-300 max-w-md">Gracias por tu compra. Tu lote de café de especialidad ya está en camino a ser preparado.</p>
      
      <div class="bg-coffee-100/50 dark:bg-coffee-950/50 border border-coffee-200/10 dark:border-coffee-800/10 p-6 rounded-xl w-full text-left flex flex-col gap-2.5 text-sm text-coffee-800 dark:text-coffee-200">
        <h3 class="text-base font-bold mb-1 border-b border-coffee-200/10 dark:border-coffee-800/10 pb-2 text-coffee-900 dark:text-coffee-100">Detalles de la Orden</h3>
        <p><strong>Código de Pedido:</strong> #00{{ createdOrder.id }}</p>
        <p><strong>Cliente:</strong> {{ createdOrder.customer_name }}</p>
        <p><strong>Correo:</strong> {{ createdOrder.customer_email }}</p>
        <p><strong>Dirección de Entrega:</strong> {{ createdOrder.delivery_address }}</p>
        <p><strong>Total de Compra:</strong> S/. {{ parseFloat(createdOrder.total).toFixed(2) }}</p>
        <p class="flex items-center gap-2"><strong>Estado del Pedido:</strong> <span class="bg-caramel-500/15 text-caramel-600 dark:text-caramel-400 px-3 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider">{{ createdOrder.status }}</span></p>
      </div>

      <button @click="goHome" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-caramel-500 to-amber-700 hover:from-caramel-600 hover:to-amber-800 text-white font-semibold py-3 px-8 rounded-full shadow-md transition-all hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0">
        Volver a la Tienda
      </button>
    </div>

    <div v-else-if="items.length === 0" class="text-center py-20 flex flex-col items-center gap-4 rounded-3xl glass-panel">
      <i class="fa-solid fa-cart-shopping text-5xl text-coffee-300 dark:text-coffee-700"></i>
      <h2 class="text-xl font-bold text-coffee-900 dark:text-coffee-100">No hay ítems para procesar</h2>
      <p class="text-coffee-500 dark:text-coffee-400 max-w-sm mb-4">Debes añadir café al carrito de compras antes de realizar el checkout.</p>
      <router-link to="/" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-caramel-500 to-amber-700 hover:from-caramel-600 hover:to-amber-800 text-white font-semibold py-3 px-8 rounded-full shadow-md transition-all hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0">
        Explorar Catálogo
      </router-link>
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
      <!-- Checkout Form -->
      <form @submit.prevent="handleSubmit" class="lg:col-span-2 p-8 rounded-2xl glass-panel flex flex-col gap-6">
        <h2 class="text-xl font-bold text-coffee-900 dark:text-coffee-100">Detalles de Envío y Facturación</h2>
        
        <div class="flex flex-col gap-2">
          <label for="name-input" class="text-xs font-bold text-coffee-600 dark:text-coffee-400">Nombre Completo</label>
          <input id="name-input" type="text" v-model="formData.customer_name" class="w-full p-3 bg-coffee-100/50 dark:bg-coffee-900/50 border border-coffee-200/10 dark:border-coffee-800/10 rounded-xl text-coffee-900 dark:text-coffee-100 outline-none focus:border-caramel-500 focus:ring-4 focus:ring-caramel-500/15 transition-all select-filter" required placeholder="Juan Pérez" />
        </div>

        <div class="flex flex-col gap-2">
          <label for="email-input" class="text-xs font-bold text-coffee-600 dark:text-coffee-400">Correo Electrónico</label>
          <input id="email-input" type="email" v-model="formData.customer_email" class="w-full p-3 bg-coffee-100/50 dark:bg-coffee-900/50 border border-coffee-200/10 dark:border-coffee-800/10 rounded-xl text-coffee-900 dark:text-coffee-100 outline-none focus:border-caramel-500 focus:ring-4 focus:ring-caramel-500/15 transition-all select-filter" required placeholder="juan.perez@example.com" />
          <span class="text-[10px] text-coffee-400 dark:text-coffee-500">Te enviaremos los detalles del seguimiento del pedido aquí.</span>
        </div>

        <div class="flex flex-col gap-2">
          <label for="address-input" class="text-xs font-bold text-coffee-600 dark:text-coffee-400">Dirección de Entrega</label>
          <textarea id="address-input" v-model="formData.delivery_address" class="w-full p-3 bg-coffee-100/50 dark:bg-coffee-900/50 border border-coffee-200/10 dark:border-coffee-800/10 rounded-xl text-coffee-900 dark:text-coffee-100 outline-none focus:border-caramel-500 focus:ring-4 focus:ring-caramel-500/15 transition-all select-filter resize-y" required placeholder="Av. Siempre Viva 742, Ciudad" rows="3"></textarea>
        </div>

        <div class="h-[1px] bg-coffee-200/10 dark:bg-coffee-800/10"></div>

        <div class="bg-coffee-100/50 dark:bg-coffee-900/50 border border-dashed border-caramel-500/50 p-5 rounded-xl">
          <h3 class="text-sm font-bold text-caramel-500 mb-1">Método de Pago (Simulado)</h3>
          <p class="text-xs text-coffee-600 dark:text-coffee-400">El checkout se procesa como pago contra entrega o simulación de pasarela de pago bancario directa.</p>
        </div>

        <div class="bg-red-500/10 text-red-500 border border-red-500/20 p-3 rounded-lg text-sm" v-if="submitError">
          <p>{{ submitError }}</p>
        </div>

        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-caramel-500 to-amber-700 hover:from-caramel-600 hover:to-amber-800 text-white font-bold py-3.5 px-8 rounded-full shadow-md transition-all hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0 disabled:opacity-75 disabled:cursor-not-allowed" :disabled="isSubmitting">
          <i class="fa-solid fa-mug-hot"></i>
          {{ isSubmitting ? 'Procesando Compra...' : 'Confirmar y Pagar (Simulado)' }}
        </button>
      </form>

      <!-- Cart Preview -->
      <div class="flex flex-col gap-6">
        <div class="p-8 rounded-2xl glass-panel flex flex-col gap-5 sticky top-28">
          <h2 class="text-lg font-bold text-coffee-900 dark:text-coffee-100">Tu Pedido</h2>
          
          <div class="flex flex-col gap-3">
            <div v-for="item in items" :key="item.product_id + '-' + item.grind_type" class="flex justify-between text-xs text-coffee-600 dark:text-coffee-300">
              <span class="max-w-[75%] truncate">{{ item.quantity }}x {{ item.name }} ({{ item.grind_type }})</span>
              <span class="font-bold">S/. {{ (item.price * item.quantity).toFixed(2) }}</span>
            </div>
          </div>

          <div class="h-[1px] bg-coffee-200/10 dark:bg-coffee-800/10"></div>

          <div class="flex justify-between text-sm text-coffee-600 dark:text-coffee-400">
            <span>Subtotal</span>
            <span>S/. {{ totalPrice.toFixed(2) }}</span>
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
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useCart } from '../composables/useCart';
import OrderService from '../api/services/OrderService';

export default {
  setup() {
    const router = useRouter();
    const { items, totalPrice, clearCart } = useCart();

    const isSubmitting = ref(false);
    const orderSuccess = ref(false);
    const createdOrder = ref(null);
    const submitError = ref(null);

    const formData = reactive({
      customer_name: '',
      customer_email: '',
      delivery_address: '',
    });

    const handleSubmit = async () => {
      isSubmitting.value = true;
      submitError.value = null;

      try {
        const orderData = {
          ...formData,
          items: items.value.map(item => ({
            product_id: item.product_id,
            quantity: item.quantity,
          })),
        };

        const response = await OrderService.placeOrder(orderData);
        createdOrder.value = response;
        orderSuccess.value = true;
        clearCart();
      } catch (err) {
        if (err.response?.data?.msg) {
          const msgKey = err.response.data.msg;
          const mapping = {
            'out_of_stock': 'Lo sentimos, alguno de los cafés en tu carrito se ha agotado o supera el stock disponible.',
            'product_not_found': 'El producto seleccionado no existe en nuestro catálogo.',
          };
          submitError.value = mapping[msgKey] || 'Hubo un error al procesar tu pedido. Por favor, inténtalo de nuevo.';
        } else {
          submitError.value = 'No se pudo conectar con el servidor de pagos. Inténtalo de nuevo.';
        }
      } finally {
        isSubmitting.value = false;
      }
    };

    const goHome = () => {
      router.push('/');
    };

    return {
      items,
      totalPrice,
      formData,
      isSubmitting,
      orderSuccess,
      createdOrder,
      submitError,
      handleSubmit,
      goHome,
    };
  },
};
</script>
