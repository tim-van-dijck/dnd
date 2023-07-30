import { useModals } from '../../../modals'

export const useState = (store) => {
    const { confirmDelete } = useModals()
    return (
        {
            state: {
                destroy: (race) => {
                    confirmDelete(
                        'race',
                        () => store.destroy(race.id).then(() => store.load())
                    )
                }

            }
        }
    )
}