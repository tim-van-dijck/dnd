import { Link } from "react-router-dom";
import PaginatedTable from "../../../../../components/layout/PaginatedTable";
import { useQuestOverviewState } from "./QuestOverview.state";
import { useQuestOverviewUI } from "./QuestOverview.ui";

const QuestOverview = () => {
  const { questRepository, destroy } = useQuestOverviewState()
  const ui = useQuestOverviewUI()

  return <div id="quests">
    <h1>Quests</h1>
    <div className="uk-section uk-section-default uk-padding-remove-top">
      {
        ui.can('create', 'quest') ?
          <Link className="uk-button uk-button-primary" to="/quests/create">
            <i className="fas fa-plus"></i> Add quest
          </Link> : null
      }
      {
        questRepository.quests !== null && questRepository.quests?.data?.length > 0 ?
          <PaginatedTable
            actions={ui.actions}
            columns={ui.columns}
            records={questRepository.quests}
            repository={{
              previous: questRepository.previous,
              page: questRepository.page,
              next: questRepository.next,
              load: questRepository.load
            }}
            onAction={(type: string, row) => {
              if (type === 'destroy') destroy(row.id)
            }}
          /> : <p className="uk-text-center">
            {questRepository.quests === null ? <i className="fas fa-sync fa-spin fa-2x"></i> :
              <span>Your quest log is empty!</span>}
          </p>
      }
    </div>
  </div>
}

export default QuestOverview
