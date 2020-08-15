import Vue from 'vue';

export const Characters = {
    namespaced: true,
    state: {
        characters: null,
        classes: {},

        races: {},
        player: {
            info: {},
            classes: {},
        }
    },
    actions: {
        loadClasses({commit}) {
            return axios.get(`/classes?include=subclasses,proficiencies,subclasses.proficiencies`)
                .then((response) => {
                    commit('SET_CLASSES', response.data.data)
                });
        },
        loadRaces({commit}) {
            return axios.get(`/races?include=proficiencies,languages,subraces`)
                .then((response) => {
                    commit('SET_RACES', response.data.data)
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
        SET_CLASSES(state, classes) {
            if (classes) {
                for (let charClass of classes) {
                    Vue.set(state.classes, charClass.id, charClass);
                }
            } else {
                Vue.set(state, 'classes', {});
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
