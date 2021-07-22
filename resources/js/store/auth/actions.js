export default {
    async login({ commit, dispatch }, user) {
        const token = await dispatch('getToken', user)
        
        await dispatch('getUser', token)
        await dispatch('getSetting', token, { root: true })

        window.axios.defaults.headers.common['Authorization'] = token
    },
    async getToken({ commit }, credentials) {
        const res = await axios.post('/api/login', credentials)
        const { token_type, access_token } = res.data

        const token = `${token_type} ${access_token}`

        commit('login', token)

        return token
    },
    async getUser({ commit }, token) {
        const res = await axios.get('/api/user', {
            headers: {
                'Authorization': token
            }
        })
        const user = res.data

        commit('setUser', user)
    },
    async updateProfile({ commit }, data) {
        const user = await axios.put('/api/profile', data)

        commit('setUser', user.data.data)
    }
}