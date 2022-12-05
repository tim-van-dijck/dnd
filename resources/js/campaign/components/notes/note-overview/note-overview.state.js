import { useModals } from '../../../../admin/modals'

export const useNoteOverviewState = (store) => {
    const { confirmDelete } = useModals()
    return (
        {
            destroy: (note) => {
                confirmDelete(
                    'note',
                    () => store.destroy(note.id).then(() => store.load())
                )
            }
        }
    )
}