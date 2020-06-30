export const Permissions = {
    namespaced: true,
    state: {
        permissions: {}
    },
    actions: {
        fetch: ({state, commit}, data) => {
            if (data.entity && data.id) {
                if (!state.permissions.hasOwnProperty(data.entity) || !state.permissions[data.entity].hasOwnProperty(data.id)) {
                    return axios.get(`/campaign/permissions/${data.entity}/${data.id}`)
                        .then((response) => {
                            let permissions = {};
                            for (let index in response.data) {
                                if (index > 0) {
                                    permissions[index] = response.data[index];
                                }
                            }
                            commit('SET_PERMISSIONS', {id: data.id, entity: data.entity, permissions});
                        })
                        .catch(() => {});
                }
            }
        }
    },
    mutations: {
        SET_PERMISSIONS: (state, data) => {
            if (!state.permissions.hasOwnProperty(data.entity)) {
                state.permissions[data.entity] = {};
            }
            if (!state.permissions[data.entity].hasOwnProperty(data.id)) {
                state.permissions[data.entity][data.id] = {};
            }
            state.permissions[data.entity][data.id] = data.permissions;
        }
    },
    getters: {
        permission: state => (entity, id) => {
            if (state.permissions.hasOwnProperty(entity) && state.permissions[entity].hasOwnProperty(id)) {
                return state.permissions[entity][id] || {};
            }
            return {};
        }
    }
}