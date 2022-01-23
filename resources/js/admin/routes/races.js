import RaceOverview from "../components/races/race-overview";
import RaceForm from "../components/races/race-form";

export const RaceRoutes = [
    {
        path: '/races',
        name: 'races',
        component: RaceOverview,
    },
    {
        path: '/races/create',
        name: 'race-create',
        component: RaceForm,
    },
    {
        path: '/races/:id/edit',
        name: 'race-edit',
        component: RaceForm,
    },
]