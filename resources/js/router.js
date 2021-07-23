import { createWebHistory, createRouter } from "vue-router";
import Home from './modules/Home.vue';
import Articles from './modules/Articles.vue';
import Login from './auth/Login.vue';
import Register from './auth/Register.vue';
import ResetPassword from './auth/ResetPassword.vue';
import NewPassword from './auth/NewPassword.vue';
import UserAccount from './modules/UserAccount.vue';
import Logout from './auth/Logout.vue';
import NotFound from './components/NotFound.vue';

import store from './store/store.js';

const routes = [
    { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound },
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'articles',
        path: '/articles',
        component: Articles
    },
    {
        name: 'login',
        path: '/login',
        component: Login,
        beforeEnter: (to, from, next) => {
            if (store.state.isAuthenticated) next({ name: 'home' })
            else next()
        }
    },
    {
        name: 'logout',
        path: '/logout',
        component: Logout,
        beforeEnter: (to, from, next) => {
            if (!store.state.isAuthenticated) next({ name: 'login' })
            else next()
        }
    },
    {
        name: 'verify-email',
        path: '/verify-email/:token',
        component: Login
    },
    {
        name: 'register',
        path: '/register',
        component: Register,
        beforeEnter: (to, from, next) => {
            if (store.state.isAuthenticated) next({ name: 'home' })
            else next()
        }
    },
    {
        name: 'reset-password',
        path: '/password/reset-password',
        component: ResetPassword,
        beforeEnter: (to, from, next) => {
            if (store.state.isAuthenticated) next({ name: 'home' })
            else next()
        }
    },
    {
        name: 'new-password',
        path: '/password/reset/:token/',
        component: NewPassword,
        beforeEnter: (to, from, next) => {
            if (store.state.isAuthenticated) next({ name: 'home' })
            else next()
        }
    },
    {
        name: 'user-account',
        path: '/user/account',
        component: UserAccount,
        beforeEnter: (to, from, next) => {
            if (!store.state.isAuthenticated) next({ name: 'login' })
            else next()
        }
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
    linkActiveClass: 'active',
    linkExactActiveClass: 'active'
});

export default router;