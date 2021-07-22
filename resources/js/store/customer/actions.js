export default {
    async get({}, query) {
        const customers = await axios.get('/api/customers', {
            params: query
        })

        return customers.data
    },
    async store({}, data) {
        const customer = await axios.post('/api/customers', data)

        return customer.data
    },
    async update({}, { id, data }) {
        const customer = await axios.put(`/api/customers/${id}`, data)

        return customer.data
    },
    async delete({}, id) {
        const customer = await axios.delete(`/api/customers/${id}`)

        return customer.data
    }
}