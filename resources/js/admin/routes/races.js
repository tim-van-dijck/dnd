import RaceDetails from '../components/races/race-detail'
import RaceForm from '../components/races/race-form'
import RaceOverview from '../components/races/race-overview'

export const RaceRoutes = [
    {
        path: '/races',
        name: 'races',
        component: RaceOverview
    },
    {
        path: '/races/create',
        name: 'race-create',
        component: RaceForm
    },
    {
        path: '/races/:id/edit',
        name: 'race-edit',
        component: RaceForm,
        props: true
    },
    {
        path: '/races/:id',
        name: 'race',
        component: RaceDetails,
        props: true
    }
]