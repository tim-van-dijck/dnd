import { useModals } from '../../../../admin/modals'

export const useQuestOverviewState = (store) => {
    const { confirmDelete } = useModals()
    return (
        {
            destroy: (quest) => {
                confirmDelete(
                    'quest',
                    () => store.destroy(quest.id).then(() => store.load())
                )
            }
        }
    )
}