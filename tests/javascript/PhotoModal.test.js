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
        {path: 'baz.jpg', id: 5, caption: 'the great baz', meta_data: '{"SomeLens":"40", "SomeCamera":"cannon"}'}
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

  it('sets the image when initialImageId goes from null to an id', () => {
    wrapper = mount(PhotoModal, {
      propsData: {
        initialImageId: 0,
        images: [{path: 'foo.jpg', id: 1}]
      }
    })

    wrapper.setData({ initialImageId: 1 })
    expect(wrapper.find('.modal-content').vnode.data.style.backgroundImage).to.equal('url(foo.jpg)')
  })

  it('has the currentImage as the background to the modal-content element', () => {
    expect(wrapper.find('.modal-content').vnode.data.style.backgroundImage).to.equal('url(baz.jpg)')
  })

  it('changes to the next image when clicking on next', () => {
    wrapper.setData({ currentImageId: 3 })
    const next = wrapper.find('.next-image')
    next.trigger('click')
    expect(wrapper.find('.modal-content').vnode.data.style.backgroundImage).to.equal('url(baz.jpg)')
  })

  it('changes to the first image when clicking on next if currently on the last image', () => {
    wrapper.setData({ currentImageId: 5 })
    const next = wrapper.find('.next-image')
    next.trigger('click')
    expect(wrapper.find('.modal-content').vnode.data.style.backgroundImage).to.equal('url(foo.jpg)')
  })

  it('changes to the previous image when clicking on previous', () => {
    wrapper.setData({ currentImageId: 3 })
    const prev = wrapper.find('.prev-image')
    prev.trigger('click')
    expect(wrapper.find('.modal-content').vnode.data.style.backgroundImage).to.equal('url(foo.jpg)')
  })

  it('changes to the last image when clicking on previous if currently on the first image', () => {
    wrapper.setData({ currentImageId: 1 })
    const prev = wrapper.find('.prev-image')
    prev.trigger('click')
    expect(wrapper.find('.modal-content').vnode.data.style.backgroundImage).to.equal('url(baz.jpg)')
  })

  it('emits the close:modal event when the close button is clicked', () => {
    const close = wrapper.find('.modal-close')
    close.trigger('click')
    expect(!!wrapper.emitted()['close:modal']).to.equal(true)
  })

  it('hides the image info by default', () => {
    expect(wrapper.contains('.meta-data')).to.equal(false)
  })

  it('shows the image info when clicking on the info icon', () => {
    const info = wrapper.find('.modal-info')
    info.trigger('click')
    expect(wrapper.contains('.meta-data')).to.equal(true)
  })

  it('hides the image info when clicking on meta-data-close', () => {
    const info = wrapper.find('.modal-info')
    info.trigger('click')
    const closeInfo = wrapper.find('.meta-data-close')
    closeInfo.trigger('click')
    expect(wrapper.contains('.meta-data')).to.equal(false)
  })

  it('hides the image info when switching to the next image', () => {
    const info = wrapper.find('.modal-info')
    info.trigger('click')
    const next = wrapper.find('.next-image')
    next.trigger('click')
    expect(wrapper.contains('.meta-data')).to.equal(false)
  })

  it('hides the image info when switching to the previous image', () => {
    const info = wrapper.find('.modal-info')
    info.trigger('click')
    const prev = wrapper.find('.prev-image')
    prev.trigger('click')
    expect(wrapper.contains('.meta-data')).to.equal(false)
  })

  it('hides the image info when closing and reopening the modal', () => {
    const info = wrapper.find('.modal-info')
    info.trigger('click')
    const close = wrapper.find('.modal-close')
    close.trigger('click')
    wrapper.setData({ currentImageId: 1 })
    expect(wrapper.contains('.meta-data')).to.equal(false)
  })

  it('sets the image caption in the info', () => {
    const info = wrapper.find('.modal-info')
    info.trigger('click')
    expect(wrapper.find('.meta-data').html()).to.contain('Description')
    expect(wrapper.find('.meta-data').html()).to.contain('the great baz')
  })

  it('sets the image meta data in the info', () => {
    const info = wrapper.find('.modal-info')
    info.trigger('click')
    expect(wrapper.find('.meta-data').html()).to.contain('<th>Some Lens</th><td>40</td>')
    expect(wrapper.find('.meta-data').html()).to.contain('<th>Some Camera</th><td>cannon</td>')
  })
})