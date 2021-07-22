export default {
    data() {
        return {
            alert: {
                active: false,
                msg: ''
            },
        }
    },
    methods: {
        makeAlert(msg) {
            this.alert = {
                active: true,
                msg
            }
        }
    }
}