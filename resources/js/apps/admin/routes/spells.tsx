import SpellDetail from "../components/spells/SpellDetail";
import SpellForm from "../components/spells/SpellForm";
import SpellOverview from "../components/spells/SpellOverview";

export const SpellRoutes = [
  {
    path: '/spells',
    element: <SpellOverview />
  },
  {
    path: '/spells/create',
    element: <SpellForm />
  },
  {
    path: '/spells/:id/edit',
    element: <SpellForm />
  },
  {
    path: '/spells/:id',
    element: <SpellDetail />
  }
]