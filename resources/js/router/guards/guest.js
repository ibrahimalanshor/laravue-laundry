import store from '../../store'

export default (to, from, next) => {
    const requireGuest = to.meta.guest
    const userLogin = store.state.auth.login

    if (requireGuest && userLogin) {
        next({ name: 'Dashboard' })
    } else {
        next()
    }
}