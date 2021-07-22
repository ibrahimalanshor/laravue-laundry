<template>
    <div class="card">
        <header class="card-header">
            <h2 class="card-header-title">Detail Transaksi</h2>
            <div class="card-header-icon">
                <time v-if="transaction">{{ createDateParsed }}</time>
            </div>
        </header>
        <div class="card-content" v-if="transaction">
        
            <div class="columns">
                <div class="column is-6">
                    <ul>
                        <li class="mb-1">Pelanggan</li>
                        <li class="has-text-weight-bold mb-1">{{ transaction.customer.name }}</li>
                        <li class="mb-1">No Telp : {{ transaction.customer.phone }}</li>
                        <li class="mb-1">Alamat : {{ transaction.customer.address }}</li>
                    </ul>
                </div>
                <div class="column is-6 has-text-right-tablet">
                    <ul>
                        <li class="mb-1"><b>Nota</b> : {{ transaction.note }}</li>
                        <li class="mb-2"><b>Tanggal</b> : {{ createDateParsed }}</li>
                        <li class="mb-2"><b>Status Pembayaran</b> : <b-tag :type="paymentStatusBadge">{{ transaction.payment_status ? 'Sudah Bayar' : 'Belum Bayar' }}</b-tag></li>
                        <li><b>Status Pengerjaan</b> : <b-tag :type="workingStatusBadge">{{ transaction.working_status ? 'Selesai' : 'Sedang Dikerjakan' }}</b-tag></li>
                    </ul>
                </div>
            </div>

            <hr>
        
            <div class="table-container mb-5">
                <table class="table is-fullwidth is-striped is-hoverable">
                    <thead>
                        <tr>
                            <th>Paket</th>
                            <th>Harga</th>
                            <th>Berat</th>
                            <th>Estimasi Selesai</th>
                            <th align="right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ transaction.packet.name }}</td>
                            <td>{{ currency(transaction.packet.cost) }}</td>
                            <td>{{ transaction.weight }}</td>
                            <td>{{ date(transaction.finish_date) }}</td>
                            <td align="right">{{ currency(transaction.weight * transaction.packet.cost) }}</td>
                        </tr>
                        <tr>
                            <th colspan="4" align="right">
                                Diskon
                            </th>
                            <td align="right">{{ currency(transaction.discount) }}</td>
                        </tr>
                        <tr>
                            <th colspan="4" align="right">
                                Tax ({{ transaction.tax }}%)
                            </th>
                            <td align="right">{{ currency((transaction.weight * transaction.packet.cost) * transaction.tax / 100) }}</td>
                        </tr>
                        <tr>
                            <th colspan="4" align="right">
                                Total
                            </th>
                            <td align="right">{{ currency(transaction.total) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="buttons is-justify-content-flex-end">
                <b-button label="Kembali" tag="router-link" :to="{ name: 'Transaction' }" />
            </div>
        
        </div>
    </div>
</template>

<script>
    import { mapActions } from 'vuex'

    export default {
        data() {
            return {
                transaction: null
            }
        },
        computed: {
            createDateParsed() {
                return this.date(this.transaction.create_date)
            },
            paymentStatusBadge() {
                return this.transaction.payment_status ? 'is-link' : 'is-danger'
            },
            workingStatusBadge() {
                return this.transaction.working_status ? 'is-link' : 'is-primary'
            }
        },
        methods: {
            ...mapActions('transaction', ['show']),
            async setTransaction() {
                try {
                    const transaction = await this.show(this.$route.params.note)

                    this.transaction = transaction.data
                } catch (err) {
                    this.$router.push({ name: 'Not Found' })
                }
            }
        },
        async mounted() {
            await this.setTransaction()

            this.$Progress.finish()
        }
    }
</script>