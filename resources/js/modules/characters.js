import Vue from 'vue';

export const Characters = {
    namespaced: true,
    state: {
        backgrounds: [],
        characters: null,
        classes: {},
        errors: {},

        races: {},
        player: {
            info: {},
            classes: {},
        }
    },
    actions: {
        loadBackgrounds({commit}) {
            axios('/backgrounds?include=proficiencies,languages')
                .then((response) => {
                    commit('SET_BACKGROUNDS', response.data.data || []);
                });
        },
        loadClasses({commit}) {
            return axios.get(`/classes?include=subclasses,proficiencies,subclasses.proficiencies,spells,subclasses.spells`)
                .then((response) => {
                    commit('SET_CLASSES', response.data.data)
                });
        },
        loadRaces({commit}) {
            return axios.get(`/races?include=proficiencies,languages,abilities,subraces,subraces.abilities,traits,subraces.traits`)
                .then((response) => {
                    commit('SET_RACES', response.data.data)
                });
        },

        load({commit}, type) {
            return axios.get(`/campaign/characters?filter[type]=${type}&includes=classes,race,subrace`)
                .then((response) => {
                    commit('SET_CHARACTERS', response.data)
                });
        },
        find({commit}, id) {
            return axios.get(`/campaign/characters/${id}?includes=classes,race,subrace,proficiencies,languages`)
                .then((response) => {
                    return response.data.data;
                });
        },
        store({commit, dispatch}, character) {
            return axios.post(`/campaign/characters`, character)
                .then((response) => {
                    commit('SET_CHARACTER', response.data);
                    commit('SET_ERRORS', {});
                    dispatch('Messages/success', 'Character saved!', {root: true});
                })
                .catch((error) => {
                    commit('SET_ERRORS', error.response.data.errors);
                    dispatch('Messages/error', error.response.data.message, {root: true});
                });
        },
        update({commit}, data) {
            return axios.post(`/campaign/characters/${data.id}`, data.character)
                .then((response) => {
                    commit('SET_CHARACTER', response.data)
                });
        },
        destroy({commit}, character) {
            return axios.delete(`/campaign/characters/${character.id}`)
                .then(() => {
                    commit('REMOVE_CHARACTER', character);
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
        REMOVE_CHARACTER(state, character) {
            let index = state.characters.findIndex((item) => item.id == character.id);
            if (index) {
                state.characters.splice(index, 1);
            }
        },

        SET_BACKGROUNDS(state, backgrounds) {
            state.backgrounds = backgrounds || null;
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
