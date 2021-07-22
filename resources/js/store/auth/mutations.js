export default {
    login(state, token) {
        state.login = true
        state.token = token
        state.exp = Date.now() + (1000 * 60 * 60)
    },
    setUser(state, user) {
        state.user = user
    },
    logout(state) {
        state.login = false
        state.token = null
        state.user = {}
        state.exp = 0
    }
}