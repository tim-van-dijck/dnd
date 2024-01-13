import { FC } from "react";
import InputBoolean from "../../../../../../../components/layout/form/components/InputBoolean";
import InputText from "../../../../../../../components/layout/form/components/InputText";
import { useObjectiveFormState } from "./ObjectiveForm.state";
import { ObjectiveFormProps } from "./types";

const ObjectiveForm: FC<ObjectiveFormProps> = ({ id, objective, errors, canDelete, onChange, onDelete }) => {
  const { input, update } = useObjectiveFormState(objective, onChange)

  return <div key={`objective-${id}`}
              className="uk-card uk-card-body uk-card-secondary objective">
    <InputText id={`objective-${id}-name`}
               name={`objectives[${id}][name]`}
               label="Name"
               initialValue={input.name}
               errors={errors?.[`objectives.${id}.name`]}
               onChange={(value) => update('name', value)}
               required />
    <InputBoolean id={`objective-${id}-optional`}
                  name={`objectives[${id}][optional]`}
                  label="Optional"
                  initialValue={input.optional}
                  errors={errors?.[`objectives.${id}.optional`]}
                  onChange={(value) => update('optional', value)} />
    {
      canDelete ?
        <a className="uk-text-danger uk-float-right" onClick={(e) => {
          e.preventDefault()
          onDelete()
        }}>
          <i className="fa fa-trash"></i>
        </a> : null
    }
  </div>
}

export default ObjectiveForm