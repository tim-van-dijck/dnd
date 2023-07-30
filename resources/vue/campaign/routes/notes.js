import NoteOverview from '../components/notes/note-overview';
import NoteForm from '../components/notes/note-form';
import Note from '../components/notes/note';

export const NoteRoutes = [
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
    }
]