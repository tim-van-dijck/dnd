import CharacterForm from './components/characters/character-form';
import CharacterOverview from './components/characters/character-overview';
import PlayerCharacterOverview from './components/characters/player-character-overview';
import NPCOverview from './components/characters/npc-overview';

import LocationOverview from './components/locations/location-overview';
import LocationForm from './components/locations/location-form';

import VueRouter from "vue-router";

let routes = [
    {path: '/', redirect: '/characters'},
    {
        path: '/characters',
        name: 'characters',
        component: CharacterOverview,
        children: [
            {name: 'player-characters', path: '/characters', component: PlayerCharacterOverview},
            {name: 'npcs', path: 'npc', component: NPCOverview},
        ]
    },
    {
        path: '/characters/create/:type',
        name: 'character-create',
        props: true,
        component: CharacterForm
    },
    {
        path: '/characters/:id/edit',
        name: 'character-edit',
        props: true,
        component: CharacterForm
    },
    {
        path: '/locations',
        name: 'locations',
        props: true,
        component: LocationOverview
    },
    {
        path: '/locations/create',
        name: 'location-create',
        props: true,
        component: LocationForm
    },
    {
        path: '/locations/:id/edit',
        name: 'location-edit',
        props: true,
        component: LocationForm
    },
];

let router = new VueRouter({routes});
router.beforeEach((to, from, next) => {
    if (to.hasOwnProperty('meta') && to.meta.hasOwnProperty('title')) {
        document.title = to.meta.title;
    }
    next();
});
export default router;