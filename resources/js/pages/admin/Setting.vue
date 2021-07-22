<template>
    <div>
        <b-notification type="is-success" v-model="alert.active">{{ alert.msg }}</b-notification>
        <form class="card" v-on:submit.prevent="save">
            <header class="card-header">
                <h2 class="card-header-title">Pengaturan</h2>
            </header>
            <div class="card-content">
                <b-field horizontal label="Nama Aplikasi" :type="{ 'is-danger': errors.name.error }" :message="errors.name.message">
                    <b-input placeholder="Nama Aplikasi" v-model="app.name" />
                </b-field>
                <b-field horizontal label="Alamat" :type="{ 'is-danger': errors.address.error }" :message="errors.address.message">
                    <b-input type="textarea" placeholder="Alamat" v-model="app.address" />
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
    import { validator, alert } from '../../mixins'

    const errors = {
        name: {},
        address: {}
    }

    export default {
        mixins: [alert, validator],
        data() {
            return {
                loading: false,
                errors: {...errors},
                app: {
                    name: '',
                    address: ''
                }
            }
        },
        methods: {
            ...mapActions(['updateSetting']),
            async save() {
                this.loading = true
                this.errors = {...errors}

                try {
                    const res = await this.updateSetting(this.app)

                    this.makeAlert('Pengaturan diperbarui')
                } catch (err) {
                    this.validate(err.response.data.errors)
                } finally {
                    this.loading = false
                }
            }
        },
        mounted() {
            this.app = {...this.$store.state.app}
        }        
    }
</script>