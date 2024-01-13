import { FC } from "react";
import { Link } from "react-router-dom";
import PaginatedTable from "../../../../../components/layout/PaginatedTable";
import { useNoteOverviewState } from "./NoteOverview.state";
import { ui } from "./NoteOverview.ui";

const NoteOverview: FC = () => {
  const { noteRepository, destroy } = useNoteOverviewState()

  return <div id="Notes">
    <h1>Notes</h1>
    <div className="uk-section uk-section-default uk-padding-remove-top">
      <Link className="uk-button uk-button-primary" to="/notes/create"><i className="fas fa-plus"></i> Add note</Link>
      {
        noteRepository.notes !== null && noteRepository.notes.data.length > 0 ?
          <PaginatedTable
            actions={ui.actions}
            columns={ui.columns}
            records={noteRepository.notes}
            repository={{
              previous: noteRepository.previous,
              page: noteRepository.page,
              next: noteRepository.next,
              load: noteRepository.load
            }}
            onAction={(type: string, row) => {
              if (type === 'destroy') destroy(row.id)
            }} /> :
          <p className="uk-text-center">
            {
              noteRepository.notes === null ? <i className="fas fa-sync fa-spin fa-2x"></i> :
                <span>No notes found</span>
            }
          </p>
      }
    </div>
  </div>
}

export default NoteOverview