<template>
    <form class="p-4" v-on:submit.prevent="save">
        <div class="modal-card">
            <header class="modal-card-head">
                <h3 class="modal-card-title">Tambah Pengguna</h3>
                <button type="button" class="delete" v-on:click="$emit('close')" />
            </header>
            <section class="modal-card-body">
                <b-field label="Nama" :type="{ 'is-danger': errors.name.error }" :message="errors.name.message">
                    <b-input placeholder="Nama" v-model="user.name"></b-input>
                </b-field>
                <b-field label="Username" :type="{ 'is-danger': errors.username.error }" :message="errors.username.message">
                    <b-input placeholder="Username" v-model="user.username"></b-input>
                </b-field>
                <b-field label="Password" :type="{ 'is-danger': errors.password.error }" :message="errors.password.message">
                    <b-input type="password" placeholder="Password" v-model="user.password"></b-input>
                </b-field>
            </section>
            <footer class="modal-card-foot">
                <b-button label="Batal" v-on:click="$emit('close')" />
                <b-button label="Simpan" native-type="submit" type="is-link" :disabled="loading" />
            </footer>
        </div>
    </form>
</template>

<script>
    import { mapActions } from 'vuex'
    import { validator } from '../../mixins'

    const errors = {
        name: {},
        username: {},
        password: {}
    }

    export default {
        mixins: [validator],
        data() {
            return {
                loading: false,
                user: {
                    name: '',
                    username: '',
                    password: ''
                },
                errors: {...errors}
            }
        },
        methods: {
            ...mapActions('user', ['store']),
            async save() {
                this.loading = true
                this.errors = {...errors}

                try {
                    const user = await this.store(this.user)

                    this.$emit('saved', 'Pengguna Ditambahkan')
                    this.$emit('close')
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