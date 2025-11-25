<template>
  <!-- Panel de perfil y edición de datos de usuario -->
  <div class="profile">
    <section class="panel">
      <header class="panel__header">
        <div>
          <h2>Perfil</h2>
          <p class="muted">Gestiona tus datos y credenciales</p>
        </div>
      </header>

      <div class="panel__body user-grid">
        <div class="user-block">
          <p><strong>Nombre:</strong> {{ auth.user?.name }}</p>
          <p><strong>Email:</strong> {{ auth.user?.email }}</p>
          <p><strong>Teléfono:</strong> {{ auth.user?.phone || '—' }}</p>
          <p><strong>Dirección:</strong> {{ auth.user?.address || '—' }}</p>
          <p><strong>Tipo de cuenta:</strong> {{ auth.user?.role }}</p>
        </div>

        <form class="form" @submit.prevent="updateUser">
          <h3>Actualizar datos</h3>
          <div class="form-row">
            <label>Nombre</label>
            <input v-model="userForm.name" type="text" />
          </div>
          <div class="form-row">
            <label>Email</label>
            <input v-model="userForm.email" type="email" />
          </div>
          <div class="form-row">
            <label>Teléfono</label>
            <input v-model="userForm.phone" type="text" />
          </div>
          <div class="form-row">
            <label>Dirección</label>
            <input v-model="userForm.address" type="text" />
          </div>
          <div class="form-row">
            <label>Nueva contraseña</label>
            <input v-model="userForm.password" type="password" autocomplete="new-password" />
          </div>
          <div class="form-row">
            <label>Confirmar contraseña</label>
            <input
              v-model="userForm.password_confirmation"
              type="password"
              autocomplete="new-password"
            />
          </div>
          <div class="form-actions">
            <button type="submit" :disabled="userLoading">
              {{ userLoading ? 'Guardando...' : 'Guardar cambios' }}
            </button>
            <span class="status ok" v-if="userMessage">{{ userMessage }}</span>
            <span class="status error" v-if="userError">{{ userError }}</span>
          </div>
        </form>
      </div>
    </section>

    <!-- Sección Business: listado/creación/edición de servicios -->
    <template v-if="role === 'business'">
      <section class="panel">
        <header class="panel__header">
          <div>
            <h2>Servicios</h2>
            <p class="muted">Crea, actualiza y consulta tus servicios</p>
          </div>
          <div class="actions">
            <button @click="toggleCreateService" class="ghost">
              {{ showCreateService ? 'Cerrar' : 'Crear servicio' }}
            </button>
            <button @click="fetchServices" :disabled="servicesLoading">
              {{ servicesLoading ? 'Actualizando...' : 'Refrescar' }}
            </button>
          </div>
        </header>

        <div class="panel__body">
          <form v-if="showCreateService" class="form" @submit.prevent="createService">
            <h3>Nuevo servicio</h3>
            <div class="grid-2">
              <div class="form-row">
                <label>Nombre*</label>
                <input v-model="createServiceForm.name" type="text" required />
              </div>
              <div class="form-row">
                <label>Email*</label>
                <input v-model="createServiceForm.email" type="email" required />
              </div>
              <div class="form-row">
                <label>Teléfono*</label>
                <input v-model="createServiceForm.phone" type="text" required />
              </div>
              <div class="form-row">
                <label>Precio*</label>
                <input v-model.number="createServiceForm.price" type="number" step="0.01" required />
              </div>
              <div class="form-row">
                <label>Subcategoría</label>
                <select v-model="createServiceForm.subcategory_id">
                  <option value="">(sin subcategoría)</option>
                  <option v-for="sub in subcategories" :key="sub.id" :value="sub.id">
                    {{ sub.name }}<span v-if="sub.category"> · {{ sub.category.name }}</span>
                  </option>
                </select>
              </div>
              <div class="form-row">
                <label>Ciudad</label>
                <input v-model="createServiceForm.address.city" type="text" />
              </div>
              <div class="form-row">
                <label>Calle</label>
                <input v-model="createServiceForm.address.street" type="text" />
              </div>
              <div class="form-row">
                <label>CP</label>
                <input v-model="createServiceForm.address.zip" type="text" />
              </div>
            </div>
            <div class="form-row">
              <label>Descripción</label>
              <textarea v-model="createServiceForm.description" rows="3"></textarea>
            </div>
            <div class="grid-2">
              <div class="form-row">
                <label>URL imagen (opcional)</label>
                <input v-model="createServiceImage.url" type="url" placeholder="https://..." />
              </div>
              <div class="form-row">
                <label>Archivo imagen (opcional)</label>
                <input type="file" accept="image/*" @change="onCreateFileChange" />
              </div>
            </div>
            <div class="form-actions">
              <button type="submit" :disabled="servicesLoading">
                {{ servicesLoading ? 'Creando...' : 'Crear servicio' }}
              </button>
              <span class="status error" v-if="servicesError">{{ servicesError }}</span>
            </div>
          </form>

          <div v-if="servicesError" class="alert error">{{ servicesError }}</div>
          <div v-if="servicesLoading" class="muted">Cargando servicios...</div>

          <div class="service-cards" v-if="services.length">
            <article v-for="svc in services" :key="svc.id" class="card">
              <header class="card__header">
                <div>
                  <h3>{{ svc.name }}</h3>
                  <p class="muted">
                    {{ svc.subcategory?.name || '—' }}
                    <span v-if="svc.subcategory?.category">
                      · {{ svc.subcategory.category.name }}
                    </span>
                  </p>
                </div>
                <div class="badge">Reviews: {{ svc.reviews_count || 0 }}</div>
              </header>

              <div class="card__body">
                <p class="muted">Precio: {{ svc.price }}</p>
                <p class="muted">Email: {{ svc.email }}</p>
                <p class="muted">Tel: {{ svc.phone }}</p>
                <div class="thumbs" v-if="svc.images?.length">
                  <img
                    v-for="img in svc.images"
                    :key="img.id"
                    :src="img.url"
                    :alt="img.caption || svc.name"
                  />
                </div>

                <div class="form mini-form">
                  <h4>Editar servicio</h4>
                <div class="form-row">
                  <label>Nombre</label>
                  <input v-model="serviceEdits[svc.id].name" type="text" />
                </div>
                <div class="form-row">
                  <label>Precio</label>
                  <input v-model.number="serviceEdits[svc.id].price" type="number" step="0.01" />
                </div>
                <div class="form-row">
                  <label>Subcategoría</label>
                  <select v-model="serviceEdits[svc.id].subcategory_id">
                    <option value="">(sin subcategoría)</option>
                    <option v-for="sub in subcategories" :key="sub.id" :value="sub.id">
                      {{ sub.name }}<span v-if="sub.category"> · {{ sub.category.name }}</span>
                    </option>
                  </select>
                </div>
                <div class="form-row">
                  <label>Descripción</label>
                  <textarea v-model="serviceEdits[svc.id].description" rows="2"></textarea>
                </div>
                  <div class="form-actions">
                    <button @click="updateService(svc.id)" :disabled="servicesLoading">
                      Guardar
                    </button>
                  </div>
                </div>

                <div class="form mini-form">
                  <h4>Subir imagen</h4>
                  <div class="form-row">
                    <label>URL</label>
                    <input v-model="imageUrls[svc.id]" type="url" placeholder="https://..." />
                  </div>
                  <div class="form-row">
                    <label>Archivo</label>
                    <input type="file" accept="image/*" @change="onFileChange(svc.id, $event)" />
                  </div>
                  <div class="form-actions">
                    <button @click="addServiceImage(svc.id)" :disabled="servicesLoading">
                      Añadir
                    </button>
                  </div>
                </div>

                <div class="reviews" v-if="svc.reviews?.length">
                  <h4>Últimos comentarios</h4>
                  <ul>
                    <li v-for="rev in svc.reviews" :key="rev.id">
                      <strong>{{ rev.user?.name || 'Usuario' }}</strong> · {{ rev.rating }}/5
                      <p>{{ rev.comment || 'Sin comentario' }}</p>
                    </li>
                  </ul>
                </div>
              </div>
            </article>
          </div>

          <div v-else class="muted">Aún no tienes servicios.</div>
        </div>
      </section>
    </template>

    <!-- Sección User: Mi boda (servicios agrupados + edición pivot) -->
    <template v-else>
      <section class="panel">
        <header class="panel__header">
          <div>
            <h2>Mi boda</h2>
            <p class="muted">Organiza tus servicios por categoría</p>
          </div>
          <div class="actions">
            <button @click="reloadWedding" :disabled="weddingLoading">
              {{ weddingLoading ? 'Actualizando...' : 'Refrescar' }}
            </button>
          </div>
        </header>

        <div class="panel__body">
          <div v-if="weddingError" class="alert error">{{ weddingError }}</div>

          <div v-if="weddingLoading" class="muted">Cargando boda...</div>

          <div v-else-if="!wedding">
            <button @click="createWedding" :disabled="weddingLoading">Crear mi boda</button>
          </div>

          <div v-else>
            <p class="muted">
              {{ wedding.name }} · Estado: {{ wedding.status }} · Invitados:
              {{ wedding.guest_count || '—' }}
            </p>

            <div v-if="groupedServicesKeys.length === 0" class="muted">
              Aún no hay servicios añadidos a la boda.
            </div>

            <div v-else class="wedding-groups">
              <div v-for="cat in groupedServicesKeys" :key="cat" class="wedding-group">
                <h3>{{ cat }}</h3>
                <div class="wedding-service" v-for="svc in groupedServices[cat]" :key="svc.id">
                  <div class="wedding-service__info">
                    <p class="title">{{ svc.name }}</p>
                    <p class="muted">
                      {{ svc.subcategory?.name || '—' }}
                    </p>
                  </div>
                  <div class="wedding-service__thumb">
                    <img :src="svc.images?.[0]?.url || fallbackImage" :alt="svc.name" />
                  </div>
                <div class="wedding-service__controls">
                  <label>
                    Precio pactado
                    <input
                      v-model.number="weddingUpdates[svc.id].price"
                        type="number"
                        step="0.01"
                      />
                    </label>
                    <label>
                      Cantidad
                      <input
                        v-model.number="weddingUpdates[svc.id].quantity"
                        type="number"
                        min="1"
                      />
                    </label>
                  <label>
                    Estado
                    <select
                      v-model="weddingUpdates[svc.id].status"
                      :class="statusSelectClass(weddingUpdates[svc.id].status)"
                    >
                      <option v-for="opt in serviceStatusOptions" :key="opt" :value="opt">
                        {{ opt }}
                      </option>
                    </select>
                  </label>
                  <button @click="saveWeddingService(svc.id)" :disabled="weddingLoading">
                    Guardar
                  </button>
                  <button class="danger" @click="detachWeddingService(svc.id)" :disabled="weddingLoading">
                    Quitar
                  </button>
                </div>
                <div class="pivot-meta">
                  <span>Actual: {{ svc.pivot?.status || '—' }}</span>
                  <span>Precio: {{ svc.pivot?.price ?? 0 }}</span>
                  <span>Cant: {{ svc.pivot?.quantity ?? 1 }}</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="total">
              Total estimado: {{ weddingTotal.toFixed(2) }}
            </div>
          </div>
        </div>
      </section>
    </template>
  </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import api from '../api/axios'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const role = computed(() => auth.user?.role)
