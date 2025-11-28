<template>
  <div class="service" v-if="service">
    <h1 class="service__title">{{ service.name }}</h1>

    <div class="service__layout">
      <div class="service__visual">
        <div class="service__carousel" v-if="images.length">
          <img
            :src="images[currentImage]?.url || fallbackImage"
            :alt="images[currentImage]?.caption || service.name"
            class="carousel__image"
          />
          <div class="carousel__controls" v-if="images.length > 1">
            <button class="btn btn--ghost btn--small" @click="prevImage" aria-label="Anterior">
              &#8592;
            </button>
            <span>{{ currentImage + 1 }} / {{ images.length }}</span>
            <button class="btn btn--ghost btn--small" @click="nextImage" aria-label="Siguiente">
              &#8594;
            </button>
          </div>
        </div>
        <div v-else class="carousel__placeholder">
          <p>Sin imágenes</p>
        </div>
      </div>

      <aside class="service__info">
        <p class="service__price">Precio: {{ euro(service.price) }}</p>
        <p class="service__meta">
          <span v-if="service.subcategory">
            {{ service.subcategory.name }}
            <span v-if="service.subcategory.category">
              · {{ service.subcategory.category.name }}
            </span>
          </span>
        </p>
        <p class="service__desc">{{ service.description }}</p>
        <div class="service__contact">
          <p v-if="service.email">Email: {{ service.email }}</p>
          <p v-if="service.phone">Teléfono: {{ service.phone }}</p>
          <p v-if="service.address">
            Dirección:
            {{ service.address.street }}, {{ service.address.city }} {{ service.address.zip }}
          </p>
        </div>

        <div class="info-form">
          <h3>Pide información al proveedor</h3>
          <form @submit.prevent="sendInquiry">
            <label>
              Nombre y apellidos
              <input v-model="inquiry.name" type="text" required class="form__input" />
            </label>
            <label>
              Teléfono
              <input v-model="inquiry.phone" type="tel" class="form__input" />
            </label>
            <label>
              Email
              <input v-model="inquiry.email" type="email" required class="form__input" />  
              <!-- Quizas redundante. Por si escribe desde otro correo distinto a donde quiere recibir la info -->
            </label>
            <label>
              Fecha de la boda
              <input v-model="inquiry.date" type="date" class="form__input" />
            </label>
            <label>
              Mensaje
              <textarea
                v-model="inquiry.message"
                rows="3"
                placeholder="Tus dudas..."
                class="form__textarea"
              ></textarea>
            </label>
            <button type="submit" class="btn btn--primary btn--small">Enviar solicitud</button>
          </form>
        </div>
      </aside>
    </div>

    <section class="comments">
      <h3>Añadir comentario</h3>
      <div class="comment-box">
        <textarea
          v-model="commentForm.comment"
          placeholder="Añadir un comentario..."
          rows="3"
          ref="commentTextarea"
          @input="resizeCommentBox"
          :disabled="!auth.token || commentLoading"
          class="form__textarea"
        ></textarea>
        <div class="comment-actions">
          <label class="rating-select" aria-label="Puntuación">
            <span aria-hidden="true">⭐</span>
            <select
              v-model.number="commentForm.rating"
              :disabled="!auth.token || commentLoading"
              class="form__select"
            >
              <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
            </select>
          </label>
          <button
            type="button"
            class="btn btn--primary btn--small"
            :disabled="!auth.token || commentLoading || !commentForm.comment"
            @click="submitComment"
          >
            {{ commentLoading ? 'Enviando...' : 'Publicar' }}
          </button>
        </div>
        <p v-if="!auth.token" class="hint">Inicia sesión para comentar.</p>
        <p v-if="commentError" class="error">{{ commentError }}</p>
      </div>

      <div class="comment-list">
        <p v-if="reviews.length === 0 && !reviewsLoading">Sin comentarios aún.</p>
        <p v-if="reviewsLoading">Cargando comentarios...</p>
        <article v-for="rev in reviews" :key="rev.id" class="comment-item">
          <div class="comment-item__header">
            <strong>{{ rev.user?.name || 'Usuario' }}</strong>
            <span class="comment-item__rating">★ {{ rev.rating }}/5</span>
          </div>
          <p class="comment-item__text">{{ rev.comment || 'Sin comentario' }}</p>
        </article>
      </div>
    </section>
  </div>

  <div v-else>
    <p v-if="loading">Cargando servicio...</p>
    <p v-else class="error">{{ error || 'Servicio no encontrado' }}</p>
  </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import api from '../api/axios'
import { useAuthStore } from '../stores/auth'

const route = useRoute()
const auth = useAuthStore()

const service = ref(null)
const images = ref([])
const currentImage = ref(0)
const reviews = ref([])

const loading = ref(true)
const error = ref('')
const reviewsLoading = ref(false)
const commentLoading = ref(false)
const commentError = ref('')
const commentTextarea = ref(null)

const commentForm = ref({
  rating: 5,
  comment: '',
})

const inquiry = ref({
  name: '',
  phone: '',
  email: '',
  date: '',
  message: '',
})

const fallbackImage =
  'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=800&q=60&auto=compress'

const serviceId = computed(() => route.params.id) //cogemos el id que aparece en la url
const euro = (value) => `${Number(value ?? 0).toFixed(2)} €`

const fetchService = async () => {
  loading.value = true
  error.value = ''
  try {
    const res = await api.get(`/services/${serviceId.value}`)
    service.value = res.data?.data
    images.value = service.value?.images || []
  } catch (e) {
    error.value = e.response?.data?.message || 'Error al cargar servicio'
  } finally {
    loading.value = false
  }
}

const fetchReviews = async () => {
  reviewsLoading.value = true
  try {
    const res = await api.get('/reviews', {
      params: { service_id: serviceId.value, per_page: 20 },
    })
    reviews.value = res.data?.data?.data || []
  } catch (e) {
    commentError.value = e.response?.data?.message || 'Error al cargar comentarios'
  } finally {
    reviewsLoading.value = false
  }
}

const submitComment = async () => {
  if (!auth.token) return
  commentError.value = ''
  commentLoading.value = true
  try {
    await api.post('/reviews', {
      service_id: serviceId.value,
      rating: commentForm.value.rating,
      comment: commentForm.value.comment,
    })
    commentForm.value.comment = ''
    await fetchReviews()
  } catch (e) {
    commentError.value =
      e.response?.data?.message || 'No se pudo enviar el comentario. ¿Ya comentaste este servicio?'
  } finally {
    commentLoading.value = false
  }
}

const sendInquiry = () => {
  if (!service.value?.email) return

  const subject = encodeURIComponent(`Consulta sobre ${service.value.name}`)
  const bodyLines = [
    `Nombre: ${inquiry.value.name}`,
    `Teléfono: ${inquiry.value.phone || ''}`,
    `Email: ${inquiry.value.email}`,
    `Fecha de la boda: ${inquiry.value.date || ''}`,
    '',
    inquiry.value.message || '',
  ]
  const body = encodeURIComponent(bodyLines.join('\n'))
  window.location.href = `mailto:${service.value.email}?subject=${subject}&body=${body}` //para abrir el cliente de correo
}

const nextImage = () => {
  currentImage.value = (currentImage.value + 1) % images.value.length
}

const prevImage = () => {
  currentImage.value =
    (currentImage.value - 1 + images.value.length) % images.value.length
}

onMounted(async () => {
  await fetchService()
  await fetchReviews()
})
</script>
