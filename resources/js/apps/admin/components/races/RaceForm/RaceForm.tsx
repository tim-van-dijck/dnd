import { useParams } from "react-router";
import { useNavigate } from "react-router-dom";
import FormButtons from "../../../../../components/layout/form/components/FormButtons";
import InputNumber from "../../../../../components/layout/form/components/InputNumber";
import InputRichText from "../../../../../components/layout/form/components/InputRichText";
import InputSelect from "../../../../../components/layout/form/components/InputSelect";
import InputText from "../../../../../components/layout/form/components/InputText";
import AbilityBonusModal from "./components/AbilityBonusModal";
import FeatureList from "./components/FeatureList";
import LanguageSelectModal from "./components/LanguageSelectModal";
import ProficiencySelectModal from "./components/ProficiencySelectModal";
import TraitSelectModal from "./components/TraitSelectModal";
import { useRaceFormState } from "./RaceForm.state";
import { useRaceFormUI } from "./RaceForm.ui";

const RaceForm = () => {
  const { id } = useParams()
  const navigate = useNavigate()
  const {
    race, errors, sizes, addTrait, remove, removeTrait, onUpdate, submit
  } = useRaceFormState(id ? parseInt(id) : undefined)
  const { selected, title } = useRaceFormUI(race, id)

  return <div>
    <h1>{title}</h1>
    <div className="uk-section uk-section-default">
      <div className="uk-container padded">
        {race ? <form className="uk-form-stacked" onSubmit={submit} data-uk-grid>
            <div className="uk-width-1-2">
              <InputText id="name"
                         name="name"
                         label="Name"
                         initialValue={race.name}
                         errors={errors.name}
                         onChange={(value) => onUpdate('name', value)}
                         required />
              <InputSelect id="size"
                           name="size"
                           label="Size"
                           options={sizes}
                           initialValue={race.size}
                           errors={errors.size}
                           onChange={(value) => onUpdate('size', value)}
                           required />
              <InputNumber id="speed"
                           name="speed"
                           label="Speed"
                           initialValue={race.speed}
                           errors={errors.speed}
                           min={0}
                           step={5}
                           onChange={(value) => onUpdate('speed', value)}
                           required />
              <InputRichText id="description"
                             name="description"
                             label="Description"
                             initialValue={race.description}
                             errors={errors?.description}
                             onChange={(value) => onUpdate('description', value)} />
            </div>
            <div className="uk-width-1-2">
              <h3>Ability Bonuses</h3>
              <InputNumber id="optional_ability_bonuses"
                           name="optional_ability_bonuses"
                           label="Ability bonus choices"
                           initialValue={race.optional_ability_bonuses}
                           errors={errors.optional_ability_bonuses}
                           min={0}
                           onChange={(value) => onUpdate('optional_ability_bonuses', value)}
                           required />
              <FeatureList items={selected.ability_bonuses.map(({ id, ability, bonus, optional }) => (
                { id, name: `${ability} +${bonus}`, optional }
              ))}
                           remove={(id) => remove('ability_bonuses', id)} />
              <div className="uk-margin">
                <AbilityBonusModal selected={race.ability_bonuses.map(ability => ability.ability)}
                                   onChange={(value) => onUpdate(
                                     'ability_bonuses',
                                     [ ...race?.ability_bonuses, value ]
                                   )} />
              </div>
              <h3>Proficiencies</h3>
              <InputNumber id="optional_proficiencies"
                           name="optional_proficiencies"
                           label="Proficiency choices"
                           initialValue={race.optional_proficiencies}
                           errors={errors.optional_proficiencies}
                           min={0}
                           onChange={(value) => onUpdate('optional_proficiencies', value)}
                           required />
              <FeatureList items={selected.proficiencies} remove={(id) => remove('proficiencies', id)} />
              <div className="uk-margin">
                <ProficiencySelectModal selected={race.proficiencies.map(prof => prof.id)}
                                        onChange={(value) => onUpdate(
                                          'proficiencies',
                                          [ ...race.proficiencies, value ]
                                        )} />
              </div>
              <h3>Languages</h3>
              <InputNumber id="optional_languages"
                           name="optional_languages"
                           label="Language choices"
                           initialValue={race.optional_languages}
                           errors={errors.optional_languages}
                           min={0}
                           onChange={(value) => onUpdate('optional_languages', value)}
                           required />
              <FeatureList items={selected.languages} remove={(id) => remove('languages', id)} />

              <div className="uk-margin">
                <LanguageSelectModal selected={race.languages.map(lang => lang.id)}
                                     onChange={(value) => {
                                       onUpdate('languages', [ ...race.languages, value ]);
                                     }} />
              </div>
              <h3>Feats</h3>
              <InputNumber id="optional_feats"
                           name="optional_feats"
                           label="Feat choices"
                           initialValue={race.optional_feats}
                           errors={errors.optional_feats}
                           min={0}
                           onChange={(value) => onUpdate('optional_feats', value)}
                           required />

            </div>
            <div className="uk-margin uk-width">
              <h2>Race Traits</h2>
              <FeatureList items={selected.traits} remove={(_, index) => removeTrait(index)} />
              <TraitSelectModal selected={race.traits.map(trait => trait.id)} onChange={(value) => addTrait(value)} />
            </div>
            <FormButtons cancel={() => navigate('/races')} />
          </form> :
          <p className="uk-text-center"><i className="fas fa-2x fa-sync fa-spin"></i></p>
        }
      </div>
    </div>
  </div>
}

export default RaceForm