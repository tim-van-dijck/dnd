import {useStore} from "vuex";
import {confirmDelete} from "../../../modals";

const store = useStore();

export const state = {
    races: store.Races.state.races,
    destroy: (race) => {
        confirmDelete(
            'race',
            () => store.dispatch('Races/destroy', race).then(() => store.dispatch('Races/load'))
        )
    }
}