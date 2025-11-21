<template>
  <div class="home">
    <h1>Bienvenido a la plataforma de bodas</h1>

    <p v-if="loading">Cargando datos...</p>
    <p v-if="error" class="error">{{ error }}</p>

    <section class="section">
      <h2>Categorías</h2>
      <ul class="chip-list">
        <li v-for="cat in categories" :key="cat.id" class="chip">{{ cat.name }}</li>
      </ul>
    </section>

    <section class="section">
      <h2>Servicios</h2>
      <div v-if="!services.length && !loading">No hay servicios disponibles</div>
      <div class="services-grid">
        <RouterLink
          v-for="svc in services"
          :key="svc.id"
          class="card"
          :to="`/services/${svc.id}`"
        >
          <img
            class="card__image"
            :src="getImage(svc)"
            :alt="svc.name"
            loading="lazy"
          />
          <h3>{{ svc.name }}</h3>
          <p class="desc">{{ svc.description }}</p>
          <p class="meta">
            <span>Precio: {{ svc.price }}</span>
          </p>
        </RouterLink>
      </div>
    </section>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import api from '../api/axios'

const categories = ref([])
const services = ref([])
const loading = ref(true)
const error = ref('')
const fallbackImage =
  'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=800&q=60&auto=compress'

const fetchData = async () => {
  loading.value = true
  error.value = ''

  try {
    const [catRes, svcRes] = await Promise.all([
      api.get('/categories'),
      api.get('/services', { params: { per_page: 7 } }),
    ])

    categories.value = catRes.data?.data || []
    services.value = svcRes.data?.data?.data || []
  } catch (e) {
    error.value = e.response?.data?.message || 'Error al cargar datos'
  } finally {
    loading.value = false
  }
}

onMounted(fetchData)

const getImage = (svc) => svc.images?.[0]?.url || fallbackImage
</script>

<style scoped>
.home {
  padding: 2rem 0;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.section {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.chip-list {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  padding: 0;
  margin: 0;
  list-style: none;
}

.chip {
  padding: 0.4rem 0.75rem;
  background: #f1f1f1;
  border-radius: 999px;
  font-size: 0.95rem;
}

.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 1rem;
}

.card {
  border: 1px solid #e2e2e2;
  border-radius: 8px;
  padding: 1rem;
  background: #fff;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.card__image {
  width: 100%;
  height: 140px;
  object-fit: cover;
  border-radius: 6px;
}

.desc {
  color: #555;
  min-height: 48px;
}

.meta {
  font-size: 0.9rem;
  color: #333;
}

.error {
  color: red;
}
</style>
