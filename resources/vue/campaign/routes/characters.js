import CharacterForm from '../components/characters/character-form'
import CharacterOverview from '../components/characters/character-overview'
import NPCOverview from '../components/characters/npc-overview'
import PCDetail from '../components/characters/player-character'
import PCForm from '../components/characters/player-character-form'
import PlayerCharacterOverview from '../components/characters/player-character-overview'

export const CharacterRoutes = [
    {
        path: '/characters',
        redirect: 'player-characters',
        name: 'characters',
        component: CharacterOverview,
        children: [
            { name: 'player-characters', path: 'players', component: PlayerCharacterOverview },
            { name: 'npcs', path: 'npc', component: NPCOverview }
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
    }
]