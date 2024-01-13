import classNames from "classnames";
import { FC } from "react";
import { useQuestObjective } from "./QuestObjective.state";
import { QuestObjectiveProps } from "./types";

const QuestObjective: FC<QuestObjectiveProps> = ({ questId, objective }) => {
  const { status, complete, fail } = useQuestObjective(questId, objective)

  return <div className="uk-card uk-card-body objective">
    <span className="actions uk-float-right">
      <i className={classNames("fas fa-check-circle fa-fw", { 'uk-text-success': status == 1 })}
         onClick={complete} />
      <i className={classNames("fas fa-times-circle fa-fw", { 'uk-text-danger': status == 2 })}
         onClick={fail} />
    </span>
    <span className={classNames({ 'uk-text-success': status == 1, 'uk-text-danger': status == 2 })}>
      {status == 1 ? <i className="fas fa-check-circle fa-fw"></i> : null}
      {status == 2 ? <i className="fas fa-times-circle fa-fw"></i> : null}
      {status > 0 ? <s>{objective.name}</s> : <span>{objective.name}</span>}
    </span>
  </div>
}

export default QuestObjective