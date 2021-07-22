<template>
    <div>
        <b-notification type="is-success" v-model="alert.active">{{ alert.msg }}</b-notification>
        <div class="card">
            <header class="card-header">
                <h2 class="card-header-title">Data Transaksi</h2>
                <div class="card-header-icon">
                    <b-button type="is-link" size="is-small" tag="router-link" :to="{ name: 'Create Transaction' }">Transaksi Baru</b-button>
                </div>
            </header>
            <div class="card-content">
                <nav class="level">
                    <div class="level-left">
                        <div class="level-item">
                            <b-field grouped>
                                <b-field label="Tanggal" label-position="on-border">
                                    <b-datepicker placeholder="Tanggal" size="is-small" v-model="dates" v-on:input="filterDate" range />
                                </b-field>
                                <b-field label="Status Pembayaran" label-position="on-border">
                                    <b-select placeholder="Status Pembayaran" size="is-small" v-model="payment_status">
                                        <option value="1">Sudah Bayar</option>
                                        <option value="0">Belum Bayar</option>
                                        <option value="">Semua</option>
                                    </b-select>
                                </b-field>
                                <b-field label="Status Pengerjaan" label-position="on-border">
                                    <b-select placeholder="Status Pengerjaan" size="is-small" v-model="working_status">
                                        <option value="1">Selesai</option>
                                        <option value="0">Sedang Dikerjakan</option>
                                        <option value="">Semua</option>
                                    </b-select>
                                </b-field>
                            </b-field>
                        </div>
                    </div>
                    <div class="level-right">
                        <div class="level-item">
                            <b-field grouped>
                                <b-field label="Tampilkan" label-position="on-border">
                                    <b-select placeholder="Tampilkan" size="is-small" v-model="take">
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="50">50</option>
                                        <option value="">Tampilkan</option>
                                    </b-select>
                                </b-field>
                                <b-field label="Cari" label-position="on-border">
                                    <b-input placeholder="Cari Nama" size="is-small" type="search" icon="search" v-model="search"></b-input>
                                </b-field>
                            </b-field>
                        </div>
                    </div>
                </nav>

                <b-table
                    :data="transactions.data"
                    :loading="loading"
                    striped hoverable bordered :mobile-cards="false"
                    paginated backend-pagination pagination-simple
                    backend-sorting sort-icon="caret-up" sort-icon-size="is-small"
                    :total="transactions.meta ? transactions.meta.total : 0" :per-page="transactions.meta ? transactions.meta.per_page : 0" :current-page="page"
                    v-on:page-change="nextPage" v-on:sort="sortTable">
                        <b-table-column field="no" label="No" v-slot="props">
                            {{ props.index + 1 }}
                        </b-table-column>
                        <b-table-column field="note" label="Nota" v-slot="props" sortable>
                            {{ props.row.note }}
                        </b-table-column>
                        <b-table-column field="packet.name" label="Paket" v-slot="props">
                            {{ props.row.packet.name }}
                        </b-table-column>
                        <b-table-column field="weight" label="Berat" v-slot="props" sortable>
                            {{ props.row.weight }} kg
                        </b-table-column>
                        <b-table-column field="working_status" label="Status" v-slot="props" sortable>
                            <b-tag :type="workingStatusBadge(props.row.working_status)">{{ props.row.working_status ? 'Selesai' : 'Sedang Dikerjakan' }}</b-tag>
                        </b-table-column>
                        <b-table-column field="total" label="Total Bayar" v-slot="props" sortable>
                            {{ currency(props.row.total) }}
                        </b-table-column>
                        <b-table-column field="action" label="Action" v-slot="props">
                            <b-dropdown>
                                <template #trigger="{ active }">
                                    <b-button label="Aksi" type="is-link" size="is-small" :icon-right="active ? 'caret-up' : 'caret-down'" />
                                </template>

                                <b-dropdown-item v-on:click="updatePayment(props.row.id)">Update Status Pembayaran</b-dropdown-item>
                                <b-dropdown-item v-on:click="updateWorking(props.row.id)">Update Status Pengerjaan</b-dropdown-item>
                                <b-dropdown-item has-link><router-link :to="{ name: 'Detail Transaction', params: { note: props.row.note } }">Detail Transaksi</router-link></b-dropdown-item>
                                <b-dropdown-item class="has-text-danger" v-on:click="destroy(props.row.id)">Hapus</b-dropdown-item>
                            </b-dropdown>
                        </b-table-column>

                        <template #empty>
                            <div class="has-text-centered">
                                Empty
                            </div>
                        </template>
                </b-table>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions } from 'vuex'
    import { alert, filter } from '../../../mixins'

    export default {
        mixins: [alert],
        data() {
            return {
                transactions: {},
                dates: [],
                loading: true,
                take: this.$route.query.take,
                search: this.$route.query.search,
                working_status: this.$route.query.working_status,
                payment_status: this.$route.query.payment_status
            }
        },
        computed: {
            page() {
                return this.$route.query.page ? +this.$route.query.page : 1
            }
        },
        watch: {
            '$route.query': async function (param) {
                await this.getData(param)
            },
            search(search) {
                this.updateData({
                    search,
                    take: this.take,
                    sort_by: this.$route.query.sort_by,
                    order_by: this.$route.query.order_by,
                    from: this.$route.query.from,
                    to: this.$route.query.to,
                    working_status: this.$route.query.working_status,
                    payment_status: this.$route.query.payment_status
                })
            },
            take(take) {
                this.updateData({
                    take: take,
                    search: this.search,
                    sort_by: this.$route.query.sort_by,
                    order_by: this.$route.query.order_by,
                    from: this.$route.query.from,
                    to: this.$route.query.to,
                    working_status: this.$route.query.working_status,
                    payment_status: this.$route.query.payment_status
                })
            },
            working_status(working_status) {
                this.updateData({
                    take: this.take,
                    search: this.search,
                    sort_by: this.$route.query.sort_by,
                    order_by: this.$route.query.order_by,
                    from: this.$route.query.from,
                    to: this.$route.query.to,
                    working_status: working_status,
                    payment_status: this.$route.query.payment_status
                })
            },
            payment_status(payment_status) {
                this.updateData({
                    take: this.take,
                    search: this.search,
                    sort_by: this.$route.query.sort_by,
                    order_by: this.$route.query.order_by,
                    from: this.$route.query.from,
                    to: this.$route.query.to,
                    working_status: this.$route.query.working_status,
                    payment_status: payment_status
                })
            }
        },
        methods: {
            ...mapActions('transaction', ['get', 'delete', 'updatePaymentStatus', 'updateWorkingStatus']),
            setData(data) {
                this.transactions = data
            },
            async getData({ page, search, sort_by, order_by, from, to, take, payment_status, working_status }) {
                this.loading = true

                try {
                    const results = await this.get({ name: search, take, page, sort_by, order_by, from, to, payment_status, working_status })

                    this.setData(results)

                    this.loading = false
                } catch (err) {
                    console.log(err)
                }
            },
            async reload(msg) {
                this.getData({
                    take: this.take,
                    sort_by: this.$route.query.sort_by,
                    order_by: this.$route.query.order_by
                })

                this.makeAlert(msg)
            },
            destroy(id) {
                this.$buefy.dialog.confirm({
                    title: 'Hapus',
                    message: 'Hapus Transaksi ini?',
                    cancelText: 'Batal',
                    confirmText: 'Hapus',
                    type: 'is-danger',
                    onConfirm: async () => {
                        try {
                            await this.delete(id)

                            this.reload('Transaksi dihapus')
                        } catch (err) {
                            console.log(err)
                        }
                    }
                })
            },
            updatePayment(id) {
                this.$buefy.dialog.confirm({
                    title: 'Update Pembayaran',
                    message: 'Update status pembayaran transaksi ini?',
                    cancelText: 'Batal',
                    confirmText: 'Update',
                    type: 'is-danger',
                    onConfirm: async () => {
                        try {
                            await this.updatePaymentStatus(id)

                            this.reload('Transaksi diperbarui')
                        } catch (err) {
                            console.log(err)
                        }
                    }
                })
            },
            updateWorking(id) {
                this.$buefy.dialog.confirm({
                    title: 'Update Pengerjaan',
                    message: 'Update status pengerjaan transaksi ini?',
                    cancelText: 'Batal',
                    confirmText: 'Update',
                    type: 'is-danger',
                    onConfirm: async () => {
                        try {
                            await this.updateWorkingStatus(id)

                            this.reload('Transaksi diperbarui')
                        } catch (err) {
                            console.log(err)
                        }
                    }
                })
            },
            filterDate(dates) {
                const [from, to] = dates

                this.updateData({
                    search: this.search,
                    take: this.take,
                    sort_by: this.$route.query.sort_by,
                    order_by: this.$route.query.order_by,
                    payment_status: this.$route.query.payment_status,
                    working_status: this.$route.query.working_status,
                    from: from.toISOString().substr(0, from.toISOString().indexOf('T')),
                    to: to.toISOString().substr(0, to.toISOString().indexOf('T'))
                })
            },
            nextPage(page) {
                this.updateData({
                    page,
                    search: this.search,
                    take: this.take,
                    sort_by: this.$route.query.sort_by,
                    order_by: this.$route.query.order_by,
                    payment_status: this.$route.query.payment_status,
                    working_status: this.$route.query.working_status,
                    from: this.$route.query.from,
                    to: this.$route.query.to
                })
            },
            sortTable(field, order) {
                this.updateData({
                    search: this.search,
                    take: this.take,
                    sort_by: field,
                    order_by: order,
                    from: this.$route.query.from,
                    to: this.$route.query.to,
                    working_status: this.$route.query.working_status,
                    payment_status: this.$route.query.payment_status
                })
            },
            updateData(query) {
                this.$router.push({ query })
            },
            workingStatusBadge(working_status) {
                return working_status ? 'is-primary' : 'is-warning'
            }
        },
        beforeRouteEnter(to, from, next) {
            next(async vm => {
                await vm.getData({
                    page: to.query.page,
                    search: to.query.search,
                    sort_by: to.query.sort_by,
                    take: to.query.take,
                    order_by: to.query.order_by,
                    from: to.query.from,
                    to: to.query.to,
                    working_status: to.query.working_status,
                    payment_status: to.query.payment_status
                })
            })
        },
        created() {
            if (this.$route.query.from) {
                const date = new Date(this.$route.query.from)

                if (!isNaN(date.valueOf())) {
                    this.dates[0] = date
                }
            }
            if (this.$route.query.to) {
                const date = new Date(this.$route.query.to)
                
                if (!isNaN(date.valueOf())) {
                    this.dates[1] = date
                }
            }
        }
    }
</script>