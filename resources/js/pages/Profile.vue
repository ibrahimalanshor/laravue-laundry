<template>
    <div>
        <b-notification type="is-success" v-model="alert.active">{{ alert.msg }}</b-notification>
        <form class="card" v-on:submit.prevent="save">
            <header class="card-header">
                <h2 class="card-header-title">Update Profil</h2>
            </header>
            <div class="card-content">
                <b-field horizontal label="Nama" :type="{ 'is-danger': errors.name.error }" :message="errors.name.message">
                    <b-input placeholder="Nama" v-model="user.name" />
                </b-field>
                <b-field horizontal label="Username" :type="{ 'is-danger': errors.username.error }" :message="errors.username.message">
                    <b-input placeholder="Username" v-model="user.username" />
                </b-field>
                <b-field horizontal label="Password" :type="{ 'is-danger': errors.password.error }" :message="errors.password.message">
                    <b-input type="password" placeholder="Password" v-model="user.password" />
                </b-field>
                <b-field horizontal>
                    <b-button native-type="submit" type="is-link" label="Simpan" :disabled="loading" />
                </b-field>
            </div>
        </form>
    </div>
</template>

<script>
    import { mapActions } from 'vuex'
    import { validator, alert } from '../mixins'

    const errors = {
        name: {},
        username: {},
        password: {}
    }

    export default {
        mixins: [alert, validator],
        data() {
            return {
                loading: false,
                errors: {...errors},
                user: {
                    name: '',
                    username: '',
                    password: ''
                }
            }
        },
        methods: {
            ...mapActions('auth', ['updateProfile']),
            async save() {
                this.loading = true
                this.errors = {...errors}

                try {
                    const res = await this.updateProfile(this.user)

                    this.makeAlert('Profil diperbarui')
                } catch (err) {
                    this.validate(err.response.data.errors)
                } finally {
                    this.loading = false
                }
            }
        },
        mounted() {
            this.user = {...this.$store.state.auth.user}
        }        
    }
</script>