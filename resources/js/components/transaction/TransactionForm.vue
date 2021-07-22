<template>
    <div class="card">
        <header class="card-header">
            <h2 class="card-header-title">Data Laundry</h2>
        </header>
        <div class="card-content">
            <b-field grouped group-multiline>
                <b-field label="Paket" class="mb-3" expanded :type="{ 'is-danger': errors.packet.error }" :message="errors.packet.message">
                    <b-autocomplete
                        placeholder="Pilih Paket"
                        v-model="packet"
                        :data="packets.data" field="name" :loading="searching"
                        v-on:typing="search" v-on:focus="open" v-on:select="storePacket" v-on:infinite-scroll="loadMore"
                        open-on-focus check-infinite-scroll />
                </b-field>
                <b-field label="Harga" class="mb-3" expanded>
                    <b-input placeholder="Harga" :value="currency(packetData.cost ? packetData.cost : 0)" disabled />
                </b-field>
            </b-field>
            <b-field grouped group-multiline>
                <div class="field is-expanded mb-3">
                    <label class="label">Berat (kg)</label>

                    <div class="control" :class="{ 'has-icons-right': errors.weight.error }">
                        <input type="text" class="input" :class="{ 'is-danger': errors.weight.error }" placeholder="Berat" v-model="weight">

                        <span class="icon is-right has-text-danger" v-if="errors.weight.error">
                            <i class="fas fa-exclamation-circle"></i>
                        </span>
                    </div>

                    <p class="help is-danger" v-if="errors.weight.error">{{ errors.weight.message }}</p>
                </div>
                <b-field label="Subtotal" class="mb-3" expanded>
                    <b-input placeholder="Subtotal" :value="currency(subtotal)" disabled />
                </b-field>
            </b-field>
            <b-field grouped group-multiline>
                <b-field label="Tanggal Pengerjaan" class="mb-3" expanded>
                    <b-datepicker
                        placeholder="Tanggal Pengerjaan"
                        :min-date="minDate"
                        v-model="create_date"
                        v-on:input="storeFinishDate" />
                </b-field>
                <b-field label="Estimasi Selesai" class="mb-5" expanded>
                    <b-input placeholder="Estimasi Selesai" :value="finish_date.toLocaleDateString()" disabled />
                </b-field>
            </b-field>
        </div>
    </div>
</template>

<script>
    import { mapActions } from 'vuex'

    export default {
        model: {
            prop: 'subtotalRoot',
            event: 'change'
        },
        props: ['errors', 'subtotalRoot'],
        data() {
            return {
                searching: false,
                create_date: new Date(),
                finish_date: new Date(),
                minDate: new Date(new Date().valueOf() - 1000 * 60 * 60 * 24),
                take: 10,
                weight: '',
                subtotal: 0,
                packet: '',
                packets: {},
                packetData: {}
            }
        },
        watch: {
            weight(weight) {
                const number = weight.replace(/\D/gi, '')
                
                this.weight = number
               
                this.$emit('store-weight', number)
                this.storeSubtotal()
            },
            packetData(packet) {
                this.storeSubtotal()
                this.storeFinishDate(this.create_date)
            }
        },
        methods: {
            ...mapActions('packet', ['get']),
            async searchPacket(name) {
                this.searching = true

                try {
                    const packets = await this.get({ take: this.take, name })

                    this.packets = packets
                } catch (err) {
                    console.log(err)
                } finally {
                    this.searching = false
                }
            },
            async search(name) {
                await this.searchPacket(name)
            },
            async open() {
                await this.searchPacket(this.packet)
            },
            async loadMore() {
                if (this.packets.meta.total > this.take) {
                    this.take += 5

                    await this.open()
                }
            },
            storeFinishDate(create_date) {
                const time = this.packetData.time ?? 0
                const end = 1000 * 60 * 60 * 24 * time

                const finish_date = new Date(this.create_date.valueOf() + end)

                this.finish_date = finish_date

                this.$emit('store-date', create_date, finish_date)
            },
            storePacket(packet) {
                this.packetData = packet ?? {}

                this.$emit('store-packet', packet?.id ?? null)
            },
            storeSubtotal() {
                const cost = this.packetData.cost ?? 0
                const weight = this.weight ?? 0

                const subtotal = cost * weight
                
                this.subtotal = subtotal

                this.$emit('change', subtotal)
            }
        }
    }
</script>