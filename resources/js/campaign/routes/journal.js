import JournalEntry from '../components/journal/journal-entry'
import JournalForm from '../components/journal/journal-form'
import JournalOverview from '../components/journal/journal-overview'

export const JournalRoutes = [
    {
        path: '/journal',
        name: 'journal',
        props: true,
        component: JournalOverview
    },
    {
        path: '/journal/create',
        name: 'journal-create',
        props: true,
        component: JournalForm
    },
    {
        path: '/journal/:id/edit',
        name: 'journal-edit',
        props: true,
        component: JournalForm
    },
    {
        path: '/journal/:id',
        name: 'journal-details',
        props: true,
        component: JournalEntry
    }
]