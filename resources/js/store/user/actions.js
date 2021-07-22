export default {
    async get({}, query) {
        const users = await axios.get('/api/users', {
            params: query
        })

        return users.data 
    },
    async store({}, data) {
        const user = await axios.post('/api/users', data)

        return user.data
    },
    async update({}, { id, data }) {
        const user = await axios.put(`/api/users/${id}`, data)

        return user.data
    },
    async delete({}, id) {
        await axios.delete(`/api/users/${id}`)
    }
}