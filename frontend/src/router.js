import { createRouter, createWebHistory } from 'vue-router';
import HomeView from './views/HomeView.vue';
import ProductDetailView from './views/ProductDetailView.vue';
import CartView from './views/CartView.vue';
import CheckoutView from './views/CheckoutView.vue';
import LoginView from './views/LoginView.vue';
import RegisterView from './views/RegisterView.vue';
import ProfileView from './views/ProfileView.vue';
import { useAuthStore } from './stores/authStore';

const routes = [
  { path: '/', name: 'home', component: HomeView },
  { path: '/product/:id', name: 'product-detail', component: ProductDetailView },
  { path: '/cart', name: 'cart', component: CartView },
  { path: '/checkout', name: 'checkout', component: CheckoutView, meta: { requiresAuth: true } },
  { path: '/login', name: 'login', component: LoginView },
  { path: '/register', name: 'register', component: RegisterView },
  { path: '/profile', name: 'profile', component: ProfileView, meta: { requiresAuth: true } },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0 };
  }
});

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();

  // Si hay token pero no se ha cargado el usuario, intentar recuperarlo
  if (authStore.token && !authStore.user) {
    await authStore.fetchCurrentUser();
  }

  const isAuth = authStore.isAuthenticated;

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isAuth) {
      next({
        name: 'login',
        query: { redirect: to.fullPath }
      });
    } else {
      next();
    }
  } else if ((to.name === 'login' || to.name === 'register') && isAuth) {
    next({ name: 'home' });
  } else {
    next();
  }
});

export default router;
