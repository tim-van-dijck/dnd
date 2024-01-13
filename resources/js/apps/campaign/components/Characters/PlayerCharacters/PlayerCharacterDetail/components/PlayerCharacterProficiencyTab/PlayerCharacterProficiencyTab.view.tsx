import { FC } from 'react'
import { PlayerCharacterProficiencyTabViewProps } from './PlayerCharacterProficiencyTab.types'

const PlayerCharacterProficiencyTabView: FC<PlayerCharacterProficiencyTabViewProps> = ({ state, ui }) =>
  <div id="proficiency-tab">
    <div className="languages uk-margin">
      <h2>Languages</h2>
      {
        ui.loading ?
          <p className="uk-text-center"><i className="fas fa-sync fa-spin fa-2x"></i></p> :
          <div className="uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-grid-small uk-grid-match"
               data-uk-grid>
            {
              state.characterLanguages.map((language) =>
                <div>
                  <div className="uk-card uk-card-body uk-card-primary">
                    <div className="uk-card-title">{language.name}</div>
                    <p><em>{language.script ? language.script : 'No'} script</em></p>
                  </div>
                </div>)
            }
          </div>
      }
    </div>
    <div className="skills uk-margin">
      <h2>Skills</h2>
      <div className="uk-child-width-1-2@s uk-child-width-1-3@l uk-child-width-1-4@xl uk-grid-small uk-grid-match"
           data-uk-grid>
        {
          state.skills.map((skill) =>
            <div>
              <div className="uk-card uk-card-body uk-card-primary">
                <div className="uk-card-title">{skill.name}</div>
                <p><em>({skill.origin} proficiency)</em></p>
              </div>
            </div>
          )
        }
      </div>
    </div>
    <div className="tools uk-margin">
      <h2>Tools</h2>
      {
        state.tools.length > 0 ?
          <div className="uk-child-width-1-2@s uk-child-width-1-3@l uk-child-width-1-4@xl uk-grid-small uk-grid-match"
               data-uk-grid>
            {
              state.tools.map((tool) => <div>
                <div className="uk-card uk-card-body uk-card-primary">
                  <div className="uk-card-title">{tool.name}</div>
                  <p><em>({tool.origin} proficiency)</em></p>
                </div>
              </div>)
            }
          </div> :
          <div className="uk-alert-primary" data-uk-alert>
            This character doesn't have Tool proficiencies
          </div>
      }
    </div>
    <div className="instruments uk-margin">
      <h2>Instruments</h2>
      {
        state.instruments.length > 0 ?
          <div className="uk-child-width-1-2@s uk-child-width-1-3@l uk-child-width-1-4@xl uk-grid-small uk-grid-match"
               data-uk-grid>
            {
              state.instruments.map((instrument) => <div>
                <div className="uk-card uk-card-body uk-card-primary">
                  <div className="uk-card-title">{instrument.name}</div>
                  <p><em>({instrument.origin} proficiency)</em></p>
                </div>
              </div>)
            }
          </div> :
          <div className="uk-alert-primary" data-uk-alert>
            This character doesn't have Instrument proficiencies
          </div>
      }
    </div>
    <div className="weapons-armor uk-margin">
      <h2>Weapons & armor</h2>
      <ul className="uk-list">
        {state.armor.length > 0 ?
          <li><i className="fas fa-fw fa-shield-halved" /> {state.armor.join(', ')}</li> :
          <li>No armor proficiencies</li>}
        {state.weapons.length > 0 ?
          <li><i className="fas fa-fw fa-hand-fist" /> {state.weapons.join(', ')}</li> :
          <li>No weapon proficiencies</li>}
      </ul>
    </div>
  </div>

export default PlayerCharacterProficiencyTabView