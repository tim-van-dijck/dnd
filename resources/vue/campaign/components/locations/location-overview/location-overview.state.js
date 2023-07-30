import { useModals } from '../../../../admin/modals'

export const useLocationOverviewState = (store) => {
    const { confirmDelete } = useModals()
    return (
        {
            destroy: (location) => {
                confirmDelete(
                    'location',
                    () => store.destroy(location.id).then(() => store.load())
                )
            }
        }
    )
}