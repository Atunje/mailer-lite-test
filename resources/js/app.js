import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

import App from './views/App'

//plugins
import './plugins/bootstrap-vue'

//router
import { router, http } from "./http";

//store
import store from "./store";

//layouts
import Private from './layouts/Private.vue'
import Public from './layouts/Public.vue'

Vue.component('private-layout', Private)
Vue.component('public-layout', Public)


Vue.config.productionTip = false;
Vue.use(http);

//set the auth token in the state and get the user info
store.dispatch('auth/attempt', localStorage.getItem('authToken'));

let app;

if (navigator.cookieEnabled) {
    new Vue({
        router,
        store,
        render: h => h(App)
    }).$mount("#app");
} else {
    document.write("Please enable cookies to continue");
}

export default app;