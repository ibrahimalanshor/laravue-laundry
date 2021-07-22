<template>
    <div class="card">
        <header class="card-header">
            <h2 class="card-header-title">Data Pelanggan</h2>
        </header>
        <div class="card-content">
            <b-field label="Pelanggan" :type="{ 'is-danger': errors.customer.error }" :message="errors.customer.message">
                <b-autocomplete
                    placeholder="Cari Pelanggan"
                    v-model="customer"
                    :data="customers.data" field="name" :loading="searching"
                    v-on:typing="search" v-on:focus="open" v-on:select="store" v-on:infinite-scroll="loadMore"
                    open-on-focus check-infinite-scroll />
            </b-field>
            <b-field label="No Telp">
                <b-input placeholder="No Telp" v-model="customerData.phone" disabled />
            </b-field>
            <b-field label="Alamat">
                <b-input placeholder="Alamat" type="textarea" v-model="customerData.address" disabled />
            </b-field>
        </div>
    </div>
</template>

<script>
    import { mapActions } from 'vuex'

    export default {
        model: {
            prop: 'customerId',
            event: 'select'
        },
        props: ['errors', 'customerId'],
        data() {
            return {
                searching: false,
                take: 10,
                customer: '',
                customers: {},
                customerData: {}
            }
        },
        methods: {
            ...mapActions('customer', ['get']),
            async searchCustomer(name) {
                this.searching = true

                try {
                    const customers = await this.get({ take: this.take, name })

                    this.customers = customers
                } catch (err) {
                    console.log(err)
                } finally {
                    this.searching = false
                }
            },
            async search(name) {
                await this.searchCustomer(name)
            },
            async open() {
                await this.searchCustomer(this.customer)
            },
            async loadMore() {
                if (this.customers.meta.total > this.take) {
                    this.take += 5

                    await this.open()
                }
            },
            async store(customer) {
                this.customerData = customer ?? {}

                this.$emit('select', customer?.id ?? null)
            }
        }
    }
</script>