<template>
  <div class="flex flex-col gap-12">
    <!-- Hero Banner -->
    <header class="relative grid grid-cols-1 md:grid-cols-2 items-center gap-8 p-8 md:p-16 rounded-3xl overflow-hidden glass-panel">
      <!-- Blurred Background Image overlay -->
      <div class="absolute inset-0 z-0 bg-cover bg-center filter blur-[4px] opacity-30 dark:opacity-15 pointer-events-none" style="background-image: url('/images/hero_bg.png');"></div>
      
      <!-- Content layers -->
      <div class="flex flex-col items-start text-left z-10">
        <span class="text-xs md:text-sm uppercase tracking-widest font-extrabold text-caramel-500">
          Granos Selectos de Especialidad
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 mt-2 leading-tight text-coffee-900 dark:text-coffee-50">
          Despierta tus Sentidos con <br>
          <span class="text-caramel-500 font-serif">Aroma Real</span>
        </h1>
        <p class="text-coffee-600 dark:text-coffee-300 text-sm md:text-base mb-8 max-w-md leading-relaxed">
          Llevamos a tu taza los mejores granos de café seleccionados a mano, tostados artesanalmente y procedentes de los rincones más fértiles del mundo.
        </p>
        <a href="#catalogo" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-caramel-500 to-amber-700 hover:from-caramel-600 hover:to-amber-800 text-white font-semibold py-3 px-8 rounded-full shadow-md transition-all hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0">
          Explorar Catálogo 
          <i class="fa-solid fa-mug-hot"></i>
        </a>
      </div>
      <div class="flex justify-center items-center z-10">
        <div class="relative mt-10">
          <div class="steam-container">
            <div class="steam-line"></div>
            <div class="steam-line"></div>
            <div class="steam-line"></div>
          </div>
          <div class="relative w-36 h-24">
            <!-- Cup Handle (rendered behind the body) -->
            <div class="absolute top-[15px] -right-[18px] w-12 h-[55px] border-[10px] border-coffee-800 dark:border-coffee-700 rounded-full"></div>
            <!-- Cup Body -->
            <div class="relative w-full h-full bg-gradient-to-br from-coffee-700 to-coffee-800 dark:from-coffee-600 dark:to-coffee-700 rounded-b-[70px] shadow-md z-10"></div>
          </div>
          <div class="w-44 h-4 bg-coffee-800 dark:bg-coffee-200 rounded-full mx-auto mt-2.5 shadow-md"></div>
        </div>
      </div>
    </header>

    <!-- Glow spots behind catalog -->
    <div class="glow-spot top-[300px] left-[10%]"></div>
    <div class="glow-spot top-[700px] right-[10%]"></div>

    <!-- Catalog Section -->
    <section id="catalogo" class="flex flex-col gap-8">
      <div class="text-center">
        <h2 class="text-3xl font-bold text-coffee-900 dark:text-coffee-100">Nuestra Colección de Granos</h2>
        <p class="text-coffee-500 dark:text-coffee-400 text-sm mt-1">Filtra y encuentra tu tueste y origen ideal</p>
      </div>

      <!-- Filters -->
      <div class="flex gap-6 items-end p-6 flex-wrap rounded-2xl glass-panel relative z-30">
        <div class="flex flex-col gap-2 flex-grow min-w-[200px]">
          <label for="origin-filter" class="text-xs font-bold text-coffee-600 dark:text-coffee-400">Origen</label>
          <CoffeeSelect id="origin-filter" v-model="selectedOrigin" :options="originsOptions" />
        </div>
        
        <div class="flex flex-col gap-2 flex-grow min-w-[200px]">
          <label for="roast-filter" class="text-xs font-bold text-coffee-600 dark:text-coffee-400">Nivel de Tueste</label>
          <CoffeeSelect id="roast-filter" v-model="selectedRoast" :options="roastLevelsOptions" />
        </div>

        <button @click="clearFilters" class="border border-coffee-200/20 dark:border-coffee-800/20 text-coffee-600 dark:text-coffee-300 px-6 py-3 rounded-full font-semibold hover:bg-coffee-100 dark:hover:bg-coffee-900 hover:text-caramel-500 hover:border-caramel-500 transition-all h-[46px] flex items-center" v-if="selectedOrigin || selectedRoast">
          Limpiar Filtros ×
        </button>
      </div>

      <!-- States -->
      <div v-if="loading" class="text-center py-16 flex flex-col items-center gap-4">
        <div class="w-10 h-10 border-4 border-coffee-200/25 border-t-caramel-500 rounded-full animate-spin"></div>
        <p class="text-coffee-600 dark:text-coffee-400">Cargando granos de café excepcionales...</p>
      </div>

      <div v-else-if="error" class="text-center py-16 flex flex-col items-center gap-4">
        <p class="text-red-500">{{ error }}</p>
        <button @click="loadProducts" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-caramel-500 to-amber-700 hover:from-caramel-600 hover:to-amber-800 text-white font-semibold py-3 px-8 rounded-full shadow-md transition-all hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0">
          Reintentar
        </button>
      </div>

      <div v-else-if="filteredProducts.length === 0" class="text-center py-16 flex flex-col items-center gap-4 rounded-2xl glass-panel">
        <i class="fa-solid fa-box-open text-4xl text-coffee-300 dark:text-coffee-700"></i>
        <h3 class="text-lg font-bold">No se encontraron cafés</h3>
        <p class="text-coffee-500 dark:text-coffee-400 max-w-sm mb-2">Intenta cambiar los filtros de origen o nivel de tueste.</p>
        <button @click="clearFilters" class="inline-flex items-center justify-center gap-2 border-2 border-coffee-700 dark:border-coffee-500 hover:bg-coffee-100 dark:hover:bg-coffee-900 text-coffee-900 dark:text-coffee-100 font-semibold py-2.5 px-6 rounded-full transition-all hover:-translate-y-0.5">
          Restablecer filtros
        </button>
      </div>

      <!-- Grid -->
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <CoffeeCard v-for="coffee in filteredProducts" :key="coffee.id" :coffee="coffee" />
      </div>
    </section>
  </div>
</template>

<script>
import { onMounted, computed } from 'vue';
import { useProducts } from '../composables/useProducts';
import CoffeeCard from '../components/CoffeeCard.vue';
import CoffeeSelect from '../components/CoffeeSelect.vue';

export default {
  components: { CoffeeCard, CoffeeSelect },
  setup() {
    const {
      products,
      loading,
      error,
      selectedRoast,
      selectedOrigin,
      filteredProducts,
      origins,
      roastLevels,
      loadProducts
    } = useProducts();

    const originsOptions = computed(() => {
      const opts = origins.value.map(org => ({ value: org, label: org }));
      return [{ value: '', label: 'Todos los Orígenes' }, ...opts];
    });

    const roastLevelsOptions = computed(() => {
      const opts = roastLevels.value.map(rst => ({ value: rst, label: rst }));
      return [{ value: '', label: 'Todos los Tuestes' }, ...opts];
    });

    const clearFilters = () => {
      selectedRoast.value = '';
      selectedOrigin.value = '';
    };

    onMounted(() => {
      loadProducts();
    });

    return {
      loading,
      error,
      selectedRoast,
      selectedOrigin,
      filteredProducts,
      originsOptions,
      roastLevelsOptions,
      clearFilters,
      loadProducts,
    };
  },
};
</script>
