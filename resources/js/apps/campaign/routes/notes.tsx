import { Route } from '@dnd/types'
import NoteDetail from '../components/Notes/NoteDetail'
import NoteForm from '../components/Notes/NoteForm'
import NoteOverview from '../components/Notes/NoteOverview'

export const NoteRoutes: Route[] = [
  {
    path: '/notes',
    element: <NoteOverview />
  },
  {
    path: '/notes/create',
    element: <NoteForm />
  },
  {
    path: '/notes/:id',
    element: <NoteDetail />
  },
  {
    path: '/notes/:id/edit',
    element: <NoteForm />
  }
]