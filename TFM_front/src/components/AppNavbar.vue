<template>
  <nav class="navbar">
    <div class="navbar__brand">
      <RouterLink to="/" class="navbar__link" @click="goHome">Bodas</RouterLink>
    </div>

    <div class="navbar__links">
      <RouterLink to="/" class="navbar__link" @click="goHome">Inicio</RouterLink>

      <template v-if="!auth.token">
        <RouterLink to="/login" class="navbar__link">Login</RouterLink>
        <RouterLink to="/register" class="navbar__link">Registro</RouterLink>
      </template>

      <template v-else>
        <RouterLink to="/profile" class="navbar__link">Perfil</RouterLink>
        <button type="button" class="btn btn--ghost btn--small navbar__button" @click="handleLogout">
          Salir
        </button>
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
