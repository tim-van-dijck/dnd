import { FC } from "react";
import InputSelect from "../../../../../../../components/layout/form/components/InputSelect";
import InputBoolean from "../../../../../../../components/layout/form/components/InputBoolean";
import { useLanguageModalState } from "./LanguageSelectModal.state";
import { LanguageModalProps } from "./types";
import Modal from "../../../../../../../components/layout/Modal";

const LanguageSelectModal: FC<LanguageModalProps> = ({ selected, onChange }) => {
  const { input, options, save, update } = useLanguageModalState(selected, onChange)

  return <div>
    <Modal id="lang-select-modal" title="Select a language proficiency" trigger={
      <button className="uk-button uk-button-primary" type="button">
        Select language
      </button>}>
      {
        input === null ? null :
          <>
            <InputSelect
              id="language"
              name="language"
              label=""
              emptyLabel="Choose a language"
              options={options}
              initialValue={input.id}
              onChange={(value) => update('id', value)} />
            <InputBoolean
              id="language-optional"
              name="language-optional"
              label="Optional"
              onChange={(value) => update('optional', value)}
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
}

export default LanguageSelectModal