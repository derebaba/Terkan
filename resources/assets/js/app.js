window.Vue = require('vue');

import Vue from 'vue'

import Autocomplete from 'v-autocomplete'

// You need a specific loader for CSS files like https://github.com/webpack/css-loader
import 'v-autocomplete/dist/v-autocomplete.css'

Vue.use(Autocomplete)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('lang-autocomplete', require('./components/LanguageAutocomplete.vue'));

Vue.component("genre-filter", require("./components/GenreFilter.vue"));

const app = new Vue({
	el: '#app',
	data: { }
});
