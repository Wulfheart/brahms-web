
window.Vue = require('vue');

Vue.component('viz', require('./components/VizComponent.vue').default);

const app = new Vue({
    el: '#app',
});
