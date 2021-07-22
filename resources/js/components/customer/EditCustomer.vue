<template>
    <form class="p-4" v-on:submit.prevent="save">
        <div class="modal-card">
            <header class="modal-card-head">
                <h3 class="modal-card-title">Edit Pelanggan</h3>
                <button type="button" class="delete" v-on:click="$emit('close')" />
            </header>
            <section class="modal-card-body">
                <b-field label="Nama" :type="{ 'is-danger': errors.name.error }" :message="errors.name.message">
                    <b-input placeholder="Nama" v-model="customer.name"></b-input>
                </b-field>
                <div class="field">
                    <label class="label">No Telp</label>
                    <div class="field-body">
                        <div class="field has-addons">
                            <div class="control has-icons-right is-expanded">
                                <input type="text" class="input" :class="{ 'is-danger': errors.phone.error }" placeholder="No Telp" v-model="customer.phone">

                                <span class="icon is-right has-text-danger" v-if="errors.phone.error">
                                    <i class="fas fa-exclamation-circle"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <p class="help is-danger" v-if="errors.phone.error">{{ errors.phone.message }}</p>
                </div>
                <b-field label="Alamat" :type="{ 'is-danger': errors.address.error }" :message="errors.address.message">
                    <b-input type="textarea" placeholder="Alamat" v-model="customer.address"></b-input>
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
        phone: {},
        address: {}
    }

    export default {
        mixins: [validator],
        props: ['id', 'data'],
        data() {
            return {
                loading: false,
                errors: {...errors},
                customer: this.data
            }
        },
        watch: {
            'customer.phone': function (phone) {                
                this.customer.phone = phone.replace(/\D/gi, '')
            }
        },
        methods: {
            ...mapActions('customer', ['update']),
            async save() {
                this.loading = true
                this.errors = {...errors}


                try {
                    const customer = await this.update({
                        id: this.id,
                        data: this.customer
                    })

                    this.$emit('saved', 'Pelanggan Diperbarui')
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