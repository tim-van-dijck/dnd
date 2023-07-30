import { defineStore } from 'pinia/dist/pinia.esm-browser'

export const usePermissionStore = defineStore('campaign-permissions', {
    state: () => (
        {
            permissions: {}
        }
    ),
    actions: {
        fetch({ entity, id }) {
            if (entity && id) {
                if (!this.permissions.hasOwnProperty(entity) || !this.permissions[entity].hasOwnProperty(id)) {
                    return axios.get(`/api/campaign/permissions/${entity}/${id}`)
                        .then((response) => {
                            const permissions = {}
                            for (const index in response.data) {
                                if (index > 0) {
                                    permissions[index] = response.data[index]
                                }
                            }

                            if (!this.permissions?.hasOwnProperty(entity)) {
                                this.permissions[entity] = {}
                            }

                            if (!this.permissions[entity].hasOwnProperty(id)) {
                                this.permissions[id] = {}
                            }

                            this.permissions[entity][id] = permissions
                        })
                        .catch(() => {
                        })
                }
            }
        }
    }
})