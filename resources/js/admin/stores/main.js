import { defineStore } from 'pinia/dist/pinia.esm-browser'

export const useMainStore = defineStore('main', {
    state: () => (
        {
            errors: {},
            languages: null,
            logs: [],
            user: {
                permissions: {}
            }
        }
    ),
    actions: {
        logout() {
            axios.post('/logout')
                .then(() => {
                    document.location.href = '/'
                })
        },
        loadLanguages() {
            return axios.get('/api/languages')
                .then((response) => {
                    this.languages = response.data
                })
        }
    }
})