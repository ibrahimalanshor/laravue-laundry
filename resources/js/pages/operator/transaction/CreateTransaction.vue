<template>
    <div class="columns is-multiline is-mobile">
        
        <div class="column is-full-mobile is-4-tablet">
            <search-customer :errors="errors" v-model="customer" />
        </div>
        
        <div class="column is-full-mobile is-8-tablet">
            <transaction-form v-on:store-packet="setPacket" :errors="errors" v-on:store-weight="setWeight" v-on:store-date="setDate" v-model="subtotal" />

            <div class="buttons mt-5 is-justify-content-flex-end">
                <b-button label="Kembali" tag="router-link" :to="{ name: 'Transaction' }" />
                <b-button type="is-link" label="Proses" :disabled="loading" v-on:click="processPayment" />
            </div>
        </div>

    </div>
</template>

<script>
    import { mapActions } from 'vuex'
    import { SearchCustomer, TransactionForm, PaymentForm } from '../../../components/transaction'

    const errors = {
        customer: {},
        packet: {},
        weight: {}
    }

    export default {
        components: { SearchCustomer, TransactionForm, PaymentForm },
        data() {
            return {
                note: '',
                create_date: '',
                finish_date: '',
                customer: null,
                packet: null,
                weight: 0,
                subtotal: 0,
                errors: {...errors},
                loading: false,
            }
        },
        methods: {
            ...mapActions('transaction', ['getNote']),
            setPacket(packet) {
                this.packet = packet
            },
            setWeight(weight) {
                this.weight = weight
            },
            setDate(create_date, finish_date) {
                this.create_date = create_date.toISOString().substr(0, create_date.toISOString().indexOf('T'))
                this.finish_date = finish_date.toISOString().substr(0, finish_date.toISOString().indexOf('T'))
            },
            async setNote() {
                try {
                    const note = await this.getNote()

                    this.note = note.data
                } catch (err) {
                    console.log(err)
                }
            },
            validate() {
                this.errors = {...errors}

                if (!this.customer) {
                    this.errors.customer = {
                        error: true,
                        message: 'Pelanggan wajib diisi'
                    }
                }

                if (!this.packet) {
                    this.errors.packet = {
                        error: true,
                        message: 'Paket wajib diisi'
                    }
                }

                if (!this.weight) {
                    this.errors.weight = {
                        error: true,
                        message: 'Berat wajib diisi'
                    }
                }

                const validate = !this.customer || !this.packet || !this.weight

                return !!!validate
            },
            processPayment() {
                this.loading = true

                if (this.validate()) {
                    this.$buefy.modal.open({
                        component: PaymentForm,
                        parent: this,
                        props: {
                            note: this.note,
                            customer: this.customer,
                            packet: this.packet,
                            weight: this.weight,
                            subtotal: this.subtotal,
                            create_date: this.create_date,
                            finish_date: this.finish_date
                        },
                        hasModalCard: true,
                    })
                }

                this.loading = false
            }
        },
        async mounted() {
            await this.setNote()

            this.$Progress.finish()
        }
    }
</script>