import JournalEntryDetails from "../components/journal/JournalEntryDetails";
import JournalForm from "../components/journal/JournalForm";
import JournalOverview from '../components/journal/JournalOverview'

export const JournalRoutes = [
  {
    path: '/journal',
    element: <JournalOverview />
  },
  {
    path: '/journal/create',
    element: <JournalForm />
  },
  {
    path: '/journal/:id/edit',
    element: <JournalForm />
  },
  {
    path: '/journal/:id',
    element: <JournalEntryDetails />
  }
]