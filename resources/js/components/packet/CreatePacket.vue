<template>
    <form class="p-4" v-on:submit.prevent="save">
        <div class="modal-card">
            <header class="modal-card-head">
                <h3 class="modal-card-title">Tambah Paket</h3>
                <button type="button" class="delete" v-on:click="$emit('close')" />
            </header>
            <section class="modal-card-body">
                <b-field label="Nama" :type="{ 'is-danger': errors.name.error }" :message="errors.name.message">
                    <b-input placeholder="Nama" v-model="packet.name"></b-input>
                </b-field>
                <b-field label="Satuan" :type="{ 'is-danger': errors.unit.error }" :message="errors.unit.message">
                    <b-input placeholder="Satuan" v-model="packet.unit"></b-input>
                </b-field>
                <div class="field">
                    <label class="label">Harga per Kilo</label>
                    <div class="field-body">
                        <div class="field has-addons">
                            <p class="control">
                                <span class="button is-static">Rp</span>
                            </p>
                            <div class="control has-icons-right is-expanded is-clearfix">
                                <input type="text" class="input" :class="{ 'is-danger': errors.cost.error }" placeholder="Waktu Pengerjaan" v-model="packet.cost">

                                <span class="icon is-right has-text-danger" v-if="errors.cost.error">
                                    <i class="fas fa-exclamation-circle"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <p class="help is-danger" v-if="errors.cost.error">{{ errors.cost.message }}</p>
                </div>
                <div class="field">
                    <label class="label">Waktu Pengerjaan (hari)</label>
                    <div class="field-body">
                        <div class="field has-addons">
                            <div class="control has-icons-right is-expanded is-clearfix">
                                <input type="text" class="input" :class="{ 'is-danger': errors.time.error }" placeholder="Waktu Pengerjaan" v-model="packet.time">

                                <span class="icon is-right has-text-danger" v-if="errors.time.error">
                                    <i class="fas fa-exclamation-circle"></i>
                                </span>
                            </div>
                            <p class="control">
                                <span class="button is-static">Hari</span>
                            </p>
                        </div>
                    </div>
                    <p class="help is-danger" v-if="errors.time.error">{{ errors.time.message }}</p>
                </div>
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
        unit: {},
        cost: {},
        time: {}
    }

    export default {
        mixins: [validator],
        data() {
            return {
                loading: false,
                packet: {
                    name: '',
                    unit: '',
                    cost: '',
                    time: ''
                },
                errors: {...errors}
            }
        },
        computed: {
            rawCost() {
                return this.packet.cost.replace(/\D/gi, '')
            }
        },
        watch: {
            'packet.cost': function (cost) {
                const number = cost.replace(/\D/gi, '')
                
                this.packet.cost = new Intl.NumberFormat('id-ID').format(number)
            },
            'packet.time': function (time) {                
                this.packet.time = time.replace(/\D/gi, '')
            }
        },
        methods: {
            ...mapActions('packet', ['store']),
            async save() {
                this.loading = true
                this.errors = {...errors}

                try {
                    const packet = await this.store({
                        ...this.packet,
                        cost: this.rawCost
                    })

                    this.$emit('saved', 'Paket Ditambahkan')
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