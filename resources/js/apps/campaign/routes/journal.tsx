import { Route } from '@dnd/types'
import JournalEntryDetails from '../components/Journal/JournalEntryDetails'
import JournalForm from '../components/Journal/JournalForm'
import JournalOverview from '../components/Journal/JournalOverview'

export const JournalRoutes: Route[] = [
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