import LocationOverview from '../components/locations/location-overview';
import LocationForm from '../components/locations/location-form';
import Location from '../components/locations/location';

export const LocationRoutes = [
    {
        path: '/locations',
        name: 'locations',
        props: true,
        component: LocationOverview
    },
    {
        path: '/locations/create',
        name: 'location-create',
        props: true,
        component: LocationForm
    },
    {
        path: '/locations/:id/edit',
        name: 'location-edit',
        props: true,
        component: LocationForm
    },
    {
        path: '/locations/:id',
        name: 'location',
        props: true,
        component: Location
    }
]