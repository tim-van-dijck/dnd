import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useStore } from 'vuex'

export const useRoleFormState = (id) => {
    const router = useRouter()
    const store = useStore()

    const state = reactive({
        errors: {},
        role: null,
        selected: {
            view: false,
            create: false,
            edit: false,
            delete: false
        },
        selectAll(type) {
            this.selected[type] = !this.selected[type]
            for (const index in this.role.permissions) {
                this.role.permissions[index][type] = this.selected[type]
            }
        },
        loadRole() {
            if (id) {
                return store.dispatch('Roles/find', id)
                    .then((role) => {
                        const localRole = {
                            name: role.name,
                            permissions: formatPermissions(role.permissions)
                        }

                        this.role = { ...localRole }
                    })
            } else {
                const role = {
                    permissions: {}
                }
                for (const permission of store.state.Roles.permissions) {
                    role.permissions[permission.id] = {
                        view: false,
                        create: false,
                        edit: false,
                        delete: false
                    }
                }
                this.role = role
            }
        },
        save() {
            let promise
            const role = {
                name: this.role.name,
                permissions: formatPermissions(this.role.permissions)
            }

            if (id) {
                promise = store.dispatch('Roles/update', { id, role })
            } else {
                promise = store.dispatch('Roles/store', role)
            }
            promise
                .then(() => {
                    router.push({ name: 'roles' })
                    this.errors = {}
                })
                .catch((exception) => {
                    this.errors = exception.response.data.errors
                })
        }
    })

    return { state }
}

const formatPermissions = (permissions) => {
    const formatted = {}
    for (const index in permissions) {
        formatted.push({
            id: index,
            view: permissions[index].view,
            create: permissions[index].create,
            edit: permissions[index].edit,
            delete: permissions[index].delete
        })
    }
    return formatted
}
