import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import App from '../App.vue'

describe('App', () => {
  it('mounts without crashing and renders router view stub', () => {
    const wrapper = mount(App, {
      global: {
        stubs: {
          AppNavbar: {
            template: '<div>NavbarStub</div>',
          },
          'router-view': {
            template: '<div>RouterViewStub</div>',
          },
        },
      },
    })

    expect(wrapper.text()).toContain('RouterViewStub')
    expect(wrapper.text()).toContain('NavbarStub')
  })
})
