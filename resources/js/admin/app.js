require('../bootstrap')

import { createPinia } from 'pinia/dist/pinia.esm-browser'

import tinymce from 'tinymce'
import { createApp } from 'vue'
import Admin from './components/Admin'
import { router } from './router'

window.tinymce = tinymce

window.onload = () => {
    const app = createApp(Admin)
    app
        .use(router)
        .use(createPinia())

    router.isReady().then(() => app.mount('#admin-app'))
}