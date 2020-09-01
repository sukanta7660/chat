require('./bootstrap');

window.Vue = require('vue');

//vuex support
import Vuex from 'vuex'
import storeVuex from './store/index'
Vue.use(Vuex)

const store = new Vuex.Store(storeVuex)

Vue.component('chat-main', require('./components/MainApp.vue').default);

//Moment js
import { filter } from './filter'

import VueChatScroll from 'vue-chat-scroll'
Vue.use(VueChatScroll)

const app = new Vue({
    el: '#app',
    store
});
