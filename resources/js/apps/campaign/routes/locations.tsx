import LocationDetail from "../components/locations/LocationDetail";
import LocationForm from "../components/locations/LocationForm";
import LocationOverview from '../components/locations/LocationOverview'

export const LocationRoutes = [
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