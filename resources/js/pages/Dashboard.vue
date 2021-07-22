<template>
    <div class="columns is-multiline is-mobile" v-if="data">
        <div class="column is-full-mobile is-6-tablet is-3-desktop">
            <div class="box widget-primary">
                <span class="is-size-2 has-text-weight-bold">{{ data.totalPacket }}</span>
                <span class="is-size-5 is-block">Paket</span>
            </div>
        </div>
        <div class="column is-full-mobile is-6-tablet is-3-desktop">
            <div class="box widget-info">
                <span class="is-size-2 has-text-weight-bold">{{ data.totalCustomer }}</span>
                <span class="is-size-5 is-block">Pelanggan</span>
            </div>
        </div>
        <div class="column is-full-mobile is-6-tablet is-3-desktop">
            <div class="box widget-warning">
                <span class="is-size-2 has-text-weight-bold">{{ data.totalTransaction }}</span>
                <span class="is-size-5 is-block">Transaksi</span>
            </div>
        </div>
        <div class="column is-full-mobile is-6-tablet is-3-desktop">
            <div class="box widget-danger">
                <span class="is-size-2 has-text-weight-bold">{{ data.totalTransactionActive }}</span>
                <span class="is-size-5 is-block">Transaksi Aktif</span>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions } from 'vuex'

    export default {
        data() {
            return {
                data: null
            }
        },
        methods: {
            ...mapActions(['getDashboard']),
            async setData() {
                try {
                    const dashboard = await this.getDashboard()

                    this.data = dashboard.data
                } catch (err) {
                    console.log(err)
                }
            }
        },
        async created() {
            await this.setData()

            this.$Progress.finish()
        }
    }
</script>