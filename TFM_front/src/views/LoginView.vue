<template>
  <div class="login">
    <h2>Iniciar sesión</h2>

    <form @submit.prevent="submit">
      <input v-model="email" type="email" placeholder="Email" />
      <input v-model="password" type="password" placeholder="Password" />

      <button type="submit">Entrar</button>
    </form>

    <p v-if="error">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const router = useRouter()

const email = ref('')
const password = ref('')
const error = ref('')

const submit = async () => {
  error.value = ''

  try {
    await auth.login(email.value, password.value)
    router.push('/')
  } catch (e) {
    error.value = e?.response?.data?.message || 'Credenciales incorrectas'
  }
}
</script>
