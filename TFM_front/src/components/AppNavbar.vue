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
