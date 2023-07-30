import { useModals } from '../../../modals'

export const useState = (store) => {
    const { confirmDelete } = useModals()
    return (
        {
            state: {
                destroy: (spell) => {
                    confirmDelete(
                        'spell',
                        () => store.destroy(spell.id).then(() => store.load())
                    )
                }
            }
        }
    )
}
