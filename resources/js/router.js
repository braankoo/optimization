import VueRouter from "vue-router";
import AdPlatformIndex from "./adPlatform/index";

import index from "./client/index";
import single from "./client/single";
import campaignSingle from "./campaign/single";
import campaignIndex from "./campaign/index";
import adGroupIndex from "./adGroup/index";


import Container from "./Container";


const router = new VueRouter(
    {
        linkActiveClass: 'open active',
        scrollBehavior: () => ({y: 0}),
        mode: 'history',

        routes: [

            {
                path: '/adPlatforms',
                component: Container,
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


export default router;
