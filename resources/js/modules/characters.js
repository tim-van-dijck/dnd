import Vue from 'vue';

export const Characters = {
    namespaced: true,
    state: {
        characters: null,
        classes: [],

        races: {},
        player: {
            info: {},
        }
    },
    actions: {
        loadRaces({commit}) {
            return axios.get(`/races`)
                .then((response) => {
                    commit('SET_RACES', response.data)
                });
        },
        loadCharacters({commit}, type) {
            return axios.get(`/campaign/characters?filter[type]=${type}`)
                .then((response) => {
                    commit('SET_CHARACTERS', response.data)
                });
        },
        loadCharacter({commit}, id) {
            return axios.get(`/campaign/characters/${id}`)
                .then((response) => {
                    return response.data;
                });
        },
        storeCharacter({commit}, character) {
            return axios.post(`/campaign/characters`, character)
                .then((response) => {
                    commit('SET_CHARACTER', response.data);
                });
        },
        updateCharacter({commit}, data) {
            return axios.post(`/campaign/characters/${data.id}`, data.character)
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
                    Vue.set(state.races, race.id, race);
                }
            } else {
                Vue.set(state, 'races', {});
            }
        },
    }
};
