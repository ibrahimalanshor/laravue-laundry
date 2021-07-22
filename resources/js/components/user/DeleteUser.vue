<template>
    <div class="p-4">
        <div class="modal-card">
            <header class="modal-card-head">
                <h3 class="modal-card-title">Hapus Pengguna</h3>
                <button type="button" class="delete" v-on:click="$emit('close')" />
            </header>
            <section class="modal-card-body">
                Hapus pengguna ini?
            </section>
            <footer class="modal-card-foot">
                <b-button label="Batal" v-on:click="$emit('close')" />
                <b-button label="Hapus" type="is-danger" v-on:click="destroy" :disabled="loading" />
            </footer>
        </div>
    </div>
</template>

<script>
    import { mapActions } from 'vuex'

    export default {
        props: ['id'],
        data() {
            return {
                loading: false
            }
        },
        methods: {
            ...mapActions('user', ['delete']),
            async destroy() {
                this.loading = true

                try {
                    await this.delete(this.id)

                    this.$emit('saved', 'Pengguna Dihapus')
                    this.$emit('close')
                } catch (err) {
                    console.log(err)
                } finally {
                    this.loading = false
                }
            }
        }
    }
</script>