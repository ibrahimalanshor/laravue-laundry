<template>
    <div>
        <b-navbar type="is-link" wrapper-class="container">
            <template #brand>
                <b-navbar-item tag="router-link" :to="{ name: 'Dashboard' }" class="has-text-weight-bold">{{ app.name }}</b-navbar-item>
            </template>
            <template #start v-if="user.role === 'operator'">
                <b-navbar-item tag="router-link" :to="{ name: 'Packet' }">Paket</b-navbar-item>
                <b-navbar-item tag="router-link" :to="{ name: 'Customer' }">Pelanggan</b-navbar-item>
                <b-navbar-dropdown label="Transaksi" collapsible>
                    <b-navbar-item tag="router-link" :to="{ name: 'Transaction' }">Data Transaksi</b-navbar-item>
                    <b-navbar-item tag="router-link" :to="{ name: 'Create Transaction' }">Transaksi Baru</b-navbar-item>
                </b-navbar-dropdown>
            </template>
            <template #start v-else>
                <b-navbar-item tag="router-link" :to="{ name: 'User' }">Pengguna</b-navbar-item>
                <b-navbar-item tag="router-link" :to="{ name: 'Setting' }">Pengaturan</b-navbar-item>
            </template>
            <template #end>
                <b-navbar-dropdown :label="user.name" collapsible>
                    <b-navbar-item href="#">
                        {{ user.name }} <br>
                        role : {{ user.role }}
                    </b-navbar-item>
                    <hr class="dropdown-divider">
                    <b-navbar-item tag="router-link" :to="{ name: 'Profile' }">Profile</b-navbar-item>
                    <b-navbar-item tag="router-link" :to="{ name: 'Setting' }" v-if="user.role === 'admin'">Pengaturan</b-navbar-item>
                    <b-navbar-item class="has-text-danger" v-on:click="processLogout">Logout</b-navbar-item>
                </b-navbar-dropdown>
            </template>
        </b-navbar>
    </div>
</template>

<script>
    import { mapState, mapMutations } from 'vuex'

    export default {
        computed: {
            ...mapState('auth', ['user']),
            ...mapState(['app']),
        },
        methods: {
            ...mapMutations('auth', ['logout']),
            processLogout() {
                this.$buefy.dialog.confirm({
                    title: 'Logout',
                    message: 'Logout dari aplikasi?',
                    confirmText: 'Logout',
                    cancelText: 'Batal',
                    type: 'is-danger',
                    onConfirm: () => {
                        this.logout()

                        this.$router.push({ name: 'Login' })
                    }
                })
            }
        }
    }
</script>