import Dashboard from './components/dashboard';

import CharacterForm from './components/characters/character-form';
import PCForm from './components/characters/create/pc-form';
import PCDetail from './components/characters/details/pc-detail';
import CharacterOverview from './components/characters/character-overview';
import PlayerCharacterOverview from './components/characters/player-character-overview';
import NPCOverview from './components/characters/npc-overview';

import LocationOverview from './components/locations/location-overview';
import LocationForm from './components/locations/location-form';
import Location from './components/locations/location';

import NoteOverview from './components/notes/note-overview';
import NoteForm from './components/notes/note-form';
import Note from './components/notes/note';

import QuestOverview from './components/quests/quest-overview';
import QuestForm from './components/quests/quest-form';
import Quest from './components/quests/quest';

import RoleOverview from './components/roles/role-overview';
import RoleForm from './components/roles/role-form';

import UserOverview from './components/users/user-overview';
import UserForm from './components/users/user-form';

import VueRouter from "vue-router";
import ComingSoon from "./components/ComingSoon";

let routes = [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard
    },
    {
        path: '/characters',
        redirect: 'player-characters',
        name: 'characters',
        component: CharacterOverview,
        children: [
            {name: 'player-characters', path: 'players', component: PlayerCharacterOverview},
            {name: 'npcs', path: 'npc', component: NPCOverview},
        ]
    },
    {
        path: '/characters/player/create',
        name: 'pc-create',
        props: true,
        component: PCForm
    },
    {
        path: '/characters/player/:id',
        name: 'pc-detail',
        props: true,
        component: PCDetail
    },
    {
        path: '/characters/player/:id/edit',
        name: 'pc-edit',
        props: true,
        component: PCForm
    },
    {
        path: '/characters/npc/create',
        name: 'npc-create',
        props: true,
        component: CharacterForm
    },
    {
        path: '/characters/npc/:id',
        name: 'npc-detail',
        props: true,
        component: CharacterForm
    },
    {
        path: '/characters/npc/:id/edit',
        name: 'npc-edit',
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
        component: ComingSoon
    },

    {
        path: '/users',
        name: 'users',
        props: true,
        component: UserOverview
    },
    {
        path: '/users/invite',
        name: 'user-invite',
        props: true,
        component: UserForm
    },
    {
        path: '/users/:id/edit',
        name: 'user-edit',
        props: true,
        component: UserForm
    },

    {
        path: '/roles',
        name: 'roles',
        props: true,
        component: RoleOverview
    },
    {
        path: '/roles/create',
        name: 'role-create',
        props: true,
        component: RoleForm
    },
    {
        path: '/roles/:id/edit',
        name: 'role-edit',
        props: true,
        component: RoleForm
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