import { FC } from 'react'
import { useParams } from 'react-router'
import { Link } from 'react-router-dom'
import Private from '../../Common/Layout/Private'
import { useNoteDetailState } from './NoteDetail.state'

const NoteDetail: FC = () => {
  const { id } = useParams()
  const { note } = useNoteDetailState(parseInt(id!))

  if (!note) return <p className="uk-text-center"><i className="fas fa-2x fa-sync fa-spin"></i></p>

  return <div>
    <h1>
      {note ? note.name : ''}
      {note.private ? <Private entity="note" /> : null}
    </h1>
    <div className="uk-section uk-section-default">
      <div dangerouslySetInnerHTML={{ __html: note.content }} />
    </div>
    <p className="uk-margin">
      <Link className="uk-button uk-button-text" to="/notes">
        <i className="fa fa-chevron-left fa-fw"></i> Back to notes
      </Link>
    </p>
  </div>
}

export default NoteDetail