<template>
    <div id="users">
        <h1>Users</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <user-invite-modal @invite="() => store.load()"/>
                <paginated-table v-if="(users?.data?.length || 0) > 0"
                                 :actions="ui.actions"
                                 :columns="ui.columns"
                                 :records="users"
                                 :store="store"
                                 @destroy="state.destroy"
                                 searchable/>
            </div>
        </div>
    </div>
</template>

<script>
import { useUserStore } from '@admin/stores/users'
import PaginatedTable from '@components/partial/paginated-table'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import UserInviteModal from './components/user-invite-modal/user-invite-modal'
import { useUserOverviewState } from './user-overview.state'
import { ui } from './user-overview.ui'

export default {
    name: 'user-overview',
    components: { UserInviteModal, PaginatedTable },
    setup() {
        const router = useRouter()
        const store = useUserStore()
        const { users } = storeToRefs(store)
        const state = useUserOverviewState(store)
        onMounted(() => store.load())
        return { router, state, store, ui, users }
    }
}
</script>