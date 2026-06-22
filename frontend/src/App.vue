<template>
  <div :data-theme="currentTheme" class="app-layout">
    <Navbar @toggle-theme="toggleTheme" :theme="currentTheme" />
    <main class="main-content">
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>
    <footer class="text-center py-10 px-4 border-t border-coffee-200/10 dark:border-coffee-800/10 text-[0.85rem] text-coffee-500 dark:text-coffee-400 bg-coffee-100/50 dark:bg-coffee-950/60">
      <p>&copy; 2026 Café Premium E-Commerce. Todos los derechos reservados. Hecho con ❤️ y café.</p>
    </footer>
  </div>
</template>

<script>
import { ref, onMounted, watch } from 'vue';
import Navbar from './components/Navbar.vue';

export default {
  components: { Navbar },
  setup() {
    const currentTheme = ref('light');

    const toggleTheme = () => {
      currentTheme.value = currentTheme.value === 'light' ? 'dark' : 'light';
      localStorage.setItem('theme', currentTheme.value);
    };

    onMounted(() => {
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme) {
        currentTheme.value = savedTheme;
      }
    });

    // Sync theme to document element so styles apply to html/body
    watch(currentTheme, (newTheme) => {
      document.documentElement.setAttribute('data-theme', newTheme);
    }, { immediate: true });

    return {
      currentTheme,
      toggleTheme,
    };
  },
};
</script>

<style>
.app-layout {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.main-content {
  flex: 1;
  padding: 2rem 1rem;
  max-width: 1200px;
  width: 100%;
  margin: 0 auto;
}

/* Page transitions */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