const fallbackImage =
  'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=800&q=60&auto=compress'

// -----------------------------
// Bloque: Datos de usuario y update
// -----------------------------
const userForm = reactive({
  name: auth.user?.name || '',
  email: auth.user?.email || '',
  phone: auth.user?.phone || '',
  address: auth.user?.address || '',
  password: '',
  password_confirmation: '',
})
const userLoading = ref(false)
const userMessage = ref('')
const userError = ref('')

const updateUser = async () => {
  userLoading.value = true
  userMessage.value = ''
  userError.value = ''

  const payload = cleanPayload(userForm)

  if (!Object.keys(payload).length) {
    userError.value = 'No hay cambios que guardar'
    userLoading.value = false
    return
  }

  try {
    const res = await api.put(`/users/${auth.user.id}`, payload)
    auth.user = res.data?.data
    userMessage.value = 'Datos guardados'
    userForm.password = ''
    userForm.password_confirmation = ''
  } catch (e) {
    userError.value = e.response?.data?.message || 'Error al guardar'
  } finally {
    userLoading.value = false
  }
}

// Business: servicios
// -----------------------------
// Bloque: Servicios (perfil business/admin)
// -----------------------------
const services = ref([])
const servicesLoading = ref(false)
const servicesError = ref('')
const showCreateService = ref(false)
const subcategories = ref([])
const createServiceForm = reactive({
  name: '',
  email: auth.user?.email || '',
  phone: auth.user?.phone || '',
  price: '',
  description: '',
  subcategory_id: '',
  address: {
    street: '',
    city: '',
    zip: '',
  },
})
const createServiceImage = reactive({
  url: '',
  file: null,
})
const serviceEdits = reactive({})
const imageUrls = reactive({})
const imageFiles = reactive({})

