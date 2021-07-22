import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'

import auth from './auth'
import user from './user'
import packet from './packet'
import customer from './customer'
import transaction from './transaction'

import state from './state'
import mutations from './mutations'
import actions from './actions'

Vue.use(Vuex)

export default new Vuex.Store({
    plugins: [createPersistedState()],
    state,
    mutations,
    actions,
    modules: {
        auth,
        user,
        packet,
        customer,
        transaction
    }
})