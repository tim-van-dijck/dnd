import { FC } from "react";
import { useParams } from "react-router";
import { Link } from "react-router-dom";
import { useNoteDetailState } from "./NoteDetail.state";

const NoteDetail: FC = () => {
  const { id } = useParams()
  const { note } = useNoteDetailState(parseInt(id!))

  if (!note) return <p className="uk-text-center"><i className="fas fa-2x fa-sync fa-spin"></i></p>

  return <div>
    <h1>
      <Link className="uk-link-text" to="/notes"><i className="fas fa-chevron-left"></i></Link>
      {note ? note.name : ''}
      {note.private ?
        <>{' '}<span title="This note is private">(<i className="fas fa-user-secret"></i>)</span></> :
        null}
    </h1>
    <div className="uk-section uk-section-default">
      <div id="note" className="uk-container padded">
        <div dangerouslySetInnerHTML={{ __html: note.content }} />
        <p className="uk-margin">
          <Link className="uk-button uk-button-text" to="/notes">
            <i className="fa fa-chevron-left fa-fw"></i> Back to notes
          </Link>
        </p>
      </div>
    </div>
  </div>
}

export default NoteDetail