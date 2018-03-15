import app from '../../resources/assets/js/app'
import Vue from 'vue'

Vue.config.silent = true
describe('Photo modal mixin', () => {
  it('has a data property for currentImageId', () => {
    expect(app.$data).to.have.deep.property('currentImageId', null)
  })

  it('has a method for setting the currentImageId', () => {
    // noinspection JSUnresolvedFunction
    app.setCurrentImageId(32)

    // noinspection JSUnresolvedVariable
    expect(app.$data.currentImageId).to.equal(32)
  })
})
Vue.config.silent = false
