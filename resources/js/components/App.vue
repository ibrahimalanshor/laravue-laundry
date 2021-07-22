<template>
    <div>
        <vue-progress-bar />

        <div v-if="login">
            <navbar />

            <section class="section">
                <div class="container">
                    <div class="is-flex is-align-items-center is-justify-content-space-between mb-5">
                        <h1 class="title m-0">{{ $route.meta.title }}</h1>
                        <nav class="breadcrumb is-hidden-mobile">
                            <ul>
                                <li v-for="(route, key) in $route.meta.breadcrumbs" :class="{ 'is-active': route.name === $route.name }" :key="key">
                                    <router-link :to="{ name: route.name }">{{ route.name }}</router-link>
                                </li>
                            </ul>
                        </nav>     
                    </div>
                    <router-view></router-view>
                </div>
            </section>
        </div>
        <div v-else>
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import { mapState, mapMutations } from 'vuex'
    import Navbar from './Navbar'

    export default {
        components: {
            Navbar,
        },
        data() {
            return {
                loading: true,
                blacklistProgress: ['Create Transaction', 'Detail Transaction', 'Dashboard']
            }
        },
        computed: {
            ...mapState('auth', ['login', 'token'])
        },
        methods: {
            ...mapMutations('auth', ['logout']),
            setProgress() {
                if (this.$route.name === 'Login') {
                    this.$Progress.setColor('hsl(217, 71%, 53%)')
                } else {
                    this.$Progress.setColor('hsl(48, 100%, 67%)')
                }

                this.$Progress.start()

                this.$router.beforeEach((to, from, next) => {
                    if (to.name === 'Login') {
                        this.$Progress.setColor('hsl(217, 71%, 53%)')
                    } else {
                        this.$Progress.setColor('hsl(48, 100%, 67%)')
                    }

                    if (to.name !== from.name) {
                        this.$Progress.start()
                    }

                    next()
                })

                this.$router.afterEach((to, from) => {
                    if (!this.blacklistProgress.includes(to.name)) {
                        this.$Progress.finish()
                    }
                })
            },
            setAxios() {
                window.axios.defaults.headers.common['Authorization'] = this.token

                window.axios.interceptors.response.use(res => res, err => {
                    if (err.response.status === 401) {
                        this.logout()

                        this.$router.push({ name: 'Login' })
                    }

                    return Promise.reject(err)
                })
            }
        },
        created() {
            this.setProgress()
            this.setAxios()
        },
        mounted() {
            if (!this.blacklistProgress.includes(this.$route.name)) {
                this.$Progress.finish()
            }
        }
    }
</script>