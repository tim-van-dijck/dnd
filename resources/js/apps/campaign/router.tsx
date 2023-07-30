import NotFound from "../../components/views/NotFound";
import Dashboard from './components/Dashboard'
import { JournalRoutes } from "./routes/journal";
import { LocationRoutes } from "./routes/locations";
import { NoteRoutes } from "./routes/notes";
import { QuestRoutes } from "./routes/quests";
import { RoleRoutes } from "./routes/roles";
import { UserRoutes } from "./routes/users";

const routes = [
  {
    path: '/',
    element: <Dashboard />
  },
  {
    path: '/dashboard',
    element: <Dashboard />
  },

  ...LocationRoutes,
  ...QuestRoutes,
  ...JournalRoutes,
  ...NoteRoutes,
  ...UserRoutes,
  ...RoleRoutes,
  /*
    ...CharacterRoutes,
    ...InventoryRoutes,
  */

  {
    path: '/:pathMatch(.*)/*',
    component: NotFound
  }
]

export default routes