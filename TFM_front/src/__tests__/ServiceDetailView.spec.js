import { flushPromises, mount } from '@vue/test-utils'
import { createPinia, setActivePinia } from 'pinia'
import { beforeEach, describe, expect, it, vi } from 'vitest'
import ServiceDetailView from '../views/ServiceDetailView.vue'

const route = { path: '/services/5', params: { id: 5 }, fullPath: '/services/5' }
const router = { push: vi.fn(), currentRoute: { value: route } }

vi.mock('vue-router', () => ({
  __route: route,
  __router: router,
  useRoute: () => route,
  useRouter: () => router,
  RouterLink: { template: '<a><slot /></a>' },
}))

const mockGet = vi.fn((url) => {
  if (url === '/services/5') {
    return Promise.resolve({
      data: {
        data: {
          id: 5,
          name: 'Hotel Mirador',
          price: 200,
          images: [],
          subcategory: { name: 'Hotel', category: { name: 'Alojamiento' } },
        },
      },
    })
  }
  if (url.startsWith('/reviews')) {
    return Promise.resolve({
      data: {
        data: { data: [{ id: 1, user: { name: 'Ana' }, rating: 5, comment: 'Genial' }] },
      },
    })
  }
  return Promise.resolve({ data: {} })
})

vi.mock('../api/axios', () => ({
  default: {
    get: mockGet,
    post: vi.fn(),
  },
}))

describe('ServiceDetailView', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    mockGet.mockClear()
  })

  it('loads service detail and reviews', async () => {
    const wrapper = mount(ServiceDetailView)

    await flushPromises()

    expect(wrapper.find('h1').text()).toBe('Hotel Mirador')
    expect(wrapper.findAll('.comment-item').length).toBe(1)
  })
})
