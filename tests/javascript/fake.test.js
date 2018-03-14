import { mount } from '@vue/test-utils'
import example from '../../resources/assets/js/components/ExampleComponent.vue'

describe('foo', () => {
  it('defaults to 0', () => {
    const wrapper = mount(example)
    expect(wrapper.vm.count).to.equal(3)
  })
})