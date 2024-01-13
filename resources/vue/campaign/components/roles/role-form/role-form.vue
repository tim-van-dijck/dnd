<template>
    <div>
        <h1>{{ title }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="role != null" action="">
                    <div class="uk-margin">
                        <label for="name" class="uk-form-label">Name</label>
                        <input id="name" name="name" type="text" class="uk-input" v-model="role.name">
                    </div>
                    <h2>Permissions</h2>
                    <table class="uk-table" v-if="permissions != null">
                        <thead>
                        <tr>
                            <th>Entity</th>
                            <th>View</th>
                            <th>Create</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td></td>
                            <td>
                                <input type="checkbox" class="uk-checkbox" @change.prevent="selectAll('view')"
                                       :checked="selected.view">
                            </td>
                            <td>
                                <input type="checkbox" class="uk-checkbox" @change.prevent="selectAll('create')"
                                       :checked="selected.create">
                            </td>
                            <td>
                                <input type="checkbox" class="uk-checkbox" @change.prevent="selectAll('edit')"
                                       :checked="selected.edit">
                            </td>
                            <td>
                                <input type="checkbox" class="uk-checkbox" @change.prevent="selectAll('delete')"
                                       :checked="selected.delete">
                            </td>
                        </tr>
                        <tr v-for="permission in permissions">
                            <td style="text-transform: capitalize;">{{ permission.name }}</td>
                            <td>
                                <input type="checkbox" class="uk-checkbox"
                                       v-model="role.permissions[permission.id].view">
                            </td>
                            <td>
                                <input type="checkbox" class="uk-checkbox"
                                       v-model="role.permissions[permission.id].create">
                            </td>
                            <td>
                                <input type="checkbox" class="uk-checkbox"
                                       v-model="role.permissions[permission.id].edit">
                            </td>
                            <td>
                                <input type="checkbox" class="uk-checkbox"
                                       v-model="role.permissions[permission.id].delete">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <p v-else class="uk-text-center">
                        <i class="fas fa-2x fa-sync fa-spin"></i>
                    </p>
                    <p class="uk-margin">
                        <button class="uk-button uk-button-primary" @click.prevent="save">Save</button>
                        <router-link class="uk-button uk-button-danger" :to="{name: 'roles'}">
                            Cancel
                        </router-link>
                    </p>
                </form>
                <p v-else class="uk-text-center">
                    <i class="fas fa-2x fa-sync fa-spin"></i>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { computed, onMounted, watch } from 'vue'
import { useStore } from 'vuex'
import { useRoleFormState } from './role-form.state'

export default {
    name: 'RoleForm',
    props: ['id'],
    setup(props) {
        const store = useStore()
        const { state } = useRoleFormState(props.id)

        onMounted(() => {
            store.dispatch('Roles/loadPermissions')
                .then(() => state.loadRole())
        })

        watch(
            () => state.role,
            (role) => {
                if (role != null) {
                    for (const type in this.selected) {
                        let allSelected = true
                        for (const permission in role.permissions) {
                            if (!role.permissions[permission][type]) {
                                allSelected = false
                                break
                            }
                        }
                        this.selected[type] = allSelected
                    }
                }
            },
            { deep: true }
        )

        return {
            state,
            ui: {
                title: computed(() => props.id ? `Edit ${state.role ? state.role.name : 'role'}` : '')
            }
        }
    }
}
</script>