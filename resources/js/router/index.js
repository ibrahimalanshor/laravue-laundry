import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import { Dashboard, Forbidden, NotFound, Profile } from '../pages'
import { Login } from '../pages/auth'
import { User, Setting } from '../pages/admin'
import { Packet, Customer } from '../pages/operator'
import { DataTransaction, CreateTransaction, DetailTransaction } from '../pages/operator/transaction'

import { auth, guest, role, checktoken } from './guards'

const routes = [
    {
        path: '/',
        name: 'Dashboard',
        component: Dashboard,
        meta: {
            auth: true,
            title: 'Dashboard',
            breadcrumbs: [
                {
                    name: 'Dashboard'
                }
            ]
        }
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: {
            guest: true
        }
    },
    {
        path: '/forbidden',
        name: 'Forbidden',
        component: Forbidden,
        meta: {
            auth: true,
            title: 'Forbidden'
        }
    },
    {
        path: '/404',
        name: 'Not Found',
        component: NotFound,
        meta: {
            auth: true,
            title: 'Not Found'
        }
    },
    {
        path: '/profile',
        name: 'Profile',
        component: Profile,
        meta: {
            auth: true,
            title: 'Edit Profil'
        }
    },
    {
        path: '/users',
        name: 'User',
        component: User,
        meta: {
            auth: true,
            protected: true,
            role: 'admin',
            title: 'Pengguna',
            breadcrumbs: [
                {
                    name: 'Dashboard',
                },
                {
                    name: 'User'
                }
            ]
        }
    },
    {
        path: '/setting',
        name: 'Setting',
        component: Setting,
        meta: {
            auth: true,
            protected: true,
            role: 'admin',
            title: 'Pengaturan',
            breadcrumbs: [
                {
                    name: 'Dashboard',
                },
                {
                    name: 'Setting'
                }
            ]
        }
    },
    {
        path: '/packets',
        name: 'Packet',
        component: Packet,
        meta: {
            auth: true,
            protected: true,
            role: 'operator',
            title: 'Paket',
            breadcrumbs: [
                {
                    name: 'Dashboard',
                },
                {
                    name: 'Packet'
                }
            ]
        }
    },
    {
        path: '/customers',
        name: 'Customer',
        component: Customer,
        meta: {
            auth: true,
            protected: true,
            role: 'operator',
            title: 'Pelanggan',
            breadcrumbs: [
                {
                    name: 'Dashboard',
                },
                {
                    name: 'Customer'
                }
            ]
        }
    },
    {
        path: '/transactions',
        name: 'Transaction',
        component: DataTransaction,
        meta: {
            auth: true,
            protected: true,
            role: 'operator',
            title: 'Data Transaksi',
            breadcrumbs: [
                {
                    name: 'Dashboard',
                },
                {
                    name: 'Transaction'
                }
            ]
        }
    },
    {
        path: '/transactions/create',
        name: 'Create Transaction',
        component: CreateTransaction,
        meta: {
            auth: true,
            protected: true,
            role: 'operator',
            title: 'Transaksi Baru',
            breadcrumbs: [
                {
                    name: 'Dashboard',
                },
                {
                    name: 'Transaction'
                },
                {
                    name: 'Create Transaction'
                }
            ]
        }
    },
    {
        path: '/transactions/:note',
        name: 'Detail Transaction',
        component: DetailTransaction,
        meta: {
            auth: true,
            protected: true,
            role: 'operator',
            title: 'Detail Transaksi',
            breadcrumbs: [
                {
                    name: 'Dashboard',
                },
                {
                    name: 'Transaction'
                },
                {
                    name: 'Detail Transaction'
                }
            ]
        }
    }
]

const router = new VueRouter({
    routes,
    mode: 'history'
})

router.beforeEach(auth)
router.beforeEach(guest)
router.beforeEach(role)
router.beforeEach(checktoken)

export default router