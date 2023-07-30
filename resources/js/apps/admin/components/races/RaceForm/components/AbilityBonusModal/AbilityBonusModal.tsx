import InputSelect from "../../../../../../../components/layout/form/components/InputSelect";
import InputNumber from "../../../../../../../components/layout/form/components/InputNumber";
import InputBoolean from "../../../../../../../components/layout/form/components/InputBoolean";
import { useAbilityBonusModalState } from "./AbilityBonusModal.state";
import { FC } from "react";
import { AbilityBonusModalProps } from "./types";
import Modal from "../../../../../../../components/layout/Modal";

const AbilityBonusModal: FC<AbilityBonusModalProps> = ({ selected, onChange }) => {
  const { input, options, save, update } = useAbilityBonusModalState(selected, onChange)
  return (
    <div>
      <Modal id="ability-select-modal"
             trigger={<button className="uk-button uk-button-primary"
                              type="button">
               Select ability bonus
             </button>}>
        {input === null ? null :
          <>
            <InputSelect id="ability"
                         name="ability"
                         label=""
                         emptyLabel="Choose an ability"
                         options={options}
                         onChange={(value) => update('ability', value)}
                         initialValue={input.ability} />
            <InputNumber id="bonus"
                         name="bonus"
                         label="Bonus"
                         onChange={(value) => update('bonus', value)}
                         initialValue={input.bonus} />
            <InputBoolean onChange={(value) => update('optional', value)}
                          id="ability-optional"
                          name="ability-optional"
                          label="Optional"
                          initialValue={input.optional} />
            <div className="uk-margin">
              <button className="uk-button uk-button-primary" type="button" onClick={(e) => {
                e.preventDefault()
                save()
              }}>Select
              </button>
            </div>
          </>
        }
      </Modal>
    </div>
  )
}

export default AbilityBonusModal