import { useModals } from '../../../modals'

export const useUserOverviewState = (store) => {
    const { confirmDelete } = useModals()
    return {
        destroy: (user) => {
            confirmDelete(
                'user',
                () => store.destroy(user.id).then(() => store.load())
            )
        }
    }
}
