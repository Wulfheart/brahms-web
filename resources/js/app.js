
window.Vue = require('vue');

Vue.component('viz', require('./components/VizComponent.vue').default);

Vue.use(require('vue-shortkey'))

const app = new Vue({
    el: '#app',
});

