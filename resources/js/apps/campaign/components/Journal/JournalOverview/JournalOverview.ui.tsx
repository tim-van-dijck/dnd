import { JournalEntry } from "@dnd/types";
import { Link } from "react-router-dom";
import { DraggableItem } from "../../../../../components/layout/Draggable/types";

export const useDraggableJournalEntries = (
  entries: JournalEntry[] | null,
  destroy: (entry: JournalEntry) => void
): DraggableItem[] => {
  return entries?.map((entry) => (
    {
      key: entry.id,
      content: <div key={`journal-entry-${entry.id}`}
                    className="uk-width uk-card uk-card-body uk-card-secondary"
      >
        <div className="uk-flex">
          <div className="uk-width">
            <h3 className="uk-card-title">
              <Link className="uk-link-heading uk-width uk-display-block" to={`/journal/${entry.id}`}>
                {entry.title}
              </Link>
            </h3>
          </div>
          <div className="uk-flex uk-flex-between">
            <Link className="uk-button uk-button-round uk-button-default journal-button-link"
                  to={`/journal/${entry.id}`}>
              <i className="fas fa-eye"></i>
            </Link>
            <Link className="uk-button uk-button-round uk-button-default journal-button-link"
                  to={`/journal/${entry.id}/edit`}>
              <i className="fas fa-edit"></i>
            </Link>
            <button className="uk-text-danger uk-button uk-button-default uk-button-round"
                    onClick={(e) => {
                      e.preventDefault()
                      destroy(entry)
                    }}>
              <i className="fas fa-trash"></i>
            </button>
            <button type="button"
                    className="sort-handle uk-button uk-button-round uk-button-default">
              <i className="fas fa-grip-vertical"></i>
            </button>
          </div>
        </div>
      </div>
    }
  )) || []
}