import store from '../../store'

export default (to, from, next) => {
    const requireAuth = to.meta.auth
    const userLogin = store.state.auth.login

    if (requireAuth && !userLogin) {
        next({ name: 'Login' })
    } else {
        next()
    }
}