import CharacterForm from './components/characters/character-form';
import CharacterOverview from './components/characters/character-overview';
import PlayerCharacterOverview from './components/characters/player-character-overview';
import NPCOverview from './components/characters/npc-overview';

import LocationOverview from './components/locations/location-overview';
import LocationForm from './components/locations/location-form';
import Location from './components/locations/location';

import QuestOverview from './components/quests/quest-overview';
import QuestForm from './components/quests/quest-form';
import Quest from './components/quests/quest';

import NoteOverview from './components/notes/note-overview';
import NoteForm from './components/notes/note-form';
import Note from './components/notes/note';

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
    {
        path: '/locations/:id',
        name: 'location',
        props: true,
        component: Location
    },

    {
        path: '/quests',
        name: 'quests',
        props: true,
        component: QuestOverview
    },
    {
        path: '/quests/create',
        name: 'quest-create',
        props: true,
        component: QuestForm
    },
    {
        path: '/quests/:id',
        name: 'quest',
        props: true,
        component: Quest
    },
    {
        path: '/quests/:id/edit',
        name: 'quest-edit',
        props: true,
        component: QuestForm
    },

    {
        path: '/notes',
        name: 'notes',
        props: true,
        component: NoteOverview
    },
    {
        path: '/notes/create',
        name: 'note-create',
        props: true,
        component: NoteForm
    },
    {
        path: '/notes/:id',
        name: 'note',
        props: true,
        component: Note
    },
    {
        path: '/notes/:id/edit',
        name: 'note-edit',
        props: true,
        component: NoteForm
    },

    {
        path: '/inventory',
        name: 'inventory',
        props: true,
        component: LocationOverview
    },

    {
        path: '/users',
        name: 'users',
        props: true,
        component: LocationOverview
    },

    {
        path: '/roles',
        name: 'roles',
        props: true,
        component: LocationOverview
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