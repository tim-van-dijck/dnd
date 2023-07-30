import { defineStore } from 'pinia/dist/pinia.esm-browser'
import { useMessageStore } from '../../stores/messages'

const url = '/api/campaign/characters'

export const useCharacterStore = defineStore('campaign-characters', {
    state: () => (
        {
            backgrounds: [],
            characters: null,
            classes: null,

            races: {},
            player: {
                info: {},
                classes: {}
            }
        }
    ),
    actions: {
        loadBackgrounds() {
            const includes = ['proficiencies', 'languages', 'features']
            return axios.get(`/api/backgrounds?include=${includes.join(',')}`)
                .then((response) => {
                    this.backgrounds = response.data.data || []
                })
        },
        loadClasses() {
            const includes = [
                'subclasses',
                'proficiencies',
                'subclasses.proficiencies',
                'spells',
                'subclasses.spells',
                'features',
                'subclasses.features'
            ]
            return axios.get(`/api/classes?include=${includes.join(',')}`)
                .then((response) => {
                    const classes = {}
                    for (const charClass of response.data.data) {
                        classes[charClass.id] = charClass
                    }
                    this.classes = classes
                })
        },
        loadRaces() {
            const includes = [
                'proficiencies',
                'languages',
                'abilities',
                'subraces',
                'subraces.abilities',
                'traits',
                'subraces.traits'
            ]
            return axios.get(`/api/races?include=${includes.join(',')}`)
                .then((response) => {
                    this.races = response.data.data
                })
        },

        load(type) {
            const includes = ['classes', 'race', 'subrace']
            return axios.get(`${url}?filter[type]=${type}&includes=${includes}`)
                .then((response) => {
                    this.characters = response.data
                })
        },
        find(id) {
            const includes = ['classes', 'race', 'subrace', 'proficiencies', 'languages', 'spells']
            return axios.get(`${url}/${id}?includes=${includes}`)
                .then((response) => {
                    return response.data.data
                })
        },
        store(character) {
            if (character.background_id === 0) {
                character.background_id = null
            }
            return axios.post(url, character)
                .then((response) => {
                    this.character = response.data
                    const messageStore = useMessageStore()
                    messageStore.success('Character saved!')
                    return response.data.data
                })
                .catch((exception) => {
                    const messageStore = useMessageStore()
                    messageStore.error(exception.response.data.message)
                    throw exception
                })
        },
        update({ id, character }) {
            if (character.background_id === 0) {
                character.background_id = null
            }
            return axios.put(`${url}/${id}`, character)
                .then((response) => {
                    this.character = response.data
                    const messageStore = useMessageStore()
                    messageStore.success('Character saved!')
                    return response.data.data
                })
                .catch((exception) => {
                    const messageStore = useMessageStore()
                    messageStore.error(exception.response.data.message)
                    throw exception
                })
        },
        destroy(id) {
            return axios.delete(`${url}/${id}`)
                .then(() => {
                    this.load()
                })
        }
    }
})
