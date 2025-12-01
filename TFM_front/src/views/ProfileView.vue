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
            <input v-model="userForm.name" type="text" class="form__input" />
          </div>
          <div class="form-row">
            <label>Email</label>
            <input v-model="userForm.email" type="email" class="form__input" />
          </div>
          <div class="form-row">
            <label>Teléfono</label>
            <input v-model="userForm.phone" type="text" class="form__input" />
          </div>
          <div class="form-row">
            <label>Dirección</label>
            <input v-model="userForm.address" type="text" class="form__input" />
          </div>
          <div class="form-row">
            <label>Nueva contraseña</label>
            <input
              v-model="userForm.password"
              type="password"
              autocomplete="new-password"
              class="form__input"
            />
          </div>
          <div class="form-row">
            <label>Confirmar contraseña</label>
            <input
              v-model="userForm.password_confirmation"
              type="password"
              autocomplete="new-password"
              class="form__input"
            />
          </div>
          <div class="form-actions">
            <button class="btn btn--primary" type="submit" :disabled="userLoading">
              {{ userLoading ? 'Guardando...' : 'Guardar cambios' }}
            </button>
            <span class="status ok" v-if="userMessage">{{ userMessage }}</span>
            <span class="status error" v-if="userError">{{ userError }}</span>
          </div>
        </form>
      </div>
    </section>

    <!-- Servicios (business/admin): listado/creación/edición de servicios -->
    <template v-if="role === 'business' || role === 'admin'">
      <section class="panel">
        <header class="panel__header">
          <div>
            <h2>Servicios</h2>
            <p class="muted">Crea, actualiza y consulta tus servicios</p>
          </div>
          <div class="actions">
            <button @click="toggleCreateService" class="btn btn--ghost">
              {{ showCreateService ? 'Cerrar' : 'Crear servicio' }}
            </button>
            <button class="btn btn--ghost btn--small" @click="fetchServices" :disabled="servicesLoading">
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
                <input v-model="createServiceForm.name" type="text" required class="form__input" />
              </div>
              <div class="form-row">
                <label>Email*</label>
                <input v-model="createServiceForm.email" type="email" required class="form__input" />
              </div>
              <div class="form-row">
                <label>Teléfono*</label>
                <input v-model="createServiceForm.phone" type="text" required class="form__input" />
              </div>
              <div class="form-row">
                <label>Precio*</label>
                <div class="input-prefix">
                  <span class="input-prefix__symbol">€</span>
                  <input
                    v-model.number="createServiceForm.price"
                    type="number"
                    step="0.01"
                    required
                    class="form__input"
                  />
                </div>
              </div>
              <div class="form-row">
                <label>Subcategoría</label>
                <select v-model="createServiceForm.subcategory_id" class="form__select">
                  <option value="">(sin subcategoría)</option>
                  <option v-for="sub in subcategories" :key="sub.id" :value="sub.id">
                    {{ sub.name }}<span v-if="sub.category"> · {{ sub.category.name }}</span>
                  </option>
                </select>
              </div>
              <div class="form-row">
                <label>Ciudad</label>
                <input v-model="createServiceForm.address.city" type="text" class="form__input" />
              </div>
              <div class="form-row">
                <label>Calle</label>
                <input v-model="createServiceForm.address.street" type="text" class="form__input" />
              </div>
              <div class="form-row">
                <label>CP</label>
                <input v-model="createServiceForm.address.zip" type="text" class="form__input" />
              </div>
            </div>
            <div class="form-row">
              <label>Descripción</label>
              <textarea v-model="createServiceForm.description" rows="3" class="form__textarea"></textarea>
            </div>
            <div class="grid-2">
              <div class="form-row">
                <label>URL imagen (opcional)</label>
                <input
                  v-model="createServiceImage.url"
                  type="url"
                  placeholder="https://..."
                  class="form__input"
                />
              </div>
              <div class="form-row">
                <label>Archivo imagen (opcional)</label>
                <input type="file" accept="image/*" @change="onCreateFileChange" class="form__input" />
              </div>
            </div>
            <div class="form-actions">
              <button class="btn btn--primary" type="submit" :disabled="servicesLoading">
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
                <p class="muted">Precios desde: {{ euro(svc.price) }}</p>
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
                  <input v-model="serviceEdits[svc.id].name" type="text" class="form__input" />
                </div>
                <div class="form-row">
                  <label>Email</label>
                  <input v-model="serviceEdits[svc.id].email" type="email" class="form__input" />
                </div>
                <div class="form-row">
                  <label>Teléfono</label>
                  <input v-model="serviceEdits[svc.id].phone" type="text" class="form__input" />
                </div>
                <div class="form-row">
                  <label>Precios desde</label>
                  <div class="input-prefix">
                    <span class="input-prefix__symbol">€</span>
                    <input
                      v-model.number="serviceEdits[svc.id].price"
                      type="number"
                      step="0.01"
                      class="form__input"
                    />
                  </div>
                </div>
                <div class="form-row">
                  <label>Subcategoría</label>
                  <select v-model="serviceEdits[svc.id].subcategory_id" class="form__select">
                    <option value="">(sin subcategoría)</option>
                    <option v-for="sub in subcategories" :key="sub.id" :value="sub.id">
                      {{ sub.name }}<span v-if="sub.category"> · {{ sub.category.name }}</span>
                    </option>
                  </select>
                </div>
                <div class="form-row">
                  <label>Descripción</label>
                  <textarea v-model="serviceEdits[svc.id].description" rows="2" class="form__textarea"></textarea>
                </div>
                  <div class="form-actions">
                    <button
                      class="btn btn--primary btn--small"
                      @click="updateService(svc.id)"
                      :disabled="servicesLoading"
                    >
                      Guardar
                    </button>
                    <button
                      class="btn btn--danger btn--small"
                      @click="deleteService(svc.id)"
                      :disabled="servicesLoading"
                    >
                      Eliminar servicio
                    </button>
                  </div>
                </div>

                <div class="form mini-form">
                  <h4>Subir imagen</h4>
                  <div class="form-row">
                    <label>URL</label>
                    <input
                      v-model="imageUrls[svc.id]"
                      type="url"
                      placeholder="https://..."
                      class="form__input"
                    />
                  </div>
                  <div class="form-row">
                    <label>Archivo</label>
                    <input
                      type="file"
                      accept="image/*"
                      @change="onFileChange(svc.id, $event)"
                      class="form__input"
                    />
                  </div>
                <div class="form-actions">
                    <button class="btn btn--ghost btn--small" @click="addServiceImage(svc.id)" :disabled="servicesLoading">
                      Subir imagen
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

    <!-- Sección User/Admin: Mi boda (servicios agrupados + edición pivot) -->
    <template v-if="role !== 'business'">
      <section class="panel">
        <header class="panel__header">
          <div>
            <h2>Mi boda</h2>
            <p class="muted">Organiza tus servicios por categoría</p>
          </div>
          <div class="actions">
            <button class="btn btn--ghost btn--small" @click="reloadWedding" :disabled="weddingLoading">
              {{ weddingLoading ? 'Actualizando...' : 'Refrescar' }}
            </button>
          </div>
        </header>

        <div class="panel__body">
          <div v-if="weddingError" class="alert error">{{ weddingError }}</div>

          <div v-if="weddingLoading" class="muted">Cargando boda...</div>

          <div v-else-if="!wedding">
            <div class="form mini-form">
              <h4>Crear mi boda</h4>
              <div class="form-row">
                <label>Fecha de la boda</label>
                <input v-model="weddingEdit.wedding_date" type="date" class="form__input" />
              </div>
              <div class="form-row">
                <label>Estado</label>
                <select v-model="weddingEdit.status" class="form__select">
                  <option value="">(sin estado)</option>
                  <option v-for="opt in weddingStatusOptions" :key="opt" :value="opt">
                    {{ opt }}
                  </option>
                </select>
              </div>
              <div class="form-row">
                <label>Número de invitados</label>
                <input v-model.number="weddingEdit.guest_count" type="number" min="0" class="form__input" />
              </div>
              <div class="form-actions">
                <button class="btn btn--primary btn--small" @click="createWedding" :disabled="weddingLoading">
                  {{ weddingLoading ? 'Creando...' : 'Crear mi boda' }}
                </button>
              </div>
            </div>
          </div>

          <div v-else>
            <p class="muted">
              {{ wedding.name }} · Estado: {{ wedding.status }} · Invitados:
              {{ wedding.guest_count || '—' }}
              <span v-if="wedding.wedding_date">· Fecha: {{ formatDate(wedding.wedding_date) }}</span>
            </p>

            <div class="wedding-meta">
              <div class="form mini-form">
                <h4>Datos de la boda</h4>
                <div class="form-row">
                  <label>Estado</label>
                  <select v-model="weddingEdit.status" class="form__select">
                    <option value="">(sin estado)</option>
                    <option v-for="opt in weddingStatusOptions" :key="opt" :value="opt">
                      {{ opt }}
                    </option>
                    <option
                      v-if="weddingEdit.status && !weddingStatusOptions.includes(weddingEdit.status)"
                      :value="weddingEdit.status"
                    >
                      {{ weddingEdit.status }} (actual)
                    </option>
                  </select>
                </div>
                <div class="form-row">
                  <label>Número de invitados</label>
                  <input
                    v-model.number="weddingEdit.guest_count"
                    type="number"
                    min="0"
                    class="form__input"
                  />
                </div>
                <div class="form-row">
                  <label>Fecha de la boda</label>
                  <input v-model="weddingEdit.wedding_date" type="date" class="form__input" />
                </div>
                <div class="form-actions">
                  <button class="btn btn--primary btn--small" @click="updateWedding" :disabled="weddingLoading">
                    {{ weddingLoading ? 'Guardando...' : 'Guardar boda' }}
                  </button>
                </div>
              </div>
            </div>

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
                    <div class="input-prefix">
                      <span class="input-prefix__symbol">€</span>
                      <input
                        v-model.number="weddingUpdates[svc.id].price"
                        type="number"
                        step="0.01"
                        class="form__input"
                      />
                    </div>
                  </label>
                    <label>
                      Cantidad
                      <input
                        v-model.number="weddingUpdates[svc.id].quantity"
                        type="number"
                        min="1"
                        class="form__input"
                      />
                    </label>
                  <label>
                    Estado
                    <select
                      v-model="weddingUpdates[svc.id].status"
                      :class="statusSelectClass(weddingUpdates[svc.id].status)"
                      class="form__select"
                    >
                      <option v-for="opt in serviceStatusOptions" :key="opt" :value="opt">
                        {{ opt }}
                      </option>
                    </select>
                  </label>
                  <button
                    class="btn btn--primary btn--small"
                    @click="saveWeddingService(svc.id)"
                    :disabled="weddingLoading"
                  >
                    Guardar
                  </button>
                  <button
                    class="btn btn--danger btn--small"
                    @click="detachWeddingService(svc.id)"
                    :disabled="weddingLoading"
                  >
                    Quitar
                  </button>
                </div>
                <div class="pivot-meta">
                  <span>Actual: {{ svc.pivot?.status || '—' }}</span>
                  <span>Precios desde: {{ euro(svc.pivot?.price ?? 0) }}</span>
                  <span>Cant: {{ svc.pivot?.quantity ?? 1 }}</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="total">
              Total confirmado: {{ euro(weddingTotal) }}
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
        email: svc.email,
        phone: svc.phone,
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

    // Subir imagen si hay
    if (newServiceId && (createServiceImage.url || createServiceImage.file)) {
      const form = new FormData()
      if (createServiceImage.file) form.append('image', createServiceImage.file)
      if (createServiceImage.url) form.append('url', createServiceImage.url)
      await api.post(`/services/${newServiceId}/images`, form)
    }

    // Reset formulario y recargar servicios
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


