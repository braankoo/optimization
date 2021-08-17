<template>
    <div id="admin">
        <b-container :fluid="true">
            <b-row>
                <b-col cols="1">
                    <sidebar-menu :menu="menu" width="150px">
                        <span slot="toggle-icon"><i class="fa fa fa-arrows-h"></i></span>
                        <span slot="footer" class="text-center"><a href=""
                                                                   class="vsm--link vsm--link_level-1"
                                                                   @click.self.prevent="logout"
                        >Log out</a></span>
                    </sidebar-menu>
                </b-col>
                <b-col cols="11" class="pt-3">
                    <router-view></router-view>
                </b-col>
            </b-row>
        </b-container>
    </div>
</template>

<script>
import {SidebarMenu} from 'vue-sidebar-menu'

export default {
    components: {
        SidebarMenu
    },
    name: "Container",
    data() {
        return {
            menu: [
                {
                    title: 'Ad Platforms',
                    href: '/adPlatforms'
                },
                {
                    title: 'Google',
                    child: [
                        {

                            title: 'Platform',
                            href: '/google/client',

                        },
                        {

                            title: 'Campaigns',
                            href: '/google/campaigns'
                        },
                        {

                            title: 'AdGroups',
                            href: '/google/adGroups'
                        },

                    ]
                },
                {
                    title: 'Bing',
                    child: [
                        {

                            title: 'Platform',
                            href: '/bing/client'
                        },
                        {

                            title: 'Campaigns',
                            href: '/bing/campaigns'
                        },
                        {

                            title: 'AdGroups',
                            href: '/bing/adGroups'
                        },

                    ]
                },
                {
                    title: 'Gemini',
                    child: [
                        {

                            title: 'Platform',
                            href: '/gemini/client'
                        },
                        {

                            title: 'Campaigns',
                            href: '/gemini/campaigns'
                        },
                        {

                            title: 'AdGroups',
                            href: '/gemini/adGroups'
                        },

                    ]
                }

            ]
        }
    },
    methods: {
        logout() {
            this.$http.post('/logout').then(() => {
                localStorage.removeItem('token');
                this.$router.push({'name': 'Login'});
            })
        }
    }
}

</script>

<style>


.v-sidebar-menu .vsm--toggle-btn:after,
.v-sidebar-menu .vsm--arrow:after {
    font: normal normal normal 14px/1 FontAwesome;
}

.v-sidebar-menu .vsm--toggle-btn:after {
    content: "\f337";
}

.v-sidebar-menu {
    background: -webkit-gradient(linear, left top, left bottom, from(#a31ffb), to(#5d72c5));
    background: linear-gradient(180deg, #a31ffb 0, #5d72c5);
}
.v-sidebar-menu .vsm--dropdown .vsm--list {
    background: linear-gradient(
        180deg
        , #a31ffb 0, #5d72c5);
}
</style>
