<template>
    <form class="p-4" v-on:submit.prevent="processTransaction">
        <div class="modal-card">
            <header class="modal-card-head">
                <h2 class="modal-card-title">Pembayaran</h2>
                <button type="button" class="delete" v-on:click="$emit('close')" />
            </header>
            <section class="modal-card-body">
                <div class="columns is-multiline is-variable is-2">
                
                    <div class="column">
                        <b-field label="Nota">
                            <b-input placeholder="Nota" :value="note" disabled />
                        </b-field>
                        <b-field label="Kasir">
                            <b-input placeholder="Kasir" :value="user.name" disabled />
                        </b-field>
                    </div>
                
                    <div class="column">
                        <div class="field">
                            <label class="label">Diskon</label>
                            <div class="field-body">
                                <div class="field has-addons">
                                    <p class="control">
                                        <span class="button is-static">Rp</span>
                                    </p>
                                    <div class="control has-icons-right is-expanded is-clearfix">
                                        <input type="text" class="input" placeholder="Diskon" v-model="discount" :class="{ 'is-danger': errors.discount.error }">

                                        <span class="icon is-right has-text-danger" v-if="errors.discount.error">
                                            <i class="fas fa-exclamation-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <p class="help is-danger" v-if="errors.discount.error">{{ errors.discount.message }}</p>
                        </div>
                        <div class="field">
                            <label class="label">Pajak</label>
                            <div class="field-body">
                                <div class="field has-addons">
                                    <div class="control has-icons-right is-expanded is-clearfix">
                                        <input type="text" class="input" placeholder="Pajak" v-model="tax" :class="{ 'is-danger': errors.tax.error }">

                                        <span class="icon is-right has-text-danger" v-if="errors.tax.error">
                                            <i class="fas fa-exclamation-circle"></i>
                                        </span>
                                    </div>
                                    <p class="control">
                                        <span class="button is-static">%</span>
                                    </p>
                                </div>
                            </div>
                            <p class="help is-danger" v-if="errors.tax.error">{{ errors.tax.message }}</p>
                        </div>
                    </div>

                </div>

                <b-field label="Grand Total">
                    <b-input placeholder="Grand Total" :value="currency(grandTotal)" disabled />
                </b-field>
            </section>
            <footer class="modal-card-foot">
                <b-button label="Close" v-on:click="$emit('close')" />
                <b-button native-type="submit" label="Process" type="is-link" :disabled="loading" />
            </footer>
        </div>
    </form>
</template>

<script>
    import { mapState, mapActions } from 'vuex'

    const errors = {
        discount: {},
        tax: {}
    }

    export default {
        props: ['note', 'subtotal', 'weight', 'packet', 'customer', 'create_date', 'finish_date'],
        data() {
            return {
                grandTotal: this.subtotal,
                discount: '',
                tax: '',
                loading: false,
                errors: {...errors}
            }
        },
        watch: {
            discount(discount) {
                const number = discount.replace(/\D/gi, '')
                
                this.discount = new Intl.NumberFormat('id-ID').format(number)

                this.setGrandTotal()                
            },
            tax(tax, oldTax) {
                const number = tax.replace(/\D/gi, '')

                if (number > 100) {
                    this.tax = oldTax
                } else {
                    this.tax = number
                }            
            
                this.setGrandTotal()                
            }
        },
        computed: {
            ...mapState('auth', ['user']),
            rawDiscount() {
                return this.discount.replace(/\D/gi, '')
            },
            rawTax() {
                return this.subtotal / 100 * this.tax
            }
        },
        methods: {
            ...mapActions('transaction', ['store']),
            setGrandTotal() {
                const grandTotal = this.subtotal + this.rawTax - this.rawDiscount

                this.grandTotal = Math.max(grandTotal, 0)
            },
            validate() {
                this.errors = {...errors}

                const discountError = this.rawDiscount >= (this.subtotal + this.rawTax)

                if (discountError) {
                    this.errors.discount = {
                        error: true,
                        message: 'Diskon terlalu banyak'
                    }
                }

                return !discountError
            },
            async processTransaction() {
                this.loading = true

                if (this.validate()) {
                    
                    try {
                        const transaction = await this.store({
                            note: this.note,
                            weight: this.weight,
                            create_date: this.create_date,
                            finish_date: this.finish_date,
                            tax: this.tax,
                            discount: this.rawDiscount,
                            total: this.grandTotal,
                            packet_id: this.packet,
                            customer_id: this.customer,
                            user_id: this.user.id,
                        })

                        await this.$emit('close')

                        this.$router.push({ name: 'Detail Transaction', params: { note: transaction.data.note } })
                    } catch (err) {
                        console.log(err)
                    }

                }

                this.loading = false
            }
        }
    }
</script>