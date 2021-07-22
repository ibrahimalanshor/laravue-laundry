<template>
    <div>
        <b-notification type="is-success" v-model="alert.active">{{ alert.msg }}</b-notification>
        <div class="card">
            <header class="card-header">
                <h2 class="card-header-title">Data Pengguna</h2>
                <div class="card-header-icon">
                    <b-button type="is-link" size="is-small" v-on:click="create">Tambah Pengguna</b-button>
                </div>
            </header>
            <div class="card-content">
                <nav class="level">
                    <div class="level-left">
                        <div class="level-item">
                            <b-field label="Tampilkan" label-position="on-border">
                                <b-select placeholder="Tampilkan" size="is-small" v-model="take">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="50">50</option>
                                    <option value="">Tampilkan</option>
                                </b-select>
                            </b-field>
                        </div>
                    </div>
                    <div class="level-right">
                        <div class="level-item">
                            <b-field label="Cari" label-position="on-border">
                                <b-input placeholder="Cari Nama" size="is-small" type="search" icon="search" v-model="search"></b-input>
                            </b-field>
                        </div>
                    </div>
                </nav>

                <b-table
                    :data="users.data"
                    :loading="loading"
                    striped hoverable bordered :mobile-cards="false"
                    paginated backend-pagination pagination-simple
                    backend-sorting sort-icon="caret-up" sort-icon-size="is-small"
                    :total="users.meta ? users.meta.total : 0" :per-page="users.meta ? users.meta.per_page : 0" :current-page="page"
                    v-on:page-change="nextPage" v-on:sort="sortTable">
                        <b-table-column field="no" label="No" v-slot="props">
                            {{ props.index + 1 }}
                        </b-table-column>
                        <b-table-column field="name" label="Nama" v-slot="props" sortable>
                            {{ props.row.name }}
                        </b-table-column>
                        <b-table-column field="username" label="Username" v-slot="props" sortable>
                            {{ props.row.username }}
                        </b-table-column>
                        <b-table-column field="action" label="Action" v-slot="props">
                            <b-button type="is-primary" size="is-small" icon-left="edit" v-on:click="edit(props.row)"></b-button>
                            <b-button type="is-danger" size="is-small" icon-left="trash" v-on:click="destroy(props.row.id)"></b-button>
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
    import { CreateUser, EditUser, DeleteUser } from '../../components/user'
    import { alert, filter } from '../../mixins'

    export default {
        components: {
            CreateUser, EditUser, DeleteUser
        },
        mixins: [alert, filter],
        data() {
            return {
                users: {},
            }
        },
        methods: {
            ...mapActions('user', ['get', 'delete']),
            setData(data) {
                this.users = data
            },
            async reload(msg) {
                this.getData({
                    take: this.take,
                    sort_by: this.$route.query.sort_by,
                    order_by: this.$route.query.order_by
                })

                this.makeAlert(msg)
            },
            create() {
                this.$buefy.modal.open({
                    parent: this,
                    component: CreateUser,
                    hasModalCard: true,
                    events: { saved: this.reload }
                })
            },
            edit({ id, ...user }) {
                this.$buefy.modal.open({
                    parent: this,
                    component: EditUser,
                    hasModalCard: true,
                    events: { saved: this.reload },
                    props: { id, user }
                })
            },
            destroy(id) {
                this.$buefy.dialog.confirm({
                    title: 'Hapus',
                    message: 'Hapus Pengguna ini?',
                    cancelText: 'Batal',
                    confirmText: 'Hapus',
                    type: 'is-danger',
                    onConfirm: async () => {
                        try {
                            await this.delete(id)

                            this.reload('Pengguna dihapus')
                        } catch (err) {
                            console.log(err)
                        }
                    }
                })
            }
        },
        beforeRouteEnter(to, from, next) {
            next(async vm => {
                await vm.getData({
                    page: to.query.page,
                    search: to.query.search,
                    sort_by: to.query.sort_by,
                    take: to.query.take,
                    order_by: to.query.order_by
                })
            })
        }
    }
</script>