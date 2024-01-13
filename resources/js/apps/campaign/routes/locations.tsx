import { Route } from '@dnd/types'
import LocationDetail from '../components/Locations/LocationDetail'
import LocationForm from '../components/Locations/LocationForm'
import LocationOverview from '../components/Locations/LocationOverview'

export const LocationRoutes: Route[] = [
  {
    path: '/locations',
    element: <LocationOverview />
  },
  {
    path: '/locations/create',
    element: <LocationForm />
  },
  {
    path: '/locations/:id',
    element: <LocationDetail />
  },
  {
    path: '/locations/:id/edit',
    element: <LocationForm />
  }
]