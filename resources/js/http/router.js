import VueRouter from 'vue-router'

import Login from '../views/auth/Login'
import Register from '../views/auth/Register'
import Dashboard from '../views/dashboard'
import Fields from '../views/fields'
import { isLoggedIn } from '../utils/helpers'

const router = new VueRouter({
    mode: 'history',
    routes: [{
            path: '/',
            name: 'dashboard',
            component: Dashboard,
            meta: {
                layout: 'private',
                requiresAuth: true,
                title: 'Dashboard'
            }
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: {
                requiresAuth: false,
                title: 'Login'
            }
        },
        {
            path: '/register',
            name: 'register',
            component: Register,
            meta: {
                requiresAuth: false,
                title: 'Register'
            }
        },
        {
            path: '/fields',
            name: 'fields',
            component: Fields,
            meta: {
                layout: 'private',
                requiresAuth: true,
                title: 'Fields'
            }
        }

    ],
});

router.beforeEach((to, from, next) => {

    const is_authenticated = isLoggedIn();

    if (to.meta.requiresAuth) {

        if (is_authenticated) {

            return next();

        } else {

            next(`/login?next=${to.path}`);
        }

    }

    next();

});

router.afterEach(to => {
    document.title = `${to.meta.title || "App"} | MailerLite`;
});

export default router;