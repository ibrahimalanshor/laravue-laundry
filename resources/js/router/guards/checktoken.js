import store from '../../store'

export default (to, from, next) => {
    const { login, exp } = store.state.auth
    const expired = Date.now() > exp

    if (login && expired) {
        store.commit('auth/logout')
        next({ name: 'Login' })
    } else {
        next()
    }

}