/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Buefy from 'buefy'
import VueProgressBar from 'vue-progressbar'

import router from './router'
import store from './store'

import App from './components/App'

const options = {
  color: 'hsl(48, 100%, 67%)',
  failedColor: 'hsl(348, 100%, 61%)',
  thickness: '4px',
  transition: {
    speed: '0.2s',
    opacity: '0.6s',
    termination: 300
  },
  autoRevert: true,
  location: 'top',
  inverse: false
}

Vue.use(Buefy, {
    defaultIconPack: 'fas'
})

Vue.mixin({
  methods: {
    currency(number) {
      return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(number)
    },
    date(date) {
      const month = ['Januari', 'Februari', 'Marer', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'November', 'Desember']
      date = new Date(date)

      return `${date.getDate()} ${month[date.getMonth()]} ${date.getFullYear()}`
    }
  }
})

Vue.use(VueProgressBar, options)

const app = new Vue({
    router,
    store,
    render: h => h(App),
    el: '#app',
});
