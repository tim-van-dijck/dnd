export const generatePaginatedActions = (url, collection) => {
    return {
        previous() {
            if (collection != null && collection.meta.current_page > 1) {
                axios.get(`${url}?page[number]=${collection.meta.current_page - 1}`)
                    .then((response) => {
                        collection = response.data
                    })
            }
        },
        page(number) {
            if (collection != null && number > 0 && number <= collection.meta.last_page) {
                axios.get(`${url}?page[number]=${number}`)
                    .then((response) => {
                        collection = response.data
                    })
            }
        },
        next() {
            if (collection != null && collection.meta.current_page < collection.meta.last_page) {
                axios.get(`${url}?page[number]=${collection.meta.current_page + 1}`)
                    .then((response) => {
                        collection = response.data
                    })
            }
        },
        load(filters) {
            const params = {}
            for (let key in filters || {}) {
                params[`filters[${key}]`] = filters[key]
            }
            const query = new URLSearchParams(params)
            return axios.get(`${url}?${query}`)
                .then((response) => {
                    collection = response.data
                })
        }
    }
}