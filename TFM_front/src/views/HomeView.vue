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
      <h2>Servicios (primeras 6 entradas)</h2>
      <div v-if="!services.length && !loading">No hay servicios disponibles</div>
      <div class="services-grid">
        <article v-for="svc in services" :key="svc.id" class="card">
          <h3>{{ svc.name }}</h3>
          <p class="desc">{{ svc.description }}</p>
          <p class="meta">
            <span>Precio: {{ svc.price }}</span>
          </p>
        </article>
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

const fetchData = async () => {
  loading.value = true
  error.value = ''

  try {
    const [catRes, svcRes] = await Promise.all([
      api.get('/categories'),
      api.get('/services', { params: { per_page: 6 } }),
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
