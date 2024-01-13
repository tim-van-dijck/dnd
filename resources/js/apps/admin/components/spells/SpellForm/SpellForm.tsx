import InputText from "../../../../../components/layout/form/components/InputText";
import InputSelect from "../../../../../components/layout/form/components/InputSelect";
import InputBoolean from "../../../../../components/layout/form/components/InputBoolean/InputBoolean";
import InputRichText from "../../../../../components/layout/form/components/InputRichText";
import FormButtons from "../../../../../components/layout/form/components/FormButtons";
import { useNavigate } from "react-router-dom";
import { useParams } from "react-router";
import { useSpellFormState } from "./SpellForm.state";
import InputCheckbox from "../../../../../components/layout/form/components/InputCheckbox";

const SpellForm = () => {
  const { id } = useParams()
  const navigate = useNavigate()
  const { spell, componentOptions, errors, levels, schoolOptions, onUpdate, submit } = useSpellFormState(id)
  const title = id ? `Edit ${spell ? spell.name : 'spell'}` : 'Add spell'


  return <div>
    <h1>{title}</h1>
    <div className="uk-section uk-section-default">
      <div className="uk-container padded">
        {
          spell ?
            <form className="uk-form-stacked" data-uk-grid onSubmit={submit}>
              <div className="uk-width-1-2">
                <InputText id="name"
                           name="name"
                           label="Name"
                           initialValue={spell.name}
                           errors={errors?.name}
                           onChange={(value) => onUpdate('name', value)}
                           required />
                <InputSelect id="level"
                             name="level"
                             label="Level"
                             initialValue={spell.level}
                             emptyLabel="Choose a level"
                             errors={errors?.level}
                             options={levels}
                             onChange={(value) => onUpdate('level', value)}
                             required />
                <InputSelect id="school"
                             name="school"
                             label="School"
                             emptyLabel="Choose a school"
                             initialValue={spell.school}
                             errors={errors?.school}
                             options={schoolOptions}
                             onChange={(value) => onUpdate('school', value)}
                             required />
                <InputText id="range"
                           name="range"
                           label="Range"
                           initialValue={spell.range}
                           errors={errors?.range}
                           onChange={(value) => onUpdate('range', value)}
                           required />
                <InputText id="duration"
                           name="duration"
                           label="Duration"
                           initialValue={spell.duration}
                           errors={errors?.duration}
                           onChange={(value) => onUpdate('duration', value)}
                           required />
                <InputText id="casting_time"
                           name="casting_time"
                           label="Casting Time"
                           initialValue={spell.casting_time}
                           errors={errors?.casting_time}
                           onChange={(value) => onUpdate('casting_time', value)}
                           required />
                <InputCheckbox id="components"
                               name="components"
                               label="Components"
                               initialValue={spell.components}
                               errors={errors?.components}
                               options={componentOptions}
                               onChange={(value) => onUpdate('components', value)}
                               required />
                {
                  spell.components.includes('M') ?
                    <InputText id="materials"
                               name="materials"
                               label="Materials"
                               initialValue={spell.materials}
                               errors={errors?.materials}
                               onChange={(value) => onUpdate('materials', value)} />
                    : null
                }

                <hr />
                <InputBoolean id="ritual"
                              name="ritual"
                              label="Ritual"
                              initialValue={spell.ritual}
                              errors={errors?.ritual}
                              onChange={(value) => onUpdate('ritual', value)} />
                <InputBoolean id="concentration"
                              name="concentration"
                              label="Concentration"
                              initialValue={spell.concentration}
                              errors={errors?.concentration}
                              onChange={(value) => onUpdate('concentration', value)} />
              </div>
              <div className="uk-width-1-2">
                <InputRichText id="description"
                               name="description"
                               label="Description"
                               initialValue={spell.description}
                               errors={errors?.description}
                               onChange={(value) => onUpdate('description', value)} />
                <InputText id="higher_levels"
                           name="higher_levels"
                           label="At higher levels"
                           initialValue={spell.higher_levels}
                           errors={errors?.higher_levels}
                           onChange={(value) => onUpdate('higher_levels', value)}
                           multiline />
              </div>

              <FormButtons cancel={() => navigate('/spells')} />
            </form> : <p className="uk-text-center"><i className="fas fa-2x fa-sync fa-spin"></i></p>}
      </div>
    </div>
  </div>;
}

export default SpellForm