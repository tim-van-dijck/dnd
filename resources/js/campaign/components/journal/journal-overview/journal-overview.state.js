import { useModals } from '../../../../admin/modals'

export const useState = (store) => {
    const { confirmDelete } = useModals()
    return (
        {
            state: {
                destroy: (journal) => {
                    confirmDelete(
                        'journal entry',
                        () => store.destroy(journal.id).then(() => store.load())
                    )
                }
            }
        }
    )
}