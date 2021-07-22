export default {
    methods: {
        validate(errors) {
            for (let [name, error] of Object.entries(errors)) {
                this.errors[name] = {
                    error: true,
                    message: error[0]
                }
            }
        }
    }
}