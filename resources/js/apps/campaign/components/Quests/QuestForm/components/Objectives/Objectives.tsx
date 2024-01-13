import classNames from "classnames";
import { FC } from "react";
import ObjectiveForm from "../ObjectiveForm";
import { useObjectivesState } from "./Objectives.state";
import { ObjectivesProps } from "./types";

const Objectives: FC<ObjectivesProps> = ({ value, errors, onChange }) => {
  const { input, update, addObjective, removeObjective } = useObjectivesState(value, onChange)
  return <>
    <h2 className={classNames({ 'uk-text-danger': Object.keys(errors).length > 0 })}>
      Objectives
    </h2>
    {input.map(({ key, objective }, index) =>
      <ObjectiveForm key={key}
                     id={key}
                     objective={objective}
                     errors={Object.fromEntries(Object.entries(errors)
                       .filter(([ i ]) => i.includes(`objectives.${index}`)))}
                     canDelete={input.length > 1}
                     onChange={(val) => update(key, val)}
                     onDelete={() => removeObjective(index)} />)}
    <button className="uk-align-center uk-button uk-button-primary uk-button-round"
            onClick={addObjective}>
      <i className="fas fa-plus fa-fw" />
    </button>
  </>
}

export default Objectives