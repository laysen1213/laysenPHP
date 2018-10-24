// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store'

import Element from 'element-ui' // element-ui
import 'element-ui/lib/theme-chalk/index.css'
Vue.use(Element, { size: 'mini' })

import upload from './plug/upload'
import lsTable from './plug/lsTable'
Vue.component('upload', upload)
Vue.component('lsTable', lsTable)

// import http_host from './base/js/common'
// Vue.prototype.$http = http_host

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  template: '<App/>',
  components: { App }
})
