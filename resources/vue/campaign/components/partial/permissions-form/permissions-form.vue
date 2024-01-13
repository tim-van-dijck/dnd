<template>
    <div class="permissions">
        <div class="uk-margin">
            <p>Do you want to override the standard permissions per user?</p>
            <label class="uk-margin-right"><input class="uk-radio" type="radio" :value="true" v-model="state.override">
                Yes
            </label>
            <label><input class="uk-radio" type="radio" :value="false" v-model="state.override"> No</label>
        </div>
        <table class="uk-table" v-if="state.override && state.input != null && users != null">
            <thead>
            <tr>
                <th>User</th>
                <th>
                    <label>
                        <input type="checkbox" class="uk-checkbox" @change.prevent="state.selectAll('view')"
                               :checked="state.selected.view"> View
                    </label>
                </th>
                <th>
                    <label>
                        <input type="checkbox" class="uk-checkbox" @change.prevent="state.selectAll('create')"
                               :checked="state.selected.create"> Create
                    </label>
                </th>
                <th>
                    <label>
                        <input type="checkbox" class="uk-checkbox" @change.prevent="state.selectAll('edit')"
                               :checked="state.selected.edit"> Edit
                    </label>
                </th>
                <th>
                    <label>
                        <input type="checkbox" class="uk-checkbox" @change.prevent="state.selectAll('delete')"
                               :checked="state.selected.delete"> Delete
                    </label>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in users.data">
                <td style="text-transform: capitalize;">{{ user.name }}</td>
                <td>
                    <input type="checkbox" class="uk-checkbox" v-model="state.input[user.id].view">
                </td>
                <td>
                    <input type="checkbox" class="uk-checkbox" v-model="state.input[user.id].create">
                </td>
                <td>
                    <input type="checkbox" class="uk-checkbox" v-model="state.input[user.id].edit">
                </td>
                <td>
                    <input type="checkbox" class="uk-checkbox" v-model="state.input[user.id].delete">
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import { usePermissionStore } from '@campaign/stores/permissions'
import { useUserStore } from '@campaign/stores/users'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { onMounted, watch } from 'vue'
import { usePermissionsFormState } from './permissions-form.state'

export default {
    name: 'permissions-form',
    props: ['value', 'entity', 'id'],
    emits: ['input'],
    setup(props, ctx) {
        const userStore = useUserStore()
        const permissionStore = usePermissionStore()
        const { users } = storeToRefs(userStore)
        const { permissions } = storeToRefs(permissionStore)
        const state = usePermissionsFormState(props.entity, props.id)

        onMounted(() => {
            const promises = [
                permissionStore.fetch({ entity: props.entity, id: props.id }),
                userStore.load()
            ]
            Promise.all(promises)
                .then(() => {
                    state.init(users, permissions)
                })
        })

        watch(() => state.override, () => {
            ctx.emit('input', props.value ? state.input.value : {})
        })

        watch(() => state.input, () => {
            if (state.override) {
                ctx.emit('update:modelValue', state.input)
            }
        }, { deep: true })

        return { users, state }
    }
}
</script>