<template>
  <div class="register">
    <h2>Registro</h2>

    <form @submit.prevent="submit">
      <input v-model="name" type="text" placeholder="Nombre completo" required class="form__input" />
      <input v-model="email" type="email" placeholder="Email" required class="form__input" />
      <input v-model="phone" type="tel" placeholder="Teléfono (opcional)" class="form__input" />
      <input v-model="address" type="text" placeholder="Dirección (opcional)" class="form__input" />

      <input v-model="password" type="password" placeholder="Contraseña" required class="form__input" />
      <input
        v-model="passwordConfirmation"
        type="password"
        placeholder="Confirmar contraseña"
        required
        class="form__input"
      />

      <label>
        Tipo de cuenta:
        <select v-model="role" class="form__select">
          <option value="user">Usuario</option>
          <option value="business">Negocio</option>
        </select>
      </label>

      <button type="submit" class="btn btn--primary" :disabled="loading">
        {{ loading ? 'Registrando...' : 'Registrarse' }}
      </button>
    </form>

    <p v-if="error" class="error">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const auth = useAuthStore()

const name = ref('')
const email = ref('')
const phone = ref('')
const address = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const role = ref('user')

const loading = ref(false)
const error = ref('')

const submit = async () => {
  error.value = ''

  if (password.value !== passwordConfirmation.value) {
    error.value = 'Las contraseñas no coinciden'
    return
  }

  loading.value = true

  try {
    await auth.register({
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
      phone: phone.value || null,
      address: address.value || null,
      role: role.value,
    })

    router.push('/')
  } catch (e) {
    if (e.response?.status === 422 && e.response.data?.errors) {
      const firstError = Object.values(e.response.data.errors)[0]?.[0]
      error.value = firstError || 'Datos inválidos'
    } else {
      error.value = e.response?.data?.message || 'Error al registrar'
    }
  } finally {
    loading.value = false
  }
}
</script>
