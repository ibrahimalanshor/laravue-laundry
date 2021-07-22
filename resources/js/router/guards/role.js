import store from '../../store'

export default (to, from, next) => {
    const pageRole = to.meta.role
    const protect = to.meta.protected
    const userRole = store.state.auth.user.role

    const forbidden = pageRole !== userRole

    if (protect && forbidden) {
        next({ name: 'Forbidden' })
    } else {
        next()
    }
}