//Esto hace que se actualice el servicio, primero limpia el payload, si no hay nada que actualizar no hace nada, 
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

const deleteService = async (id) => {
  if (!id) return
  servicesError.value = ''
  servicesLoading.value = true
  try {
    await api.delete(`/services/${id}`)
    await fetchServices()
  } catch (e) {
    servicesError.value = e.response?.data?.message || 'Error al eliminar servicio'
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
const wedding = ref(null) // objeto boda completo
const weddingLoading = ref(false) // estado de carga
const weddingError = ref('') // mensaje de error
const weddingUpdates = reactive({}) // cambios en pivots de servicios
const weddingEdit = reactive({ guest_count: '', status: '', wedding_date: '' }) // ediciones de la boda
const serviceStatusOptions = ['consultado', 'confirmado', 'cancelado'] // opciones de estado
const weddingStatusOptions = ['gestionando', 'planificada', 'confirmada', 'finalizada', 'cancelada']

const setWeddingUpdates = (services = []) => {
  Object.keys(weddingUpdates).forEach((key) => delete weddingUpdates[key])
  services.forEach((svc) => {
    weddingUpdates[svc.id] = {
      price: svc.pivot?.price,
      quantity: svc.pivot?.quantity ?? 1,
      status: svc.pivot?.status || 'consultado',
    }
  })
}

const syncWeddingEdit = (data) => {
  if (!data) {
    weddingEdit.guest_count = ''
    weddingEdit.status = ''
    weddingEdit.wedding_date = ''
    return
  }
  weddingEdit.guest_count = data.guest_count ?? ''
  weddingEdit.status = data.status ?? ''
  weddingEdit.wedding_date = data.wedding_date 
    ? data.wedding_date.slice(0,10)
    :''
}

const fetchWeddingDetail = async (weddingId) => {
  if (!weddingId) throw new Error('Wedding id missing')
  const res = await api.get(`/weddings/${weddingId}`)
  const data = res.data?.data
  wedding.value = data
  setWeddingUpdates(data?.services || [])
  syncWeddingEdit(data)
}

// Cargar boda del usuario
const loadWedding = async () => {
  weddingLoading.value = true
  weddingError.value = ''
  try {
    const res = await api.get('/weddings', { params: { per_page: 1 } })
    const list = res.data?.data?.data || []
    if (!list.length) {
      wedding.value = null
      setWeddingUpdates([])
      syncWeddingEdit(null)
      return
    }

    const weddingId = list[0].id
    await fetchWeddingDetail(weddingId)
  } catch (e) {
    if (e.response?.status === 404) {
      wedding.value = null
      setWeddingUpdates([])
      syncWeddingEdit(null)
      weddingError.value = 'No se ha encontrado tu boda.'
    } else if (e.response?.status === 403) {
      weddingError.value = 'No tienes permisos para ver esta boda.'
    } else {
      weddingError.value = e.response?.data?.message || 'Error al cargar la boda'
    }
  } finally {
    weddingLoading.value = false
  }
}

const createWedding = async () => {
  weddingLoading.value = true
  weddingError.value = ''
  try {
    const payload = cleanPayload({
      name: 'Mi boda',
      status: weddingEdit.status || 'gestionando',
      wedding_date: weddingEdit.wedding_date
      ? weddingEdit.wedding_date.slice(0,10)
      : null,
      guest_count: weddingEdit.guest_count === '' ? null : weddingEdit.guest_count,
    })
    const res = await api.post('/weddings', payload)
    const newWeddingId = res.data?.data?.id
    if (newWeddingId) {
      await fetchWeddingDetail(newWeddingId)
    } else {
      wedding.value = res.data?.data || null
      setWeddingUpdates(wedding.value?.services || [])
      syncWeddingEdit(wedding.value)
    }
  } catch (e) {
    weddingError.value = e.response?.data?.message || 'Error al crear boda'
  } finally {
    weddingLoading.value = false
  }
}

const updateWedding = async () => {
  if (!wedding.value?.id) return
  weddingLoading.value = true
  weddingError.value = ''
  const payload = cleanPayload({
    guest_count: weddingEdit.guest_count === '' ? null : weddingEdit.guest_count,
    status: weddingEdit.status,
    wedding_date: weddingEdit.wedding_date
      ? weddingEdit.wedding_date.slice(0,10)
      : null,
  })
  if (!Object.keys(payload).length) {
    weddingLoading.value = false
    return
  }
  try {
    await api.put(`/weddings/${wedding.value.id}`, payload)
    await fetchWeddingDetail(wedding.value.id)
  } catch (e) {
    weddingError.value = e.response?.data?.message || 'Error al actualizar la boda'
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
    await fetchWeddingDetail(wedding.value.id)
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
    await fetchWeddingDetail(wedding.value.id)
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
const euro = (value) => `${Number(value ?? 0).toFixed(2)} €` //para formatear valores monetarios a euros
const formatDate = (value) => value?.slice(0, 10) || ''

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
  }
  await loadWedding()
})
</script>
