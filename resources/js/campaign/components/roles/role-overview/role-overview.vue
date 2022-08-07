<template>
    <div id="roles">
        <h1>Roles</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <router-link class="uk-button uk-button-primary" :to="{name: 'role-create'}">
                    <i class="fas fa-plus"></i> Add role
                </router-link>
                <table class="uk-table uk-table-divider" v-if="roles != null && roles.data.length > 0">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="role in state.roles.data">
                        <td class="uk-width-small">
                            <ul v-if="!role.system" class="uk-iconnav">
                                <li>
                                    <a href="/" class="uk-text-danger" @click.prevent="state.destroy(role)">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </li>
                                <li>
                                    <router-link :to="{name: 'role-edit', params: {id: role.id}}">
                                        <i class="fas fa-edit"></i>
                                    </router-link>
                                </li>
                            </ul>
                        </td>
                        <td>{{ role.name }}</td>
                    </tr>
                    </tbody>
                </table>
                <p v-else class="uk-text-center">
                    <i v-if="roles == null" class="fas fa-sync fa-spin fa-2x"></i>
                    <span v-else>
                        No roles found
                    </span>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted } from 'vue'
import { mapState, useStore } from 'vuex'
import { useRoleOverviewState } from './role-overview.state'

export default {
    name: 'role-overview',
    setup() {
        const store = useStore()
        onMounted(() => store.dispatch('Roles/load'))

        const { state } = useRoleOverviewState(store)

        return { state }
    },
    methods: {},
    computed: {
        ...mapState('Roles', ['roles'])
    }
}
</script>