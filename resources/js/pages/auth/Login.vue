<template>
    <div class="is-flex is-align-items-center has-background-light login-box">
        <section class="section login-section">
            <div class="container">
                <div class="column is-full-mobile is-6-tablet is-4-desktop p-0 mx-auto">
                    <h1 class="title has-text-centered mb-4 has-text-weight-bold">Login</h1>

                    <div class="card">
                        <form class="card-content" v-on:submit.prevent="processLogin">
                            <b-field label="Username" :type="{ 'is-danger': errors.username.error }" :message="errors.username.message">
                                <b-input placeholder="Username" v-model="user.username" />
                            </b-field>
                            <b-field label="Password" :type="{ 'is-danger': errors.password.error }" :message="errors.password.message">
                                <b-input type="password" placeholder="Password" v-model="user.password" />
                            </b-field>
                            <b-button type="is-link" native-type="submit" :disabled="loading">
                                Login
                            </b-button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import { mapActions, mapMutations } from 'vuex'
    import { validator } from '../../mixins'

    const errors = {
        username: {},
        password: {}
    }

    export default {
        mixins: [validator],
        data() {
            return {
                loading: false,
                errors: {...errors},
                user: {
                    username: '',
                    password: ''
                }
            }
        },
        methods: {
            ...mapActions('auth', ['login']),
            ...mapMutations(['flash']),
            async processLogin() {
                this.loading = true
                this.errors = {...errors}

                try {
                    const token = await this.login(this.user)

                    this.loading = false

                    this.$router.push({
                        name: 'Dashboard'
                    })
                } catch (err) {
                    const errors = err.response.data.errors

                    this.validate(errors)
                } finally {
                    this.loading = false
                }
            }
        }
    }
</script>