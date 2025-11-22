<template>
  <nav class="navbar">
    <div class="navbar__brand">
      <RouterLink to="/" @click="goHome">Bodas</RouterLink>
    </div>

    <div class="navbar__links">
      <RouterLink to="/" @click="goHome">Inicio</RouterLink>

      <template v-if="!auth.token">
        <RouterLink to="/login">Login</RouterLink>
        <RouterLink to="/register">Registro</RouterLink>
      </template>

      <template v-else>
        <RouterLink to="/profile">Perfil</RouterLink>
        <button type="button" @click="handleLogout">Salir</button>
      </template>
    </div>
  </nav>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const router = useRouter()

const handleLogout = async () => {
  await auth.logout()
  router.push('/login')
}

const goHome = () => {
  router.push('/').catch(() => {})
  window.dispatchEvent(new CustomEvent('reset-home-filters'))
}
</script>

<style scoped>
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem 1.5rem;
  background: #f6f6f6;
  border-bottom: 1px solid #e3e3e3;
  position: sticky;
  top: 0;
  z-index: 10;
  margin-bottom: 0.5rem;
}

.navbar__brand a {
  font-weight: 700;
  text-decoration: none;
  color: #222;
}

.navbar__links {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.navbar__links a {
  text-decoration: none;
  color: #333;
}

.navbar__links button {
  padding: 0.35rem 0.75rem;
  border: 1px solid #ccc;
  background: white;
  cursor: pointer;
  border-radius: 4px;
}

.navbar__links button:hover,
.navbar__links a:hover {
  color: #000;
}
</style>
