import { Route } from '@dnd/types'
import CharacterOverview from '../components/Characters/CharacterOverview'
import PlayerCharacterDetail from '../components/Characters/PlayerCharacters/PlayerCharacterDetail'
import PlayerCharacterForm from '../components/Characters/PlayerCharacters/PlayerCharacterForm'
import PlayerCharacterOverview from '../components/Characters/PlayerCharacters/PlayerCharacterOverview'

export const CharacterRoutes: Route[] = [
  {
    path: '/characters',
    element: <CharacterOverview />,
    children: [
      {
        index: true,
        element: <PlayerCharacterOverview />
      },
      {
        path: '/characters/players',
        element: <PlayerCharacterOverview />
      },
      // { path: '/npcs', element: NPCOverview }
    ]
  },
  {
    path: '/characters/players/create',
    element: <PlayerCharacterForm />
  },
  {
    path: '/characters/players/:id',
    element: <PlayerCharacterDetail />
  },
  /*{
      path: '/characters/npcs',
      element: <NPCOVerview />
  },
  {
      path: '/characters/players/:id/edit',
      component: <PlayerCharacterForm />
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
  }*/
]