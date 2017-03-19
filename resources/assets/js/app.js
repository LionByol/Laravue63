
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

require('./filters');
require('./resources');

Vue.component('please-wait-modal', require("./components/layout/PleaseWaitModal.vue"));
Vue.component('menu-component', require("./components/menuComponent.vue"));

import AppComponent from "./components/AppComponent.vue";
import PayableComponent from "./components/payable/PayableComponent.vue";
import PayableListComponent from "./components/payable/ListComponent.vue";
import PayableFormComponent from "./components/payable/FormComponent.vue";

import ReceivableComponent from "./components/receivable/ReceivableComponent.vue";
import ReceivableListComponent from "./components/receivable/ListComponent.vue";
import ReceivableFormComponent from "./components/receivable/FormComponent.vue";


const app = Vue.extend({
    components: {
        AppComponent
    }
});

var VueRouter = require('vue-router');
Vue.use(VueRouter);
var router = new VueRouter();

router.map({
    '/': {
        name: 'dashboard',
        component: AppComponent
    },

    '/contas/pagar': {
        name: 'contas.pagar',
        component: PayableComponent,
        subRoutes: {
            '/': {
                component: PayableListComponent
            },
            '/nova': {
                name: 'contas.pagar.nova',
                component: PayableFormComponent
            },
            '/:id/editar': {
                name: 'contas.pagar.editar',
                component: PayableFormComponent
            },
        }
    },
    '/contas/receber': {
        name: 'contas.receber',
        component: ReceivableComponent,
        subRoutes: {
            '/': {
                component: ReceivableListComponent
            },
            '/nova': {
                name: 'contas.receber.nova',
                component: ReceivableFormComponent
            },
            '/:id/editar': {
                name: 'contas.receber.editar',
                component: ReceivableFormComponent
            },
        }
    },

    '*': {
        component: AppComponent
    }
});

router.redirect({
    '*': '/'
});

router.start(app, 'body');



