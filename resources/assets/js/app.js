
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */



// jquery Plugins
require('./vendor/datepicker/bootstrap-datepicker');
require('./displayToast');


// Directives
require('./directives/date');


// Filters
require('./filters/dates');
require('./filters/limitText');
require('./filters/sortBy');


//Packages
import VueRouter from 'vue-router';
Vue.use(VueRouter);

let VueMaterial = require('vue-material');
Vue.use(VueMaterial);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

//Router
import routes from './routes';

let router = new VueRouter({
    routes,
    history: true
});

//Components
import ToolbarSidenav from './components/partials/toolbarSidebar.vue';

//Config
import { config as CONFIG } from './config/index';

//Init App
const app = new Vue({
    router,
    http: {
        root: '/',
        headers: {
            'X-CSRF-TOKEN': Laravel.csrfToken
        }
    },
    components:{
        ToolbarSidenav
    },
    data(){
        return{
            title: 'Laravel Bands App',
            user: {},
            showSpinner: false,
            moment_format: 'MM/DD/YYYY',
            appLoaded: false,

            config: CONFIG
        }
    },
    created(){
        this.appLoaded = true;
        console.info('app started');
        /*this.$http.get('/utils/user').then((response) =>{
            this.user = response.body;
        })*/
    }

});

app.$mount('#app');
