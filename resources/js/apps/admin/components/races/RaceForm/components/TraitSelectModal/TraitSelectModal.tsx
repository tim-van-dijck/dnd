import InputSelect from "../../../../../../../components/layout/form/components/InputSelect";
import InputText from "../../../../../../../components/layout/form/components/InputText";
import InputRichText from "../../../../../../../components/layout/form/components/InputRichText";
import { FC } from "react";
import { TraitModalProps } from "./types";
import { useTraitModalState } from "./TraitSelectModal.state";
import Modal from "../../../../../../../components/layout/Modal";

const TraitSelectModal: FC<TraitModalProps> = ({ selected, onChange }) => {
  const { input, options, save, selectedTrait, update } = useTraitModalState(selected, onChange)

  return <div>
    <Modal id="trait-select-modal"
           title="Select a race trait"
           trigger={<button className="uk-button uk-button-primary" type="button">
             Select race trait
           </button>}>
      {
        input === null ?
          null :
          <>
            <InputSelect id="trait" name="trait" label=""
                         options={options}
                         initialValue={input.id}
                         onChange={(value) => update('id', value)} />
            {selectedTrait ? <div dangerouslySetInnerHTML={{ __html: selectedTrait.description }}></div> : null}
            <hr />
            <div data-uk-accordion>
              <div className="accordion">
                <div className="uk-accordion-title">
                  <h2>or create a new one</h2>
                </div>
                <div className="uk-accordion-content">
                  <InputText id="trait-name"
                             name="trait-name"
                             label="Name"
                             onChange={(value) => update('name', value)}
                             required />
                  <InputRichText onChange={(value) => update('description', value)}
                                 id="trait-description"
                                 name="trait-description"
                                 label="Description" />
                </div>
              </div>
            </div>
            <div className="uk-margin">
              <button className="uk-button uk-button-primary" type="button"
                      onClick={(e) => {
                        e.preventDefault()
                        save()
                      }}>Select
              </button>
            </div>
            <button className=" uk-modal-close-default uk-close-large" type="button" data-uk-close />
          </>
      }
    </Modal>
  </div>
}

export default TraitSelectModal