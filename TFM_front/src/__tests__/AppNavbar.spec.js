import { mount } from '@vue/test-utils'
import { createPinia, setActivePinia } from 'pinia'
import { beforeEach, describe, expect, it, vi } from 'vitest'
import AppNavbar from '../components/AppNavbar.vue'
import { useAuthStore } from '../stores/auth'

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

describe('AppNavbar', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
  })

  it('shows login/register when not authenticated', () => {
    const wrapper = mount(AppNavbar)
    expect(wrapper.text()).toContain('Login')
    expect(wrapper.text()).toContain('Registro')
    expect(wrapper.text()).not.toContain('Perfil')
  })

  it('shows profile and logout when authenticated', () => {
    const auth = useAuthStore()
    auth.token = 'abc'
    auth.user = { id: 1, name: 'Test' }

    const wrapper = mount(AppNavbar)

    expect(wrapper.text()).toContain('Perfil')
    expect(wrapper.text()).toContain('Salir')
    expect(wrapper.text()).not.toContain('Login')
  })
})
