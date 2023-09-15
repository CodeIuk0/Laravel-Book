import { createRouter, createWebHistory } from 'vue-router';


import _404View from './pages/_404View.vue'
import store from "./sotre/sotre"

const  routes = [

    {
      path: '/',
      name: 'root',
      component: import("./pages/LoginPage.vue"),
    },
    {
        path: '/login',
        name: 'login',
        component: import("./pages/LoginPage.vue"),
        meta: {
            noRequiresAuth: true,
            ifNoAuthRedirectTo: "dashboard"
            },
      },
    {
        path: '/register',
        name: 'register',
        component: import("./pages/RegisterPage.vue"),
        meta: {
            noRequiresAuth: true,
            ifNoAuthRedirectTo: "dashboard"
        },
      },
      {
        path: '/dashboard',
        name: 'dashboard',
        component: import("./pages/DashboardPage.vue"),
        meta: {
            requiresAuth: true,
            ifNoAuthRedirectTo: "login",

            callbackResolve: async (to,from,next) => {
                await store.dispatch("loadBooks");
            }
        },
      },
    {
        path: '/:pathMatch(.*)*',
        name:"undefined",
        component: _404View,
        meta: {
            _404:true
            }

    },
  ]

const router = new createRouter({
    history: createWebHistory(),
    routes})

router.beforeEach(async (to, from, next) => {
    if(!to.meta._404)
    {
        if(!store.state.User){
            await store.dispatch("loadUser");
        }

        if (to.matched.some(record => ((record.meta.noRequiresAuth && store.state.User) ||  (record.meta.requiresAuth && !store.state.User)))) {
                return next({
                    name: to.meta.ifNoAuthRedirectTo || to.meta.requiresAuth,
                    query: { from: to.name }
                })
        }

        if(to.meta.callbackResolve)
        {
            const result = await to.meta.callbackResolve() || null;
            if(result)
             return result;
        }
    }
    next()
    })

export default router;
