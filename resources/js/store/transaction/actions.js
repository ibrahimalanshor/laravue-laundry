export default {
    async get({}, query) {
        const transaction = await axios.get('/api/transactions', {
            params: {
                ...query,
                note: query.name
            }
        })

        return transaction.data
    },
    async store({}, data) {
        const transaction = await axios.post('/api/transactions', data)

        return transaction.data
    },
    async show({}, note) {
        const transaction = await axios.get(`/api/transactions/note/${note}`)

        return transaction.data
    },
    async delete({}, id) {
        const transaction = await axios.delete(`/api/transactions/${id}`)

        return transaction.data
    },
    async updatePaymentStatus({}, id) {
        const transaction = await axios.patch(`/api/transactions/${id}/payment-status`)

        return transaction.data
    },
    async updateWorkingStatus({}, id) {
        const transaction = await axios.patch(`/api/transactions/${id}/working-status`)

        return transaction.data
    },
    async getNote() {
        const note = await axios.get('/api/transactions/note/')

        return note.data
    }
}