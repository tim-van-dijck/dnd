import classNames from 'classnames'
import { FC } from 'react'
import { PlayerCharacterClassTabViewProps } from './PlayerCharacterClassTab.types'

const PlayerCharacterClassTabView: FC<PlayerCharacterClassTabViewProps> = ({ state, ui }) => {
  return <div id="class-tab" className="uk-width">
    {
      !state.loading ?
        state.characterClasses.map((charClass) =>
          <div key={charClass.id} className="uk-card uk-card-body uk-card-secondary">
            {
              charClass.id ?
                <div data-uk-accordion="active: 0;">
                  <div className="accordion">
                    <div className="uk-accordion-title uk-card-title">
                      Level {charClass.level}
                      {charClass.subclass ? charClass.subclass.name : ''}
                      {charClass.name}
                    </div>
                    <div className="uk-accordion-content uk-form-horizontal">
                      <h3>Features</h3>
                      <div data-uk-grid>
                        <div className="uk-width-1-3">
                          <ul className="uk-nav uk-nav-default">
                            {
                              charClass.features.map((feature) =>
                                feature.choose === 0 ? <li key={feature.id} className={classNames({
                                  'uk-active': charClass.activeFeature ===
                                    feature
                                })}>
                                  <a href="#" onClick={(e) => {
                                    e.preventDefault()
                                    ui.setActive(charClass.id, feature)
                                  }}>{feature.name}</a>
                                </li> : feature.choices?.map((choice) => <li
                                    className={classNames({ 'uk-active': charClass.activeFeature === choice })}>
                                    <a href="#" onClick={(e) => {
                                      e.preventDefault()
                                      ui.setActive(charClass.id, choice)
                                    }}>
                                      {choice.name}
                                    </a>
                                  </li>
                                ))
                            }
                          </ul>
                        </div>
                        {
                          ui.activeFeatures[charClass.id] ?
                            <div className="uk-width-2-3 class-specs">
                              <h4>{ui.activeFeatures[charClass.id].name}</h4>
                              <div dangerouslySetInnerHTML={{ __html: ui.activeFeatures[charClass.id].description }}></div>
                            </div> : null
                        }
                      </div>
                    </div>
                  </div>
                </div> : null
            }
          </div>) :
        <p className="uk-text-center"><i className="fas fa-2x fa-sync fa-spin"></i></p>
    }
  </div>
}

export default PlayerCharacterClassTabView