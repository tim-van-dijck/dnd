import { FC } from "react";
import { Link } from "react-router-dom";
import Draggable from "../../../../../components/layout/Draggable";
import { useJournalOverviewState } from "./JournalOverview.state";
import { useDraggableJournalEntries } from "./JournalOverview.ui";

const JournalOverview: FC = () => {
  const { loading, entries, sort, destroy } = useJournalOverviewState()
  const draggableItems = useDraggableJournalEntries(entries, destroy)

  return <div id="journal">
    <h1>Journal</h1>
    <div className="uk-section uk-section-default uk-padding-remove-top">
      <Link className="uk-button uk-button-primary" to="/journal/create">
        <i className="fas fa-plus"></i> Add journal entry
      </Link>
      {
        !loading && entries.length > 0 ?
          <div className="uk-width uk-margin">
            <Draggable items={draggableItems} onUpdate={sort} />
          </div> :
          <p className="uk-text-center">
            {loading ? <i className="fas fa-sync fa-spin fa-2x"></i> : <span>No journal entries found</span>}
          </p>
      }
    </div>
  </div>
}

export default JournalOverview