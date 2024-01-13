import classNames from 'classnames'
import { FC } from 'react'
import { RaceInfoModalViewProps } from './RaceInfoModal.types'

const RaceInfoModalView: FC<RaceInfoModalViewProps> = ({ state, ui }) => {
  return <div id="race-list">
    <button className="uk-button uk-button-secondary"
            onClick={(e) => {
              e.preventDefault()
              ui.open()
            }}>Race list
    </button>
    <div id="race-info-modal" data-uk-modal>
      <div className="uk-width-expand uk-modal-dialog uk-modal-body">
        <h2 className="uk-modal-title">{ui.race?.name || 'Races'}</h2>
        <div data-uk-grid>
          <div className="uk-width-1-5">
            <ul className="uk-nav uk-nav-default">
              {
                state.races.map((race) => <li key={race.id}
                                              className={classNames({ 'uk-active': ui.race?.id === race?.id })}>
                  <a href="#" onClick={(e) => {
                    e.preventDefault()
                    ui.setActiveRace(race.id)
                  }}>{race?.name}</a></li>)
              }
            </ul>
          </div>
          <div className="uk-width-4-5">
            {
              ui.race ?
                <div>
                  <ul data-uk-tab>
                    <li className={classNames({ 'uk-active': ui.tab.key === 'description' })}>
                      <a href="#" onClick={(e) => {
                        e.preventDefault()
                        ui.tab.set('description')
                      }}>Description</a>
                    </li>
                    {
                      ui.race.traits.length > 0 ?
                        <li className={classNames({ 'uk-active': ui.tab.key === 'traits' })}>
                          <a href="#" onClick={(e) => {
                            e.preventDefault()
                            ui.tab.set('traits')
                          }}>Traits</a>
                        </li> : null
                    }
                    {
                      ui.race.subraces.length > 0 ?
                        <li className={classNames({ 'uk-active': ui.tab.key === 'subraces' })}>
                          <a href="#" onClick={(e) => {
                            e.preventDefault()
                            ui.tab.set('subraces')
                          }}>Subraces</a>
                        </li> : null
                    }
                  </ul>
                  {
                    ui.tab.key === 'description' ?
                      <div>
                        <ul className="uk-list">
                          <li><b>Ability Scores:</b> {ui.race.ability_scores}</li>
                          {
                            ui.race.proficiencies.skills.length > 0 ?
                              <li><b>Skills:</b>{ui.race.proficiencies.skills.join(', ')}</li>
                              : null
                          }
                          {
                            ui.race.proficiencies.tools.length > 0 ?
                              <li><b>Tools:</b>{ui.race.proficiencies.tools.join(', ')}</li>
                              : null
                          }
                          {
                            ui.race.proficiencies.weapons.length > 0 ?
                              <li><b>Weapons:</b>{ui.race.proficiencies.weapons.join(', ')}</li>
                              : null
                          }
                          {
                            ui.race.proficiencies.armor.length > 0 ?
                              <li><b>Armor:</b>{ui.race.proficiencies.armor.join(', ')}</li>
                              : null
                          }
                          {
                            ui.race.optional_proficiencies > 0 ?
                              <li><b>Choose {ui.race.optional_proficiencies} of:</b>{' '}
                                {ui.race.proficiencies.optional.join(', ')}</li>
                              : null
                          }
                        </ul>
                        <h3>Race description</h3>
                        <div dangerouslySetInnerHTML={{ __html: ui.race.description }} data-uk-overflow-auto />
                      </div>
                      : null
                  }
                  {
                    ui.race.traits.length > 0 && ui.tab.key === 'traits' ?
                      <div data-uk-grid>
                        <div className="uk-width-1-3">
                          <ul className="uk-nav uk-nav-default">
                            {
                              ui.race.traits.map((trait) =>
                                <li key={trait.id}
                                    className={classNames({ 'uk-active': ui.trait?.id === trait.id })}>
                                  <a href="#" onClick={e => {
                                    e.preventDefault()
                                    ui.setActiveRaceTrait(trait.id)
                                  }}>{trait.name}</a>
                                </li>)
                            }
                          </ul>
                        </div>
                        <div className="uk-width-2-3 class-specs">
                          <h4>{ui.trait?.name}</h4>
                          <div dangerouslySetInnerHTML={{ __html: ui.trait?.description || '' }} />
                        </div>
                      </div>
                      : null
                  }
                  {
                    ui.tab.key === 'subraces' ?
                      <div>
                        <ul data-uk-tab>
                          {
                            ui.race.subraces.map(subrace =>
                              <li key={subrace.id}
                                  className={classNames({ 'uk-active': ui.subrace?.id === subrace.id })}>
                                <a href="#" onClick={(e) => {
                                  e.preventDefault()
                                  ui.setActiveSubrace(subrace.id)
                                }}>{subrace.name}</a>
                              </li>
                            )
                          }
                        </ul>
                        {
                          ui.race.subraces.map((subrace) =>
                            ui.subrace?.id === subrace.id ?
                              <div key={subrace.id} className="subrace-info">
                                <div className="class-description"
                                     dangerouslySetInnerHTML={{ __html: subrace.description }} />
                                <ul className="uk-list">
                                  {
                                    ui.subrace.ability_scores.length > 0 ?
                                      <li><b>Ability Scores:</b>{ui.subrace.ability_scores}</li>
                                      : null
                                  }
                                  {
                                    ui.subrace?.proficiencies?.skills.length > 0 ?
                                      <li><b>Skills:</b>{ui.subrace.proficiencies.skills.join(', ')}</li>
                                      : null
                                  }
                                  {
                                    ui.subrace.proficiencies.tools.length > 0 ?
                                      <li><b>Tools:</b>{ui.subrace.proficiencies.tools.join(', ')}</li>
                                      : null
                                  }
                                  {
                                    ui.subrace.proficiencies.weapons.length > 0 ?
                                      <li><b>Weapons:</b>{ui.subrace.proficiencies.weapons.join(', ')}</li>
                                      : null
                                  }
                                  {
                                    ui.subrace.proficiencies.armor.length > 0 ?
                                      <li><b>Armor:</b>{ui.subrace.proficiencies.armor.join(', ')}</li>
                                      : null
                                  }
                                  {
                                    ui.subrace.optional_proficiencies > 0 ?
                                      <li><b>Choose {ui.subrace.optional_proficiencies} of:</b>
                                        {ui.subrace.proficiencies.optional.join(', ')}
                                      </li>
                                      : null
                                  }
                                </ul>
                                <h3>Traits</h3>
                                <div data-uk-grid>
                                  <div className="uk-width-1-3">
                                    <ul className="uk-nav uk-nav-default">
                                      {
                                        ui.subrace.traits.map((trait) =>
                                          <li key={trait.id}
                                              className={classNames({
                                                'uk-active': ui.subraceTrait?.id ===
                                                  trait.id
                                              })}>
                                            <a href="#" onClick={(e) => {
                                              e.preventDefault()
                                              ui.setActiveSubraceTrait(trait.id)
                                            }}>{trait.name}</a>
                                          </li>
                                        )
                                      }
                                    </ul>
                                  </div>
                                  {
                                    ui.subraceTrait ?
                                      <div className="uk-width-2-3">
                                        <h4>{ui.subraceTrait.name}</h4>
                                        <div dangerouslySetInnerHTML={{ __html: ui.subraceTrait.description }} />
                                      </div> : null
                                  }
                                </div>
                              </div>
                              : null
                          )
                        }
                      </div>
                      : null
                  }
                </div>
                : <p className="uk-text-center"><i className="fas fa-2x fa-sync fa-spin" /></p>
            }
          </div>
        </div>
        <button className=" uk-modal-close-default uk-close-large" type="button" data-uk-close />
      </div>
    </div>
  </div>
}

export default RaceInfoModalView