import { FC } from 'react'
import { Link } from 'react-router-dom'
import InputBoolean from '../../../../../../../../components/layout/form/components/InputBoolean'
import InputRichText from '../../../../../../../../components/layout/form/components/InputRichText'
import InputSelect from '../../../../../../../../components/layout/form/components/InputSelect'
import InputText from '../../../../../../../../components/layout/form/components/InputText'
import RaceInfoModal from '../../../../../Common/Domain/RaceInfoModal'
import { PlayerCharacterFormDetailsTabViewProps } from './PlayerCharacterFormDetailsTabView.types'

const PlayerCharacterFormDetailsTabView: FC<PlayerCharacterFormDetailsTabViewProps> = ({ state }) => {
  if (!state.input) return null

  return <div id="info-tab">
    <div data-uk-grid>
      <div className="uk-width-1-2">
        <RaceInfoModal />
        <InputSelect id="race"
                     name="race"
                     label="Race"
                     emptyLabel="Choose a race"
                     options={state.races}
                     errors={state.errors['info.race_id']}
                     initialValue={state.input.race_id}
                     onChange={(value) => state.update('race_id', value)}
                     required />
        <InputSelect id="subrace"
                     name="subrace"
                     label="Subrace"
                     emptyLabel="Choose a subrace"
                     options={state.subraces}
                     errors={state.errors['info.subrace_id']}
                     initialValue={state.input.subrace_id}
                     onChange={(value) => state.update('subrace_id', value)}
                     disabled={state.subraces.length === 0}
                     required />
        <hr />

        <InputText id="name"
                   name="name"
                   label="Name"
                   errors={state.errors['info.name']}
                   initialValue={state.input.name}
                   onChange={(value) => state.update('name', value)}
                   required />
        <InputSelect id="alignment"
                     name="alignment"
                     label="Alignment*"
                     emptyLabel="Choose an alignment"
                     options={state.alignments}
                     errors={state.errors['info.alignment']}
                     initialValue={state.input.alignment}
                     onChange={(value) => state.update('alignment', value)} />
        <InputText id="age"
                   name="age"
                   label="Age"
                   errors={state.errors['info.age']}
                   initialValue={state.input.age}
                   onChange={(value) => state.update('age', value)} />
        <InputBoolean id="dead"
                      name="dead"
                      label="Dead"
                      errors={state.errors['info.dead']}
                      initialValue={state.input.dead}
                      onChange={(value) => state.update('dead', value)} />

        <>
          {
            state.isOwner || state.input.owner_id === null ?
              <>
                {
                  state.isOwner && state.users !== null ?
                    <InputSelect id="owner_id"
                                 name="owner_id"
                                 label="Owner"
                                 emptyLabel="Choose an owner"
                                 options={state.users}
                                 onChange={(value) => state.update('owner_id', value)}
                                 required />
                    : null
                }
                <InputBoolean id="private"
                              name="private"
                              label="Private"
                              initialValue={state.input.private}
                              onChange={(value) => state.update('private', value)} />
              </>
              : null
          }
        </>
      </div>
      <div className="uk-width-1-2">
        <InputRichText id="bio"
                       name="bio"
                       label="Bio"
                       initialValue={state.input.bio}
                       errors={state.errors['info.bio']}
                       onChange={(value) => state.update('bio', value)} />
      </div>
    </div>
    <p className="uk-margin">
      <Link className="uk-button uk-button-danger" to="/characters/players">Cancel</Link>
      <button className="uk-button uk-button-primary uk-align-right" onClick={(e) => {
        e.preventDefault()
        state.next()
      }}
      >Next <i className="fas fa-chevron-right" /></button>
    </p>
  </div>
}

export default PlayerCharacterFormDetailsTabView