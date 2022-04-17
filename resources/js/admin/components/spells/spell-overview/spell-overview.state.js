import { useStore } from "vuex";
import { confirmDelete } from "../../../modals";

const store = useStore()

export const state = {
    spells: store.Spells.state.spells,
    destroy: (spell) => {
        confirmDelete(
            'spell',
            () => store.dispatch('Spells/destroy', spell).then(() => store.dispatch('Spells/load'))
        )
    }
}