const toggleCreateService = () => {
  showCreateService.value = !showCreateService.value
}

const fetchSubcategories = async () => {
  try {
    const res = await api.get('/subcategories')
    subcategories.value = res.data?.data || []
  } catch {
    subcategories.value = []
  }
}

const fetchServices = async () => {
  if (role.value !== 'business' && role.value !== 'admin') return
  servicesLoading.value = true
  servicesError.value = ''
  try {
    const res = await api.get('/my/services', { params: { per_page: 50, include_reviews: true } })
    services.value = res.data?.data?.data || []
    // inicializar buffers de edición
    services.value.forEach((svc) => {
      serviceEdits[svc.id] = {
        name: svc.name,
        price: svc.price,
        description: svc.description,
        subcategory_id: svc.subcategory_id,
      }
      imageUrls[svc.id] = ''
      imageFiles[svc.id] = null
    })
  } catch (e) {
    servicesError.value = e.response?.data?.message || 'Error al cargar servicios'
  } finally {
    servicesLoading.value = false
  }
}

const createService = async () => {
  servicesError.value = ''
  servicesLoading.value = true
  try {
    const payload = cleanPayload(createServiceForm)
    if (!payload.name || !payload.email || !payload.phone || !payload.price) {
      servicesError.value = 'Rellena nombre, email, teléfono y precio'
      servicesLoading.value = false
      return
    }
    const res = await api.post('/services', payload)
    const newServiceId = res.data?.data?.id

    if (newServiceId && (createServiceImage.url || createServiceImage.file)) {
      const form = new FormData()
      if (createServiceImage.file) form.append('image', createServiceImage.file)
      if (createServiceImage.url) form.append('url', createServiceImage.url)
      await api.post(`/services/${newServiceId}/images`, form)
    }

    Object.assign(createServiceForm, {
      name: '',
      email: auth.user?.email || '',
      phone: auth.user?.phone || '',
      price: '',
      description: '',
      subcategory_id: '',
      address: { street: '', city: '', zip: '' },
    })
    Object.assign(createServiceImage, { url: '', file: null })
    showCreateService.value = false
    await fetchServices()
  } catch (e) {
    servicesError.value = e.response?.data?.message || 'Error al crear servicio'
  } finally {
    servicesLoading.value = false
  }
}


