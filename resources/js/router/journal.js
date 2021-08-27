import JournalOverview from "../components/journal/journal-overview";
import JournalForm from "../components/journal/journal-form";
import JournalEntry from "../components/journal/journal-entry"

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