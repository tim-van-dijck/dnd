import { JournalEntry } from "@dnd/types";
import { FC, useEffect, useState } from "react";
import { useParams } from "react-router";
import { Link } from "react-router-dom";
import { useCampaignRepositories } from "../../../providers/CampaignRepositoryProvider";

const JournalEntryDetails: FC = () => {
  const [ entry, setEntry ] = useState<JournalEntry | null>(null)
  const { id: idAsString } = useParams()
  const id: number | undefined = typeof idAsString === 'string' ? parseInt(idAsString) : idAsString
  const { JournalRepository } = useCampaignRepositories()

  useEffect(() => {
    if (id) JournalRepository.find(id).then(setEntry)
  }, [ id ])

  return <div>
    <h1>{entry?.title || ''}</h1>
    <div className="uk-section uk-section-default">
      {
        entry ?
          <div id="journal-entry" className="uk-container padded">
            <div dangerouslySetInnerHTML={{ __html: entry.content }} />
            <p className="uk-margin-large-top">
              <Link className="uk-button uk-button-text" to="/journal">
                <i className="fa fa-chevron-left fa-fw"></i> Back to journal
              </Link>
            </p>
          </div> :
          <p className="uk-text-center"><i className="fas fa-2x fa-sync fa-spin"></i></p>
      }
    </div>
  </div>
}

export default JournalEntryDetails