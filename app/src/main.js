import Vue from 'vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import Vuetify from 'vuetify'
import VueProgressBar from 'vue-progressbar'
import VueResourceProgressBarInterceptor  from 'vue-resource-progressbar-interceptor'

import App from './App.vue'
import { routes } from './routes'

//vue router config
Vue.use(VueRouter);

//vue http resources 
Vue.use(VueResource)
Vue.http.interceptors.push(function(request, next) {
    // modify method
    //request.method = 'POST';
    // modify headers
    request.headers.set('Access-Control-Allow-Origin', '*');
    request.headers.set('Content-Type', 'application/json');
    // continue to next interceptor
    next();
});

//Vue.http.options.root = 'http://tasklist.app/api';
Vue.http.options.root = 'https://task-list-test.herokuapp.com/api';

//vue resource loadingbar
const progressbarOptions = {
    color: '#002a54',
    failedColor: '#cb171e',
    thickness: '4px',
    transition: {
        speed: '0.4s',
        opacity: '0.7s',
        termination: 700
    },
    autoRevert: false,
    location: 'top',
    inverse: false
};
Vue.use(VueProgressBar, progressbarOptions);

const progressbarInterceptorOptions = {
    latencyThreshold: 100,
    // Number of ms before progressbar starts showing, 100 is default
    responseLatency: 1000,
    // Number of ms before progressbar starts reacting to response, 50 is default
    // Can be used to wait for more requests to kick in under single progress bar
}
Vue.use(VueResourceProgressBarInterceptor, progressbarInterceptorOptions);

//vuetify config
Vue.use(Vuetify);

//vue router
const router = new VueRouter({
    mode: 'hash', //history mode cause a bug in production *see that in future
    routes
});

new Vue({
    el: '#app',
    router,
    render: h => h(App),
    data: function () {
    	return {
    		cordova: Vue.cordova
    	}
    }
});
