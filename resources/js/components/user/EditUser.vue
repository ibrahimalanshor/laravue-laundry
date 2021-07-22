<template>
    <form class="p-4" v-on:submit.prevent="save">
        <div class="modal-card">
            <header class="modal-card-head">
                <h3 class="modal-card-title">Edit Pengguna</h3>
                <button type="button" class="delete" v-on:click="$emit('close')" />
            </header>
            <section class="modal-card-body">
                <b-field label="Nama" :type="{ 'is-danger': errors.name.error }" :message="errors.name.message">
                    <b-input placeholder="Nama" v-model="user.name"></b-input>
                </b-field>
                <b-field label="Username" :type="{ 'is-danger': errors.username.error }" :message="errors.username.message">
                    <b-input placeholder="Username" v-model="user.username"></b-input>
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
    }

    export default {
        mixins: [validator],
        props: ['id', 'user'],
        data() {
            return {
                loading: false,
                errors: {...errors}
            }
        },
        methods: {
            ...mapActions('user', ['update']),
            async save() {
                this.loading = true
                this.errors = {...errors}


                try {
                    const user = await this.update({
                        id: this.id,
                        data: this.user
                    })

                    this.$emit('saved', 'Pengguna Diperbarui')
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