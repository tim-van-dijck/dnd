import { FC } from "react";
import { useParams } from "react-router";
import { useNoteFormState } from "./NoteForm.state";
import { useNoteFormUI } from "./NoteForm.ui";
import NoteFormView from "./NoteForm.view";

const NoteForm: FC = () => {
  const { id: idAsString } = useParams()
  const id: number | undefined = typeof idAsString === 'string' ? parseInt(idAsString) : idAsString
  const { errors, input, save, update } = useNoteFormState(id)
  const { can, redirect, title, tab, setTab } = useNoteFormUI(input, id)

  return <NoteFormView state={{ errors, id, input, save, update }} ui={{ can, title, redirect, tab, setTab }} />
}

export default NoteForm