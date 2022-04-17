import {debounce} from "lodash";
import {useStore} from "vuex";
import {useRouter} from "vue-router";

const addObjective = () => {
    this.quest.objectives.push({name: '', optional: false});
}

const removeObjective = (index) => {
    this.quest.objectives.splice(index, 1);
}

const save = (quest) => {
    const store = useStore()
    const router = useRouter()

    let promise;
    const input = {
        title: quest.title,
        description: quest.description,
        objectives: [],
        private: quest.private
    };
    if (store.getters.can('edit', 'role')) {
        input.permissions = quest.permissions || {};
    }
    if (this.quest.location_id > 0) {
        input.location_id = quest.location_id;
    }

    for (const objective of quest.objectives) {
        if (objective.title != '') {
            input.objectives.push(objective);
        }
    }
    if (this.id) {
        promise = store.dispatch('Quests/update', {quest: input, id: this.id})
    } else {
        promise = store.dispatch('Quests/store', input)
    }
    promise
        .then(() => {
            store.dispatch('Quests/load');
            router.push({name: 'quests'});
        })
        .catch((error) => {
            store.commit('Quests/SET_ERRORS', error.response.data.errors);
            store.dispatch('Messages/error', error.response.data.message, {root: true});
        });
}

const search = debounce((query, loading) => {
    return axios.get(`/campaign/locations?filter[query]=${escape(query)}&page[number]=1&page[size]=10`)
        .then((response) => {
            const locations = response.data.data.map((item) => ({value: item.id, label: item.name}));
            loading(false);
            return locations;
        });
}, 1000)

export {search, save}