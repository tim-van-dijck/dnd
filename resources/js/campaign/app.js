require('../bootstrap')

import { createApp } from 'vue'
import Campaign from './components/Campaign'
import router from './router'
import store from './store'

window.onload = () => {
    if (document.getElementById('app')) {
        store.dispatch('loadCampaign')
            .catch((error) => {
                if (error.response.status === 403) {
                    document.location.href = '/'
                }
            })
        store.dispatch('loadUser')
            .then(() => {
                const app = createApp(Campaign)
                app
                    .use(router)
                    .use(store)
                    .mount('#app')
            })
            .catch((error) => {
                if (error.response.status === 403) {
                    document.location.href = '/'
                }
            })
    }
}
