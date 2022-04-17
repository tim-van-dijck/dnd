require('../bootstrap');

import { createApp } from 'vue';
import {router} from './router';
import {store} from './store';
import Admin from './components/Admin'


window.copy = (item) => JSON.parse(JSON.stringify(item));

window.onload = () => {
    const app = createApp(Admin);
    app
        .use(router)
        .use(store)
        .mount('#admin-app')
};