import InputSelect from "../../../../../../../components/layout/form/components/InputSelect";
import { useProficiencyModalState } from "./ProficiencySelectModal.state";
import InputBoolean from "../../../../../../../components/layout/form/components/InputBoolean";
import { FC } from "react";
import { ProficiencyModalProps } from "./types";
import Modal from "../../../../../../../components/layout/Modal";

const ProficiencySelectModal: FC<ProficiencyModalProps> = ({ selected, onChange }) => {
  const { input, options, save, update } = useProficiencyModalState(selected, onChange)

  return (
    <div>
      <Modal id="proficiency-select-modal"
             title="Select a proficiency"
             trigger={<button className="uk-button uk-button-primary"
                              type="button"
                              data-uk-toggle="target: #proficiency-select-modal">
               Select proficiency
             </button>}>
        {
          input === null ? null :
            <>
              <InputSelect
                id="proficiency-id"
                name="proficiency-id"
                label=""
                emptyLabel="Choose a proficiency"
                options={options}
                initialValue={input.id}
                onChange={(value) => update('id', value as number)} />
              <InputBoolean onChange={(value) => update('optional', value)}
                            id="proficiency-optional"
                            name="proficiency-optional"
                            label="Optional"
                            initialValue={input.optional} />
              <div className="uk-margin">
                <button className="uk-button uk-button-primary" type="button" onClick={save}>
                  Select
                </button>
              </div>
            </>
        }
      </Modal>
    </div>
  )
}

export default ProficiencySelectModal