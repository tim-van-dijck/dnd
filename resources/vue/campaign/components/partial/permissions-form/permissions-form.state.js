import { reactive } from 'vue'

export const usePermissionsFormState = (entity, id) => {
    return reactive({
        override: false,
        input: {},
        selected: {
            view: false,
            create: false,
            edit: false,
            delete: false
        },
        setInput(permissions) {
            this.input = permissions
        },
        setUserInput(userId, permissions) {
            this.input[userId] = permissions
        },
        selectAll(type) {
            this.selected[type] = !this.selected[type]
            for (const index in this.input) {
                this.input[index][type] = this.selected[type]
            }
        },

        init(users, permissions) {
            const permission = () => {
                if (permissions.hasOwnProperty(entity) && this.input[entity].hasOwnProperty(id)) {
                    return this.input[entity][id] || {}
                }
                return {}
            }

            this.setInput(permission())
            this.override = Object.keys(permission()).length > 0

            for (const user of users.value.data) {
                if (!this.input.hasOwnProperty(user.id)) {
                    this.setUserInput(user.id, {
                        view: false,
                        edit: false,
                        create: false,
                        delete: false
                    })
                }
            }
        }
    })
}