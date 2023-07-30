import NoteDetail from '../components/notes/NoteDetail';
import NoteForm from "../components/notes/NoteForm";
import NoteOverview from '../components/notes/NoteOverview';

export const NoteRoutes = [
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