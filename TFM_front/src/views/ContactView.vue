<template>
  <section class="section">
    <h1>Contacto</h1>
    <p class="muted">
      ¿Tienes dudas sobre Mi Boda o quieres proponer mejoras? Escríbeme y te responderé lo antes posible.
    </p>

    <div class="grid-2">
      <div class="card">
        <h3>Formulario</h3>
        <form class="form" @submit.prevent="sendMailto">
          <label class="form-row">
            Nombre
            <input v-model="form.name" type="text" class="form__input" placeholder="Tu nombre" required />
          </label>
          <label class="form-row">
            Email
            <input v-model="form.email" type="email" class="form__input" placeholder="tu@email.com" required />
          </label>
          <label class="form-row">
            Mensaje
            <textarea
              v-model="form.message"
              rows="4"
              class="form__textarea"
              placeholder="Cuéntame en qué puedo ayudarte"
              required
            ></textarea>
          </label>
          <div class="form-actions">
            <button type="submit" class="btn btn--primary">Enviar</button>
            <span class="muted">* Abre tu cliente de correo con los datos.</span>
          </div>
        </form>
      </div>

      <div class="card contact-links">
        <h3>Datos directos</h3>
        <p><strong>Email:</strong> robertomartinez@example.com</p>
        <p>
          <strong>LinkedIn:</strong>
          <a
            href="https://www.linkedin.com/in/roberto-mart%C3%ADnez-resano/"
            target="_blank"
            rel="noreferrer"
          >
            linkedin.com/in/roberto-martínez-resano
          </a>
        </p>
        <p>
          <strong>GitHub:</strong>
          <a href="https://github.com/robertosarta" 
          target="_blank" 
          rel="noreferrer">
            github.com/robertosarta
          </a>
        </p>
        <p class="muted">También puedes contactarme desde tu cliente de correo si prefieres.</p>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'

const form = ref({
  name: '',
  email: '',
  message: '',
})

const sendMailto = () => {
  const subject = encodeURIComponent(`Consulta desde Mi Boda: ${form.value.name || 'Contacto'}`)
  const bodyLines = [
    `Nombre: ${form.value.name}`,
    `Email: ${form.value.email}`,
    '',
    form.value.message,
  ]
  const body = encodeURIComponent(bodyLines.join('\n'))
  window.location.href = `mailto:robertomartinez@example.com?subject=${subject}&body=${body}`
}
</script>
