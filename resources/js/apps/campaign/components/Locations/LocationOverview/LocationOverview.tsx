import { Link } from "react-router-dom";
import PaginatedTable from "../../../../../components/layout/PaginatedTable";
import { useLocationOverviewState } from "./LocationOverview.state";
import { useLocationOverviewUI } from "./LocationOverview.ui";

const LocationOverview = () => {
  const { destroy, locationRepository } = useLocationOverviewState()
  const { actions, can, columns } = useLocationOverviewUI()

  return <div id="locations">
    <h1>Locations</h1>
    <div className="uk-section uk-section-default uk-padding-remove-top">
      {
        can('create', 'location') ?
          <Link className="uk-button uk-button-primary" to="/locations/create">
            <i className="fas fa-plus"></i> Add location
          </Link> : null
      }
      {
        locationRepository.locations != null && locationRepository.locations.data.length > 0 ?
          <PaginatedTable
            actions={actions}
            columns={columns}
            repository={{
              previous: locationRepository.previous,
              page: locationRepository.page,
              next: locationRepository.next,
              load: locationRepository.load
            }}
            records={locationRepository.locations}
            onAction={(type: string, row) => {
              if (type === 'destroy') destroy(row.id)
            }}
          /> :
          <p className="uk-text-center">
            {locationRepository.locations === null ?
              <i className="fas fa-sync fa-spin fa-2x" /> :
              <span>No locations found</span>}
          </p>
      }
    </div>
  </div>
}

export default LocationOverview