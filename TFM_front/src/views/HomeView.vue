<template>
  <div class="home">
    <h1 v-if="!selectedCategoryId">Bienvenido a la plataforma de bodas</h1>

    <p v-if="loading">Cargando datos...</p>
    <p v-if="error" class="error">{{ error }}</p>
    <p v-if="actionMessage" class="success">{{ actionMessage }}</p>
    <p v-if="actionError" class="error">{{ actionError }}</p>

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
        <div v-for="svc in visibleServices" :key="svc.id" class="card">
          <RouterLink :to="`/services/${svc.id}`" class="card__link">
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
          <button
            class="add-btn"
            :disabled="attachLoading === svc.id"
            @click="attachToWedding(svc.id, svc.price)"
          >
            {{ attachLoading === svc.id ? 'Añadiendo...' : 'Añadir a mi boda' }}
          </button>
        </div>
      </div>

      <div class="services-actions" v-if="canLoadMore">
        <button @click="loadMore">Ver más</button>
      </div>
    </section>
  </div>
</template>

<script setup>
import { computed, onMounted, onBeforeUnmount, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../api/axios'
import { useAuthStore } from '../stores/auth'

const route = useRoute()
const router = useRouter()
const auth = useAuthStore()
const categories = ref([])
const subcategories = ref([])
const services = ref([])
const loading = ref(true)
const error = ref('')
const actionMessage = ref('')
const actionError = ref('')
const attachLoading = ref(null)
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
    const allowedSubIds = new Set(
      subcategories.value
        .filter((s) => Number(s.category_id) === catId)
        .map((s) => Number(s.id)),
    )

    list = list.filter((s) => {
      const subId = Number(s.subcategory?.id ?? s.subcategory_id)
      return allowedSubIds.has(subId)
    }) // para reconocer los datos con o sin relaciones cargadas
  }
  if (selectedSubcategoryId.value) {
    const subId = Number(selectedSubcategoryId.value)
    list = list.filter((s) => Number(s.subcategory?.id ?? s.subcategory_id) === subId)
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
  actionMessage.value = ''
  actionError.value = ''

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

const handleResetFilters = () => {
  selectedCategoryId.value = null
  selectedSubcategoryId.value = null
  visibleCount.value = 7
}

watch(
  () => route.path,
  (path) => {
    if (path === '/') {
      handleResetFilters()
    }
  },
  { immediate: true },
)

onMounted(() => {
  window.addEventListener('reset-home-filters', handleResetFilters)
})

onBeforeUnmount(() => {
  window.removeEventListener('reset-home-filters', handleResetFilters)
})

// Mostrar botón para usuarios logueados con rol user y también para no logueados (redirige a login).
const ensureWedding = async () => {
  // intenta obtener la boda existente o crea una nueva
  const res = await api.get('/weddings', { params: { per_page: 1 } })
  const list = res.data?.data?.data || []
  if (list.length) return list[0]
  const created = await api.post('/weddings', { name: 'Mi boda', status: 'gestionando' })
  return created.data?.data
}

const attachToWedding = async (serviceId, price) => {
  actionMessage.value = ''
  actionError.value = ''

  if (!auth.token) {
    router.push('/login')
    return
  }

  if (auth.user?.role !== 'user') {
    actionError.value = 'Debes iniciar sesión como usuario para crear tu boda.'
    return
  }

  attachLoading.value = serviceId

  try {
    const wedding = await ensureWedding()
    await api.post(`/weddings/${wedding.id}/services`, {
      service_id: serviceId,
      price,
      quantity: 1,
      status: 'consultado',
    })
    actionMessage.value = 'Servicio añadido a tu boda'
  } catch (e) {
    if (e.response?.status === 401) {
      await auth.logout()
      router.push('/login')
      return
    }
    actionError.value = e.response?.data?.message || 'No se pudo añadir el servicio'
  } finally {
    attachLoading.value = null
  }
}
</script>
