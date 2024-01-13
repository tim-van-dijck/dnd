import { useModals } from '../../../../admin/modals'

export const useRoleOverviewState = (store) => {
    const { confirmDelete } = useModals()
    const state = {
        roles: store.Roles.state.roles,
        destroy(role) {
            confirmDelete('role', () => {
                store.dispatch('Roles/destroy', role).then(() => store.dispatch('Roles/load'))
            })
        }
    }
    return { state }
}