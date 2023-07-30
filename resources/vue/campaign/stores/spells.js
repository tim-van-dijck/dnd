import { defineStore } from 'pinia/dist/pinia.esm-browser'

export const useSpellStore = defineStore('campaign-spells', {
    state: () => (
        {
            spells: null,
            multiclassTable: {
                1: {
                    spell_slots_level_1: 2
                },
                2: {
                    spell_slots_level_1: 3
                },
                3: {
                    spell_slots_level_1: 4,
                    spell_slots_level_2: 2
                },
                4: {
                    spell_slots_level_1: 4,
                    spell_slots_level_2: 3
                },
                5: {
                    spell_slots_level_1: 4,
                    spell_slots_level_2: 3,
                    spell_slots_level_3: 2
                },
                6: {
                    spell_slots_level_1: 4,
                    spell_slots_level_2: 3,
                    spell_slots_level_3: 3
                },
                7: {
                    spell_slots_level_1: 4,
                    spell_slots_level_2: 3,
                    spell_slots_level_3: 3,
                    spell_slots_level_4: 1
                },
                8: {
                    spell_slots_level_1: 4,
                    spell_slots_level_2: 3,
                    spell_slots_level_3: 3,
                    spell_slots_level_4: 2
                },
                9: {
                    spell_slots_level_1: 4,
                    spell_slots_level_2: 3,
                    spell_slots_level_3: 3,
                    spell_slots_level_4: 3,
                    spell_slots_level_5: 1
                },
                10: {
                    spell_slots_level_1: 4,
                    spell_slots_level_2: 3,
                    spell_slots_level_3: 3,
                    spell_slots_level_4: 3,
                    spell_slots_level_5: 2
                },
                11: {
                    spell_slots_level_1: 4,
                    spell_slots_level_2: 3,
                    spell_slots_level_3: 3,
                    spell_slots_level_4: 3,
                    spell_slots_level_5: 2,
                    spell_slots_level_6: 1
                },
                12: {
                    spell_slots_level_1: 4,
                    spell_slots_level_2: 3,
                    spell_slots_level_3: 3,
                    spell_slots_level_4: 3,
                    spell_slots_level_5: 2,
                    spell_slots_level_6: 1
                },
                13: {
                    spell_slots_level_1: 4,
                    spell_slots_level_2: 3,
                    spell_slots_level_3: 3,
                    spell_slots_level_4: 3,
                    spell_slots_level_5: 2,
                    spell_slots_level_6: 1,
                    spell_slots_level_7: 1
                },
                14: {
                    spell_slots_level_1: 4,
                    spell_slots_level_2: 3,
                    spell_slots_level_3: 3,
                    spell_slots_level_4: 3,
                    spell_slots_level_5: 2,
                    spell_slots_level_6: 1,
                    spell_slots_level_7: 1
                },
                15: {
                    spell_slots_level_1: 4,
                    spell_slots_level_2: 3,
                    spell_slots_level_3: 3,
                    spell_slots_level_4: 3,
                    spell_slots_level_5: 2,
                    spell_slots_level_6: 1,
                    spell_slots_level_7: 1,
                    spell_slots_level_8: 1
                },
                16: {
                    spell_slots_level_1: 4,
                    spell_slots_level_2: 3,
                    spell_slots_level_3: 3,
                    spell_slots_level_4: 3,
                    spell_slots_level_5: 2,
                    spell_slots_level_6: 1,
                    spell_slots_level_7: 1,
                    spell_slots_level_8: 1
                },
                17: {
                    spell_slots_level_1: 4,
                    spell_slots_level_2: 3,
                    spell_slots_level_3: 3,
                    spell_slots_level_4: 3,
                    spell_slots_level_5: 2,
                    spell_slots_level_6: 1,
                    spell_slots_level_7: 1,
                    spell_slots_level_8: 1,
                    spell_slots_level_9: 1
                },
                18: {
                    spell_slots_level_1: 4,
                    spell_slots_level_2: 3,
                    spell_slots_level_3: 3,
                    spell_slots_level_4: 3,
                    spell_slots_level_5: 2,
                    spell_slots_level_6: 1,
                    spell_slots_level_7: 1,
                    spell_slots_level_8: 1,
                    spell_slots_level_9: 1
                },
                19: {
                    spell_slots_level_1: 4,
                    spell_slots_level_2: 3,
                    spell_slots_level_3: 3,
                    spell_slots_level_4: 3,
                    spell_slots_level_5: 2,
                    spell_slots_level_6: 1,
                    spell_slots_level_7: 1,
                    spell_slots_level_8: 1,
                    spell_slots_level_9: 1
                },
                20: {
                    spell_slots_level_1: 4,
                    spell_slots_level_2: 3,
                    spell_slots_level_3: 3,
                    spell_slots_level_4: 3,
                    spell_slots_level_5: 2,
                    spell_slots_level_6: 1,
                    spell_slots_level_7: 1,
                    spell_slots_level_8: 1,
                    spell_slots_level_9: 1
                }
            }
        }
    ),
    actions: {
        load(force = false) {
            if (force || this.spells === null) {
                return axios.get('/api/spells')
                    .then((response) => {
                        this.spells = response.data
                    })
            } else {
                return Promise.resolve()
            }
        }
    }
})