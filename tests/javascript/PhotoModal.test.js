import { mount } from '@vue/test-utils'
import PhotoModal from '../../resources/assets/js/components/PhotoModal.vue'

let wrapper

beforeEach(() => {
  wrapper = mount(PhotoModal, {
    propsData: {
      initialImageId: 5,
      images: [
        {path: 'foo.jpg', id: 1},
        {path: 'bar.jpg', id: 3},
        {path: 'baz.jpg', id: 5}
      ]
    },
    attachToDocument: true
  })
})

describe('PhotoModal.vue', () => {
  it('does not show it when the initialImageId prop is null', () => {
    wrapper.setProps({initialImageId: null})
    expect(wrapper.isEmpty()).to.equal(true)
  })

  it('is visible when the initialImageId prop is not null', () => {
    expect(wrapper.isEmpty()).to.equal(false)
  })

  it('has the currentImage as the background to the modal-content element', () => {
    expect(wrapper.find('.modal-content').vnode.data.style.backgroundImage).to.equal('url(baz.jpg)')
  })

  it('changes to the next image when clicking on next', () => {
    wrapper.setData({ currentImageId: 3 })
    const next = wrapper.find('.next-image')
    next.trigger('click')
    expect(wrapper.vm.currentImageId).to.equal(5)
  })

  it('emits the close:modal event when the close button is clicked', () => {
    const close = wrapper.find('.modal-close')
    close.trigger('click')
    expect(!!wrapper.emitted()['close:modal']).to.equal(true)
  })
})