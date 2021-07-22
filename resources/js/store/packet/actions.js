export default {
    async get({}, query) {
        const packets = await axios.get('/api/packets', {
            params: query
        })

        return packets.data
    },
    async store({}, data) {
        const packet = await axios.post('/api/packets', data)

        return packet.data
    },
    async update({}, { id, data }) {
        const packet = await axios.put(`/api/packets/${id}`, data)

        return packet.data
    },
    async delete({}, id) {
        const packet = await axios.delete(`/api/packets/${id}`)

        return packet.data
    }
}