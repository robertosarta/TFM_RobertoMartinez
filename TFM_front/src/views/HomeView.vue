<template>
  <div class="home">
    <h1 v-if="!selectedCategoryId">Bienvenido a la plataforma de bodas</h1>

    <p v-if="loading">Cargando datos...</p>
    <p v-if="error" class="error">{{ error }}</p>

    <section class="section sticky-filter">
      <div class="filter-row">
        <h2>Categorías</h2>
        <div class="chip-list">
          <button
            v-for="cat in categories"
            :key="cat.id"
            class="chip"
            :class="{ active: selectedCategoryId === cat.id }"
            @click="toggleCategory(cat.id)"
          >
            {{ cat.name }}
          </button>
        </div>
      </div>

      <div v-if="selectedCategoryId" class="filter-row subcats-row">
        <h3>Subcategorías</h3>
        <div class="chip-scroll">
          <button
            v-for="sub in filteredSubcategories"
            :key="sub.id"
            class="chip"
            :class="{ active: selectedSubcategoryId === sub.id }"
            @click="toggleSubcategory(sub.id)"
          >
            {{ sub.name }}
          </button>
        </div>
      </div>
    </section>

    <section class="section">
      <h2 v-if="selectedCategoryName">{{ selectedCategoryName }}</h2>
      <h2 v-else>Servicios destacados</h2>

      <div v-if="!visibleServices.length && !loading">No hay servicios disponibles</div>

      <div class="services-grid">
        <RouterLink
          v-for="svc in visibleServices"
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

      <div class="services-actions" v-if="canLoadMore">
        <button @click="loadMore">Ver más</button>
      </div>
    </section>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import api from '../api/axios'

const categories = ref([])
const subcategories = ref([])
const services = ref([])
const loading = ref(true)
const error = ref('')
const fallbackImage =
  'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=800&q=60&auto=compress'

const selectedCategoryId = ref(null)
const selectedSubcategoryId = ref(null)
const visibleCount = ref(7)

const filteredSubcategories = computed(() => {
  if (!selectedCategoryId.value) return []
  const catId = Number(selectedCategoryId.value)
  return subcategories.value.filter((s) => Number(s.category_id) === catId)
})

const filteredServices = computed(() => {
  let list = services.value
  if (selectedCategoryId.value) {
    const catId = Number(selectedCategoryId.value)
    list = list.filter((s) => {
      const subCatId = Number(s.subcategory?.category?.id ?? s.subcategory?.category_id)
      return subCatId === catId
    })
  }
  if (selectedSubcategoryId.value) {
    const subId = Number(selectedSubcategoryId.value)
    list = list.filter((s) => Number(s.subcategory?.id) === subId)
  }
  return list
})

const visibleServices = computed(() => filteredServices.value.slice(0, visibleCount.value))
const canLoadMore = computed(() => filteredServices.value.length > visibleCount.value)
const selectedCategoryName = computed(() => {
  const cat = categories.value.find((c) => c.id === selectedCategoryId.value)
  return cat ? cat.name : ''
})

const fetchData = async () => {
  loading.value = true
  error.value = ''

  try {
    const [catRes, subcatRes, svcRes] = await Promise.all([
      api.get('/categories'),
      api.get('/subcategories'),
      api.get('/services', { params: { per_page: 50 } }),
    ])

    categories.value = catRes.data?.data || []
    subcategories.value = subcatRes.data?.data || []
    services.value = svcRes.data?.data?.data || []
  } catch (e) {
    error.value = e.response?.data?.message || 'Error al cargar datos'
  } finally {
    loading.value = false
  }
}

onMounted(fetchData)

const getImage = (svc) => svc.images?.[0]?.url || fallbackImage

const toggleCategory = (id) => {
  if (selectedCategoryId.value === id) {
    selectedCategoryId.value = null
    selectedSubcategoryId.value = null
  } else {
    selectedCategoryId.value = id
    selectedSubcategoryId.value = null
  }
  visibleCount.value = 7
}

const toggleSubcategory = (id) => {
  if (selectedSubcategoryId.value === id) {
    selectedSubcategoryId.value = null
  } else {
    selectedSubcategoryId.value = id
  }
  visibleCount.value = 7
}

const loadMore = () => {
  visibleCount.value += 7
}
</script>

<style scoped>
.home {
  padding: 2rem 0;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.sticky-filter {
  position: sticky;
  top: 0;
  z-index: 5;
  background: #fff;
  padding-top: 1rem;
}

.section {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.filter-row {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.chip-list {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  padding: 0;
  margin: 0;
}

.chip {
  padding: 0.4rem 0.75rem;
  background: #f1f1f1;
  border-radius: 999px;
  font-size: 0.95rem;
  border: 1px solid transparent;
  cursor: pointer;
}

.chip.active {
  background: #fff4e0;
  border-color: #f0c572;
  color: #b36b00;
}

.subcats-row {
  border-top: 1px solid #eee;
  padding-top: 0.5rem;
}

.chip-scroll {
  display: flex;
  gap: 0.5rem;
  overflow-x: auto;
  padding-bottom: 0.25rem;
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

.services-actions {
  margin-top: 1rem;
}

.services-actions button {
  padding: 0.5rem 1rem;
  border: 1px solid #ccc;
  background: white;
  cursor: pointer;
  border-radius: 4px;
}

.error {
  color: red;
}
</style>
