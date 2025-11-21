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
            <button @click="prevImage" aria-label="Anterior">&#8592;</button>
            <span>{{ currentImage + 1 }} / {{ images.length }}</span>
            <button @click="nextImage" aria-label="Siguiente">&#8594;</button>
          </div>
        </div>
        <div v-else class="carousel__placeholder">
          <p>Sin imágenes</p>
        </div>
      </div>

      <aside class="service__info">
        <p class="service__price">Precio: {{ service.price }}</p>
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
              <input v-model="inquiry.name" type="text" required />
            </label>
            <label>
              Teléfono
              <input v-model="inquiry.phone" type="tel" />
            </label>
            <label>
              Email
              <input v-model="inquiry.email" type="email" required />  
              <!-- Quizas redundante. Por si escribe desde otro correo distinto a donde quiere recibir la info -->
            </label>
            <label>
              Fecha de la boda
              <input v-model="inquiry.date" type="date" />
            </label>
            <label>
              Mensaje
              <textarea v-model="inquiry.message" rows="3" placeholder="Tus dudas..."></textarea>
            </label>
            <button type="submit">Enviar solicitud</button>
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
        ></textarea>
        <div class="comment-actions">
          <label class="rating-select" aria-label="Puntuación">
            <span aria-hidden="true">⭐</span>
            <select v-model.number="commentForm.rating" :disabled="!auth.token || commentLoading">
              <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
            </select>
          </label>
          <button
            type="button"
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

  <div v-else class="loading">
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

<style scoped>
.service {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  padding: 1rem 0;
}

.service__title {
  margin: 0;
}

.service__layout {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 1rem;
}

@media (max-width: 900px) {
  .service__layout {
    grid-template-columns: 1fr;
  }
}

.service__visual {
  border: 1px solid #e2e2e2;
  border-radius: 8px;
  overflow: hidden;
}

.service__carousel {
  position: relative;
  background: linear-gradient(120deg, #f9f9f9 0%, #f3f3f3 100%);
  display: flex;
  align-items: center;
  justify-content: center;
}

.carousel__image {
  width: 100%;
  height: auto;
  max-height: 600px;
  object-fit: contain;
  display: block;
}

.carousel__controls {
  position: absolute;
  bottom: 12px;
  left: 0;
  right: 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 1rem;
  color: #fff;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
}

.carousel__controls button {
  background: rgba(0, 0, 0, 0.4);
  color: #fff;
  border: none;
  border-radius: 4px;
  padding: 0.35rem 0.5rem;
  cursor: pointer;
}

.carousel__placeholder {
  height: 420px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f4f4f4;
}

.service__info h3 {
  margin-top: 0;
}

.service__price {
  font-weight: 600;
  margin: 0.25rem 0;
}

.service__meta {
  color: #555;
  margin: 0.25rem 0 0.5rem 0;
}

.service__desc {
  margin: 0.25rem 0 0.75rem 0;
  color: #444;
}

.service__contact p {
  margin: 0.1rem 0;
  color: #444;
}

.info-form {
  margin-top: 1rem;
  padding-top: 0.5rem;
  border-top: 1px solid #eee;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.info-form form {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.info-form input,
.info-form textarea {
  width: 100%;
  padding: 0.5rem;
}

.info-form button {
  width: fit-content;
  padding: 0.5rem 1rem;
  cursor: pointer;
}

.comments {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.comment-box {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.comment-actions {
  display: flex;
  gap: 0.75rem;
  align-items: center;
  flex-wrap: wrap;
}

.rating-select {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  font-weight: 600;
  color: #e3a10a;
}

.rating-select select {
  padding: 0.35rem 0.5rem;
}

.comment-box textarea {
  width: 100%;
  padding: 0.5rem;
  resize: none;
}

.comment-box button {
  width: fit-content;
  padding: 0.5rem 1rem;
  cursor: pointer;
}

.comment-list {
  display: grid;
  gap: 0.75rem;
}

.comment-item {
  border: 1px solid #e2e2e2;
  border-radius: 8px;
  padding: 0.75rem;
  background: #fff;
}

.comment-item__header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.comment-item__rating {
  font-weight: 600;
  color: #e3a10a;
}

.comment-item__text {
  margin: 0.35rem 0 0 0;
}

.hint {
  color: #666;
  font-size: 0.9rem;
}

.error {
  color: red;
}
</style>
