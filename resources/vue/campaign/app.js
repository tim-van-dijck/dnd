require('../bootstrap')
window.axios.defaults.headers.common['Campaign-Id'] = `${window.App.campaign_id}`

import { createPinia } from 'pinia/dist/pinia.esm-browser'
import tinymce from 'tinymce'
import { createApp } from 'vue'
import Campaign from './components/Campaign'
import router from './router'
import { useMainStore } from './stores/main'

window.tinymce = tinymce

window.onload = () => {
    const app = createApp(Campaign)
    app
        .use(router)
        .use(createPinia())

    const store = useMainStore()
    Promise.all([
        router.isReady(),
        store.loadCampaign(),
        store.loadUser()
    ])
        .then(() => app.mount('#app'))
        .catch((exception) => {
            console.log(exception)
            if (exception.response.status === 403) {
                document.location.href = '/'
            }
        })
}
