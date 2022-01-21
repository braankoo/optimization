import VueRouter from "vue-router";
import AdPlatformIndex from "./adPlatform/index";

import index from "./client/index";
import single from "./client/single";
import campaignSingle from "./campaign/single";
import campaignIndex from "./campaign/index";
import adGroupIndex from "./adGroup/index";


import Container from "./Container";
import Login from "./components/Login";


const router = new VueRouter(
    {
        linkActiveClass: 'open active',
        scrollBehavior: () => ({y: 0}),
        mode: 'history',

        routes: [
            {
                path: '/login',
                component: Login,
                name: 'Login'

            },
            {
                path: '/',
                redirect: '/adPlatforms',
                name: 'Ad Platforms',
                meta: {
                    requiresAuth: true
                },
            },
            {
                path: '/adPlatforms',
                component: Container,
                meta: {
                    requiresAuth: true
                },
                children: [
                    {
                        path: '',
                        component: AdPlatformIndex
                    }
                ],
            },
            {
                path: '/:adPlatform',
                component: Container,
                meta: {
                    requiresAuth: true
                },
                children: [
                    {
                        path: 'client',
                        component: index,
                        name: 'Client Index'
                    },
                    {
                        path: 'client/:client',
                        component: single,
                        name: 'Client Single'
                    },
                    {
                        path: 'client/:client/campaign/:campaign',
                        component: campaignSingle,
                        name: 'Campaign Single'
                    },
                    {
                        path: 'campaigns',
                        component: campaignIndex
                    },
                    {
                        path: 'adGroups',
                        component: adGroupIndex
                    }
                ]
            }

        ]
    });

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (localStorage.getItem('token') == null) {
            next({
                name: 'Login',
                params: {nextUrl: to.fullPath}
            })
        }

        window.axios.interceptors.request.use(request => {
            request.headers['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
            return request;
        });

        next();

    } else {
        next();
    }
});


export default router;
