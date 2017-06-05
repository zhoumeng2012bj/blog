
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
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

import Vue from 'vue';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-default/index.css';
import VueRouter from 'vue-router';
import routes from './routes';
import 'font-awesome/css/font-awesome.min.css'

import { getPermissionList } from './api/api.js';



Vue.use(ElementUI);
Vue.use(VueRouter);

const router = new VueRouter({
    routes
})
router.beforeEach((to, from, next) => {
    //NProgress.start();
    if (to.path == '/login') {
        sessionStorage.removeItem('user');
    }
    let user = JSON.parse(sessionStorage.getItem('user'));
    let para = {};
    getPermissionList(para).then(function(res){
        sessionStorage.removeItem('permission');
        sessionStorage.setItem('permission', JSON.stringify(res.data));
    });
    Vue.prototype.fun = function (funKey){
        let res = JSON.parse(sessionStorage.getItem('permission'));
        //获取权限列表
        var i = res.length;
        while (i--) {
            if (res[i] === funKey) {
                return true;
            }
        }
    }
    if (!user && to.path != '/login') {
        next({ path: '/login' })
    } else {
        next()
    }
})
const app = new Vue({
    router,
}).$mount('#app')
