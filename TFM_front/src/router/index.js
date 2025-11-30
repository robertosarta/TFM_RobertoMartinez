import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import ProfileView from '../views/ProfileView.vue'
import ServiceDetailView from '../views/ServiceDetailView.vue'
import AboutView from '../views/AboutView.vue'
import ContactView from '../views/ContactView.vue'
import CookiesView from '../views/CookiesView.vue'
import PrivacyView from '../views/PrivacyView.vue'

const routes = [
  { path: '/', component: HomeView },
  { path: '/login', component: LoginView },
  { path: '/register', component: RegisterView },
  { path: '/quienes-somos', component: AboutView },
  { path: '/contacto', component: ContactView },
  { path: '/cookies', component: CookiesView },
  { path: '/privacidad', component: PrivacyView },
  { path: '/services/:id', component: ServiceDetailView },
  {
    path: '/profile',
    component: ProfileView,
    meta: { requiresAuth: true },
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

router.beforeEach((to) => {
  const auth = useAuthStore()

  if (to.meta.requiresAuth && !auth.token) {
    return '/login'
  }

  if ((to.path === '/login' || to.path === '/register') && auth.token) {
    return '/'
  }

  return true
})

export default router
