export default {
    async getSetting({ commit }, token) {
        const setting = await axios.get('/api/setting', {
            headers: {
                'Authorization': token
            }
        })

        const { name, address } = setting.data.data

        commit('setApp', { name, address })
    },
    async updateSetting({ commit }, data) {
        const setting = await axios.put('/api/setting', data)

        const { name, address } = setting.data.data
        
        commit('setApp', { name, address})
    },
    async getDashboard() {
        const dashboard = await axios.get('/api/dashboard')

        return dashboard.data
    }
}