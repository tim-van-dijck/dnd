import { useParams } from "react-router";
import { useQuestFormState } from "./QuestForm.state";
import { useQuestFormUI } from "./QuestForm.ui";
import QuestFormView from "./QuestForm.view";

const QuestForm = () => {
  const { id: idAsString } = useParams()
  const id: number | undefined = typeof idAsString === 'string' ? parseInt(idAsString) : idAsString
  const { errors, input, save, update } = useQuestFormState(id)
  const { can, redirect, tab, setTab, title } = useQuestFormUI(input, id)

  return <QuestFormView state={{ id, errors, input, save, update }} ui={{ can, title, tab, setTab, redirect }} />
}

export default QuestForm