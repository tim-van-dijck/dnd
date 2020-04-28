export const Characters = {
    namespaced: true,
    state: {
        characters: null,
        races: {}
    },
    actions: {
        loadRaces({commit}) {
            return axios.get(`/races`)
                .then((response) => {
                    commit('SET_RACES', response.data)
                });
        },
        loadCharacters({commit}, data) {
            return axios.get(`/campaign/${data.campaign_id}/characters?filter[type]=${data.type}`)
                .then((response) => {
                    commit('SET_CHARACTERS', response.data)
                });
        },
        loadCharacter({commit}, data) {
            return axios.get(`/campaign/${data.campaign_id}/characters/${data.id}`)
                .then((response) => {
                    return response.data;
                });
        },
        storeCharacter({commit}, data) {
            return axios.post(`/campaign/${data.campaign_id}/characters`, data.character)
                .then((response) => {
                    commit('SET_CHARACTER', response.data);
                });
        },
        updateCharacter({commit}, data) {
            return axios.post('/characters', data)
                .then((response) => {
                    commit('SET_CHARACTER', response.data)
                });
        }
    },
    mutations: {
        SET_CHARACTERS(state, characters) {
            state.characters = characters || null;
        },
        SET_CHARACTER(state, character) {
            let characterIndex = state.characters.findIndex((item) => {
                return item.id == character.id;
            });
            if (characterIndex >= 0) {
                state.characters[characterIndex] = character;
            } else {
                state.characters.push(character);
            }
        },
        SET_RACES(state, races) {
            if (races) {
                for (let race of races) {
                    state.races[race.id] = race;
                }
            } else {
                state.races = {};
            }
        },
    }
};