//Esto hace que se actualice el servicio, primero limpia el payload y si no hay nada que actualizar no hace nada, 
//luego hace la petición PUT y vuelve a cargar los servicios, 
//lanzando errores si los hay.
const updateService = async (id) => {
  servicesError.value = ''
  const payload = cleanPayload(serviceEdits[id] || {})
  if (!Object.keys(payload).length) return
  servicesLoading.value = true
  try {
    await api.put(`/services/${id}`, payload)
    await fetchServices()
  } catch (e) {
    servicesError.value = e.response?.data?.message || 'Error al actualizar servicio'
  } finally {
    servicesLoading.value = false
  }
}

const addServiceImage = async (id) => {
  servicesError.value = ''
  const url = imageUrls[id]
  const file = imageFiles[id]
  if (!url && !file) return
  servicesLoading.value = true
  try {
    const form = new FormData()
    if (file) form.append('image', file)
    if (url) form.append('url', url)
    await api.post(`/services/${id}/images`, form)
    imageUrls[id] = ''
    imageFiles[id] = null
    await fetchServices()
  } catch (e) {
    servicesError.value = e.response?.data?.message || 'Error al subir imagen'
  } finally {
    servicesLoading.value = false
  }
}

const onFileChange = (id, event) => {
  const file = event.target.files?.[0]
  imageFiles[id] = file || null
}

const onCreateFileChange = (event) => {
  const file = event.target.files?.[0]
  createServiceImage.file = file || null
}

// User: Mi boda
const wedding = ref(null)
const weddingLoading = ref(false)
const weddingError = ref('')
const weddingUpdates = reactive({})
const serviceStatusOptions = ['consultado', 'confirmado', 'cancelado']

const loadWedding = async () => {
  weddingLoading.value = true
  weddingError.value = ''
  try {
    const res = await api.get('/weddings', { params: { per_page: 1 } })
    const list = res.data?.data?.data || []
    if (list.length) {
      wedding.value = list[0]
      await loadWeddingServices()
    } else {
      wedding.value = null
    }
  } catch (e) {
    weddingError.value = e.response?.data?.message || 'Error al cargar boda'
  } finally {
    weddingLoading.value = false
  }
}

