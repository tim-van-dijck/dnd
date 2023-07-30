import { useModals } from '../../../../admin/modals'

export const useCharacterOverviewState = (store) => {
    const { confirmDelete } = useModals()
    return (
        {
            destroy: (character) => {
                confirmDelete(
                    'character',
                    () => store.destroy(character.id).then(() => store.load())
                )
            }
        }
    )
}