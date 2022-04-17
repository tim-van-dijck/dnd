export const Permissions = {
    namespaced: true,
    state: {
        permissions: {}
    },
    actions: {
        fetch: ({state, commit}, {entity, id}) => {
            if (entity && id) {
                if (!state.permissions.hasOwnProperty(entity) || !state.permissions[entity].hasOwnProperty(id)) {
                    return axios.get(`/campaign/permissions/${entity}/${id}`)
                        .then((response) => {
                            const permissions = {};
                            for (const index in response.data) {
                                if (index > 0) {
                                    permissions[index] = response.data[index];
                                }
                            }
                            commit('SET_PERMISSIONS', {id, entity, permissions});
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