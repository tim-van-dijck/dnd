import { Link } from "react-router-dom";
import { ui } from './RaceOverview.ui'
import PaginatedTable from "../../../../../components/layout/PaginatedTable";
import { useRaceOverviewState } from "./RaceOverview.state";

const RaceOverview = () => {
  const { destroy, RaceRepository } = useRaceOverviewState()

  return <div id="races">
    <h1>Races</h1>
    <div className="uk-section uk-section-default">
      <div className="uk-container padded">
        <Link className="uk-button uk-button-primary" to="/races/create">
          <i className="fas fa-plus"></i> Add race
        </Link>
        {(
          RaceRepository.races?.data?.length || 0
        ) > 0 ?
          <PaginatedTable
            actions={ui.actions}
            columns={ui.columns}
            records={RaceRepository.races}
            repository={{
              previous: RaceRepository.previous,
              page: RaceRepository.page,
              next: RaceRepository.next,
              load: RaceRepository.load
            }}
            onAction={(type: string, row) => {
              if (type === 'destroy') destroy(row.id)
            }} /> : null}
      </div>
    </div>
  </div>
}

export default RaceOverview