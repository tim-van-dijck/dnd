import {generatePaginatedActions} from "../../generators/StateActionGenerator";

export const Races  = {
    namespaced: true,
    state: {
        races: null
    },
    actions: {
        ...generatePaginatedActions('/admin/races', 'races'),

        find({state}, id) {
            if ((state?.races?.data || []).length > 0) {
                const race = state.races.data.find((item) => item.id === id);
                if (race) {
                    return Promise.resolve(race);
                }
            }
            return axios.get(`/admin/races/${id}`)
                .then((response) => {
                    return response.data;
                });
        },
    }
}