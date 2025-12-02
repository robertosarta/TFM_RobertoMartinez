import { flushPromises, mount } from '@vue/test-utils'
import { createPinia, setActivePinia } from 'pinia'
import { beforeEach, describe, expect, it, vi } from 'vitest'
import HomeView from '../views/HomeView.vue'

vi.mock('vue-router', () => {
  const route = { path: '/', params: {}, fullPath: '/' }
  const router = { push: vi.fn(), currentRoute: { value: { path: '/' } } }
  return {
    __route: route,
    __router: router,
    useRoute: () => route,
    useRouter: () => router,
    RouterLink: { template: '<a><slot /></a>' },
  }
})

const mockGet = vi.fn((url) => {
  if (url === '/categories') {
    return Promise.resolve({ data: { data: [{ id: 1, name: 'Foto' }] } })
  }
  if (url === '/subcategories') {
    return Promise.resolve({
      data: { data: [{ id: 10, name: 'Drone', category_id: 1 }] },
    })
  }
  if (url === '/services') {
    return Promise.resolve({
      data: {
        data: {
          data: [
            { id: 100, name: 'Drone Pro', description: 'Video', price: 99, subcategory_id: 10, images: [] },
          ],
        },
      },
    })
  }
  return Promise.resolve({ data: {} })
})

vi.mock('../api/axios', () => ({
  default: {
    get: mockGet,
  },
}))

describe('HomeView', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    mockGet.mockClear()
  })

  it('renders categories and filters by category on click', async () => {
    const wrapper = mount(HomeView)

    await flushPromises()

    const chips = wrapper.findAll('button.chip')
    expect(chips.length).toBeGreaterThan(0)

    await chips[0].trigger('click')
    await flushPromises()

    const headings = wrapper.findAll('h2')
    const selectedHeading = headings[1] // services section heading
    expect(selectedHeading.text()).toBe('Foto')
  })
})
