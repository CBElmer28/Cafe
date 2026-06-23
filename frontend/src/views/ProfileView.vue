<template>
  <div class="flex flex-col gap-8 max-w-4xl mx-auto py-8">
    <!-- User Info Header -->
    <header class="p-8 rounded-3xl glass-panel flex flex-col sm:flex-row justify-between items-center gap-6 relative overflow-hidden">
      <!-- Background glow -->
      <div class="absolute inset-0 z-0 bg-gradient-to-r from-caramel-500/10 to-transparent pointer-events-none"></div>

      <div class="flex items-center gap-5 z-10">
        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-caramel-500 to-amber-700 text-white flex items-center justify-center font-extrabold text-2xl shadow-md">
          {{ userInitials }}
        </div>
        <div class="text-left">
          <h1 class="text-2xl font-extrabold text-coffee-900 dark:text-coffee-50 leading-tight">
            {{ user?.name }}
          </h1>
          <p class="text-coffee-600 dark:text-coffee-400 text-sm">
            {{ user?.email }}
          </p>
        </div>
      </div>

      <button 
        @click="handleLogout" 
        class="inline-flex items-center justify-center gap-2 border-2 border-red-500/30 dark:border-red-500/20 hover:bg-red-500/10 text-red-500 font-bold py-2.5 px-6 rounded-full transition-all hover:-translate-y-0.5 active:translate-y-0 z-10 text-sm"
      >
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
        Cerrar Sesión
      </button>
    </header>

    <!-- Orders History -->
    <section class="flex flex-col gap-6">
      <div class="text-left">
        <h2 class="text-xl font-extrabold text-coffee-900 dark:text-coffee-100 flex items-center gap-2">
          <i class="fa-solid fa-clock-rotate-left text-caramel-500"></i>
          Historial de Pedidos
        </h2>
        <p class="text-coffee-500 dark:text-coffee-400 text-xs mt-0.5">Consulta tus compras anteriores y su estado</p>
      </div>

      <!-- Loading State -->
      <div v-if="loadingOrders" class="text-center py-16 flex flex-col items-center gap-4 rounded-3xl glass-panel">
        <div class="w-10 h-10 border-4 border-coffee-200/25 border-t-caramel-500 rounded-full animate-spin"></div>
        <p class="text-coffee-600 dark:text-coffee-400 text-sm">Obteniendo tus pedidos de café...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="errorMsg" class="text-center py-16 flex flex-col items-center gap-4 rounded-3xl glass-panel">
        <p class="text-red-500 text-sm">{{ errorMsg }}</p>
        <button 
          @click="loadOrders" 
          class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-caramel-500 to-amber-700 hover:from-caramel-600 hover:to-amber-800 text-white font-semibold py-2 px-6 rounded-full text-xs shadow-md transition-all"
        >
          Reintentar
        </button>
      </div>

      <!-- Empty State -->
      <div v-else-if="orders.length === 0" class="text-center py-20 flex flex-col items-center gap-4 rounded-3xl glass-panel">
        <i class="fa-solid fa-box-open text-4xl text-coffee-300 dark:text-coffee-700"></i>
        <h3 class="text-lg font-bold text-coffee-900 dark:text-coffee-100">No tienes pedidos registrados</h3>
        <p class="text-coffee-500 dark:text-coffee-400 text-sm max-w-xs mb-2">Una vez que realices tu primera compra, podrás verla aquí.</p>
        <router-link 
          to="/" 
          class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-caramel-500 to-amber-700 hover:from-caramel-600 hover:to-amber-800 text-white font-semibold py-2.5 px-6 rounded-full text-sm shadow-md transition-all hover:-translate-y-0.5"
        >
          Ir a Comprar Café
        </router-link>
      </div>

      <!-- Orders List -->
      <div v-else class="flex flex-col gap-6">
        <div 
          v-for="order in orders" 
          :key="order.id" 
          class="rounded-2xl glass-panel overflow-hidden border border-coffee-200/10 dark:border-coffee-800/10 flex flex-col transition-all duration-300 hover:shadow-lg"
        >
          <!-- Order Summary Header -->
          <div class="p-6 bg-coffee-100/30 dark:bg-coffee-950/20 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-coffee-200/10 dark:border-coffee-800/10">
            <div class="text-left flex flex-col gap-1">
              <span class="text-xs font-bold text-coffee-400 dark:text-coffee-500">COD. PEDIDO</span>
              <span class="text-sm font-extrabold text-coffee-900 dark:text-coffee-50">#00{{ order.id }}</span>
            </div>
            
            <div class="text-left flex flex-col gap-1">
              <span class="text-xs font-bold text-coffee-400 dark:text-coffee-500">FECHA</span>
              <span class="text-sm text-coffee-700 dark:text-coffee-300">{{ formatDate(order.created_at) }}</span>
            </div>

            <div class="text-left flex flex-col gap-1">
              <span class="text-xs font-bold text-coffee-400 dark:text-coffee-500">TOTAL</span>
              <span class="text-sm font-extrabold text-caramel-500">S/. {{ parseFloat(order.total).toFixed(2) }}</span>
            </div>

            <div class="flex flex-col gap-1 items-start sm:items-end">
              <span class="text-xs font-bold text-coffee-400 dark:text-coffee-500">ESTADO</span>
              <span :class="['status-badge', `status-${order.status}`]">{{ formatStatus(order.status) }}</span>
            </div>
          </div>

          <!-- Order Content/Details -->
          <div class="p-6 flex flex-col gap-4">
            <div class="text-left text-xs text-coffee-600 dark:text-coffee-400">
              <strong>Dirección de Entrega:</strong> {{ order.delivery_address }}
            </div>

            <!-- Items -->
            <div class="flex flex-col gap-3 mt-2 border-t border-coffee-200/5 dark:border-coffee-800/5 pt-4">
              <h4 class="text-left text-xs font-bold text-coffee-500 dark:text-coffee-400 mb-1">ÍTEMS COMPRADOS</h4>
              
              <div 
                v-for="item in order.items" 
                :key="item.id" 
                class="flex items-center gap-4 py-2 border-b border-coffee-200/5 dark:border-coffee-800/5 last:border-0"
              >
                <!-- Thumbnail -->
                <div class="w-12 h-12 rounded-lg bg-coffee-100 dark:bg-coffee-950 overflow-hidden flex items-center justify-center flex-shrink-0">
                  <img 
                    :src="item.product?.image_url" 
                    :alt="item.product?.name" 
                    class="w-full h-full object-cover" 
                    @error="handleImageError"
                  />
                </div>

                <!-- Info -->
                <div class="flex-1 text-left min-w-0">
                  <h5 class="text-xs font-bold text-coffee-900 dark:text-coffee-100 truncate">
                    {{ item.product?.name || 'Café Especial' }}
                  </h5>
                  <p class="text-[10px] text-coffee-500 dark:text-coffee-400">
                    Origen: {{ item.product?.origin || 'Importado' }} | Tueste: {{ item.product?.roast_level || 'Medio' }}
                  </p>
                </div>

                <!-- Quantity & Price -->
                <div class="text-right flex flex-col justify-center flex-shrink-0">
                  <span class="text-xs font-bold text-coffee-900 dark:text-coffee-100">S/. {{ (item.price * item.quantity).toFixed(2) }}</span>
                  <span class="text-[10px] text-coffee-500 dark:text-coffee-400">{{ item.quantity }} bolsa(s) × S/. {{ parseFloat(item.price).toFixed(2) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/authStore';
import OrderService from '../api/services/OrderService';
import Swal from 'sweetalert2';

export default {
  setup() {
    const router = useRouter();
    const authStore = useAuthStore();

    const orders = ref([]);
    const loadingOrders = ref(false);
    const errorMsg = ref('');

    const user = computed(() => authStore.user);

    const userInitials = computed(() => {
      if (!user.value?.name) return 'U';
      const parts = user.value.name.split(' ');
      return parts.map(p => p[0]).slice(0, 2).join('').toUpperCase();
    });

    const loadOrders = async () => {
      loadingOrders.value = true;
      errorMsg.value = '';
      try {
        orders.value = await OrderService.getMyOrders();
      } catch (err) {
        errorMsg.value = 'No se pudo obtener el historial de tus pedidos.';
      } finally {
        loadingOrders.value = false;
      }
    };

    const handleLogout = async () => {
      const result = await Swal.fire({
        title: '¿Cerrar Sesión?',
        text: '¿Estás seguro de que deseas salir de tu cuenta?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, Salir',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: 'hsl(28, 65%, 45%)',
        cancelButtonColor: 'hsl(24, 10%, 40%)',
        background: document.documentElement.getAttribute('data-theme') === 'dark' ? 'hsl(24, 20%, 12%)' : 'hsl(24, 25%, 98%)',
        color: document.documentElement.getAttribute('data-theme') === 'dark' ? 'hsl(24, 30%, 92%)' : 'hsl(24, 45%, 15%)',
      });

      if (result.isConfirmed) {
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
      }
    };

    const formatDate = (dateString) => {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleDateString('es-PE', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
      });
    };

    const formatStatus = (status) => {
      const mapping = {
        'pending': 'Pendiente',
        'paid': 'Pagado',
        'completed': 'Completado',
        'cancelled': 'Cancelado',
      };
      return mapping[status] || status;
    };

    const handleImageError = (e) => {
      e.target.src = 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=100&auto=format&fit=crop';
    };

    onMounted(() => {
      loadOrders();
    });

    return {
      user,
      userInitials,
      orders,
      loadingOrders,
      errorMsg,
      loadOrders,
      handleLogout,
      formatDate,
      formatStatus,
      handleImageError,
    };
  },
};
</script>

<style scoped>
.status-badge {
  display: inline-flex;
  align-items: center;
  font-size: 0.65rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  line-height: 1;
}

.status-pending {
  background-color: hsla(38, 70%, 50%, 0.15);
  color: hsl(38, 70%, 45%);
}

.status-paid, .status-completed {
  background-color: hsla(142, 70%, 45%, 0.15);
  color: hsl(142, 70%, 35%);
}

.status-cancelled {
  background-color: hsla(0, 70%, 50%, 0.15);
  color: hsl(0, 70%, 45%);
}
</style>
