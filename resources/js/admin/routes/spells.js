import SpellForm from '../components/spells/spell-form'
import SpellOverview from '../components/spells/spell-overview'

export const SpellRoutes = [
    {
        path: '/spells',
        name: 'spells',
        props: true,
        component: SpellOverview
    },
    {
        path: '/spells/create',
        name: 'spell-create',
        props: true,
        component: SpellForm
    },
    {
        path: '/spells/:id',
        name: 'spell-edit',
        props: true,
        component: SpellForm
    }
]