const loadWeddingServices = async () => {
  if (!wedding.value?.id) return
  weddingError.value = ''
  try {
    const res = await api.get(`/weddings/${wedding.value.id}`)
    wedding.value = res.data?.data
    (wedding.value.services || []).forEach((svc) => {
      weddingUpdates[svc.id] = {
        price: svc.pivot?.price,
        quantity: svc.pivot?.quantity ?? 1,
        status: svc.pivot?.status || 'consultado',
      }
    })
  } catch (e) {
    // Intento de fallback: cargar solo los servicios de la boda
    if (e.response?.status === 404) {
      wedding.value = null
      weddingError.value = 'No se ha encontrado tu boda. Crea una nueva.'
      return
    }
    if (e.response?.status === 403) {
      weddingError.value = 'No tienes permisos para ver esta boda.'
      return
    }

    try {
      const servicesRes = await api.get(`/weddings/${wedding.value.id}/services`)
      const services = servicesRes.data?.data || []
      wedding.value = wedding.value || {}
      wedding.value.services = services
      services.forEach((svc) => {
        weddingUpdates[svc.id] = {
          price: svc.pivot?.price,
          quantity: svc.pivot?.quantity ?? 1,
          status: svc.pivot?.status || 'consultado',
        }
      })
      // Fallback exitoso: no mostrar mensaje de error
      weddingError.value = ''
    } catch (fallbackErr) {
      weddingError.value =
        fallbackErr.response?.data?.message ||
        e.response?.data?.message ||
        'Error al cargar servicios de la boda'
    }
  }
}

const createWedding = async () => {
  weddingLoading.value = true
  weddingError.value = ''
  try {
    const res = await api.post('/weddings', { name: 'Mi boda', status: 'gestionando' })
    wedding.value = res.data?.data
    await loadWeddingServices()
  } catch (e) {
    weddingError.value = e.response?.data?.message || 'Error al crear boda'
  } finally {
    weddingLoading.value = false
  }
}

const saveWeddingService = async (serviceId) => {
  if (!wedding.value?.id) return
  const payload = cleanPayload(weddingUpdates[serviceId] || {})
  if (!Object.keys(payload).length) return
  weddingLoading.value = true
  try {
    await api.put(`/weddings/${wedding.value.id}/services/${serviceId}`, payload)
    await loadWeddingServices()
  } catch (e) {
    weddingError.value = e.response?.data?.message || 'Error al guardar servicio'
  } finally {
    weddingLoading.value = false
  }
}

const detachWeddingService = async (serviceId) => {
  if (!wedding.value?.id) return
  weddingLoading.value = true
  weddingError.value = ''
  try {
    await api.delete(`/weddings/${wedding.value.id}/services/${serviceId}`)
    await loadWeddingServices()
  } catch (e) {
    weddingError.value = e.response?.data?.message || 'Error al quitar servicio'
  } finally {
    weddingLoading.value = false
  }
}

const reloadWedding = async () => {
  await loadWedding()
}

const groupedServices = computed(() => {
  const groups = {}
  if (!wedding.value?.services) return groups
  wedding.value.services.forEach((svc) => {
    const cat = svc.subcategory?.category?.name || 'Sin categoría'
    if (!groups[cat]) groups[cat] = []
    groups[cat].push(svc)
  })
  return groups
})

const groupedServicesKeys = computed(() => Object.keys(groupedServices.value))

const weddingTotal = computed(() => {
  if (!wedding.value?.services) return 0
  return wedding.value.services.reduce((acc, svc) => {
    const status = svc.pivot?.status
    if (status !== 'confirmado') return acc
    const price = Number(svc.pivot?.price ?? 0)
    const qty = Number(svc.pivot?.quantity ?? 1)
    return acc + price * qty
  }, 0)
})

// -----------------------------
// Utilidades
// -----------------------------
const cleanPayload = (obj) =>
  Object.fromEntries(
    Object.entries(obj).filter(
      ([, v]) => v !== '' && v !== null && v !== undefined && !(typeof v === 'number' && Number.isNaN(v)),
    ),
  )

const statusSelectClass = (status) => {
  if (status === 'confirmado') return 'status-select confirmed'
  if (status === 'cancelado') return 'status-select cancelled'
  return 'status-select consulted'
}

