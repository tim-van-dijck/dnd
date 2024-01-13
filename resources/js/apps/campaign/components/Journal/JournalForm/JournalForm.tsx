import { FC } from "react";
import { useParams } from "react-router";
import { useJournalFormState } from "./JournalForm.state";
import { useJournalFormUI } from "./JournalForm.ui";
import JournalFormView from "./JournalForm.view";

const JournalForm: FC = () => {
  const { id: idAsString } = useParams()
  const id: number | undefined = typeof idAsString === 'string' ? parseInt(idAsString) : idAsString
  const { input, errors, save, update } = useJournalFormState(id)
  const { can, redirect, title } = useJournalFormUI(id, input)

  return <JournalFormView state={{ errors, id, input, save, update }} ui={{ can, redirect, title }} />
}

export default JournalForm