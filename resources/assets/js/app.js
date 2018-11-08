
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./framework');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('example-component', require('./components/ExampleComponent.vue'));

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);

Vue.component('example-component', {
  data: function () {
    return {
      count: 0
    }
  },
  template: '<button v-on:click="count++">You clicked me {{ count }} times.</button>'
});

Vue.component('delete-button-component', {
	data: function () {
		return {
		  rows: []
		}
	},
	methods: {
		addRow: function(){
			this.rows.push({});
		},
		removeRow: function(row){
			//console.log(row);
			this.rows.$remove(row);
		}
	},
	template: '<button type="button" v-on:click="removeRow(row)" class="fa fa-remove delete-button"></button>'
});

const app = new Vue({
    el: '#app'
});

// Import jQuery Plugins
//import 'jquery-ui/ui/widgets/datepicker.js';