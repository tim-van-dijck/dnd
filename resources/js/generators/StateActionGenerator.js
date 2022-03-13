import Vue from "vue";

export const generatePaginatedActions = (url, collection) => {
    return {
        previous({state}) {
            if (state[collection] != null && state[collection].meta.current_page > 1) {
                axios.get(`${url}?page[number]=${state[collection].meta.current_page - 1}`)
                    .then((response) => {
                        state[collection] = response.data;
                    })
            }
        },
        page({state}, number) {
            if (state[collection] != null && number > 0 && number <= state[collection].meta.last_page)
                axios.get(`${url}?page[number]=${number}`)
                    .then((response) => {
                        state[collection] = response.data;
                    })
        },
        next({state}) {
            if (state[collection] != null && state[collection].meta.current_page < state[collection].meta.last_page) {
                axios.get(`${url}?page[number]=${state[collection].meta.current_page + 1}`)
                    .then((response) => {
                        state[collection] = response.data;
                    })
            }
        },
        load({state}, filters) {
            const params = {};
            for (let key in filters || {}) {
                params[`filters[${key}]`] = filters[key];
            }
            const query = new URLSearchParams(params)
            return axios.get(`${url}?${query}`)
                .then((response) => {
                    Vue.set(state, collection, response.data)
                });
        }
    }
}