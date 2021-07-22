export default {
    data() {
        return {
            loading: true,
            take: this.$route.query.take,
            search: this.$route.query.search,
        }
    },
    computed: {
        page() {
            return this.$route.query.page ? +this.$route.query.page : 1
        }
    },
    watch: {
        '$route.query': async function (param) {
            await this.getData(param)
        },
        search(search) {
            this.updateData({
                search,
                take: this.take,
                sort_by: this.$route.query.sort_by,
                order_by: this.$route.query.order_by
            })
        },
        take(take) {
            this.updateData({
                take: take,
                search: this.search,
                sort_by: this.$route.query.sort_by,
                order_by: this.$route.query.order_by
            })
        }
    },
    methods: {
        async getData({ page, search, sort_by, order_by, take }) {
            this.loading = true

            try {
                const results = await this.get({ name: search, take, page, sort_by, order_by })

                this.setData(results)

                this.loading = false
            } catch (err) {
                console.log(err)
            }
        },
        nextPage(page) {
            this.updateData({
                page,
                search: this.search,
                take: this.take,
                sort_by: this.$route.query.sort_by,
                order_by: this.$route.query.order_by,
            })
        },
        sortTable(field, order) {
            this.updateData({
                search: this.search,
                take: this.take,
                sort_by: field,
                order_by: order,
            })
        },
        updateData(query) {
            this.$router.push({ query })
        }
    }
}