// -----------------------------
// Ciclo de vida
// -----------------------------
onMounted(async () => {
  if (role.value === 'business' || role.value === 'admin') {
    await fetchSubcategories()
    await fetchServices()
  } else {
    await loadWedding()
  }
})
</script>

<style scoped>
.profile {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  padding: 1rem 0;
}

.panel {
  border: 1px solid #e2e2e2;
  border-radius: 8px;
  background: #fff;
  overflow: hidden;
}

.panel__header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.25rem;
  border-bottom: 1px solid #eee;
  gap: 1rem;
}

.panel__body {
  padding: 1.25rem;
}

.muted {
  color: #666;
  margin: 0;
}

.user-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.user-block {
  display: grid;
  gap: 0.25rem;
}

.form {
  display: grid;
  gap: 0.75rem;
}

.form-row {
  display: grid;
  gap: 0.25rem;
}

.form-row input,
.form-row textarea,
.form-row select {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 6px;
}

.form-actions {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.form-actions button {
  padding: 0.5rem 1rem;
}

.actions {
  display: flex;
  gap: 0.5rem;
}

button {
  border: 1px solid #ccc;
  background: #fff;
  cursor: pointer;
  border-radius: 6px;
  padding: 0.4rem 0.8rem;
}

button.ghost {
  background: transparent;
}

.status.ok {
  color: #0a7f3f;
}

.status.error,
.alert.error {
  color: #b00020;
}

.grid-2 {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 0.75rem;
}

.service-cards {
  display: grid;
  gap: 1rem;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
}

.card {
  border: 1px solid #eee;
  border-radius: 8px;
  padding: 1rem;
  display: grid;
  gap: 0.75rem;
  background: rgba(0, 0, 0, 0.03);
}

.card__header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 0.5rem;
}

.card__body {
  display: grid;
  gap: 0.75rem;
}

.badge {
  background: #f4f4f4;
  padding: 0.35rem 0.6rem;
  border-radius: 999px;
  font-size: 0.85rem;
}

.thumbs {
  display: flex;
  gap: 0.5rem;
}

.thumbs img {
  width: 64px;
  height: 48px;
  object-fit: cover;
  border-radius: 6px;
}

.mini-form {
  border: 1px dashed #e0e0e0;
  padding: 0.75rem;
  border-radius: 6px;
}

.reviews ul {
  padding-left: 1rem;
}

.wedding-groups {
  display: grid;
  gap: 1rem;
}

.wedding-group h3 {
  margin-bottom: 0.4rem;
}

.wedding-service {
  display: grid;
  grid-template-columns: 2fr auto 3fr;
  gap: 0.75rem;
  align-items: center;
  border: 1px solid rgba(0, 0, 0, 0.05);
  padding: 0.75rem;
  border-radius: 8px;
  background: rgba(0, 0, 0, 0.03);
}

.wedding-service__info .title {
  margin: 0;
  font-weight: 600;
}

.wedding-service__thumb img {
  width: 80px;
  height: 60px;
  object-fit: cover;
  border-radius: 6px;
}

.wedding-service__controls {
  display: grid;
  grid-template-columns: repeat(3, minmax(160px, 1fr)) auto auto;
  gap: 1.4rem;
  align-items: end;
  margin-top: 0.35rem;
}

.wedding-service__controls input,
.wedding-service__controls select {
  width: 100%;
  padding: 0.4rem;
  border-radius: 6px;
  border: 0.11rem solid #686868;
  box-shadow: none;
}

.status-select {
  transition: background 0.2s ease, color 0.2s ease;
}

.status-select.consulted {
  background: #fff9e6;
  border-color: #f0c572;
  color: #8a5a00;
}

.status-select.confirmed {
  background: #e9f8ed;
  border-color: #66b98a;
  color: #166534;
}

.status-select.cancelled {
  background: #fdeaea;
  border-color: #e08a8a;
  color: #a02020;
}

.pivot-meta {
  grid-column: 1 / -1;
  display: flex;
  gap: 1rem;
  color: #666;
  font-size: 0.9rem;
}

.total {
  margin-top: 1rem;
  font-weight: 700;
}

.alert {
  padding: 0.6rem 0.8rem;
  border-radius: 6px;
  background: #fff3cd;
  border: 1px solid #ffeeba;
}

@media (max-width: 900px) {
  .user-grid {
    grid-template-columns: 1fr;
  }
  .wedding-service {
    grid-template-columns: 1fr;
  }
}
</style>
