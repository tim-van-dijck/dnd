import classNames from 'classnames'
import { FC } from 'react'
import { Link } from 'react-router-dom'
import PlayerCharacterClassTab from './components/PlayerCharacterClassTab'
import PlayerCharacterInfoTab from './components/PlayerCharacterInfoTab'
import PlayerCharacterPersonalityTab from './components/PlayerCharacterPersonalityTab'
import PlayerCharacterProficiencyTab from './components/PlayerCharacterProficiencyTab'
import PlayerCharacterSpellsTab from './components/PlayerCharacterSpellsTab'
import { PlayerCharacterDetailTabConfig, PlayerCharacterDetailViewProps } from './PlayerCharacterDetail.types'

const PlayerCharacterDetailView: FC<PlayerCharacterDetailViewProps> = ({ state, ui }) => {
  return state.character ?
    <div id="pc-detail">
      <h1>
        <Link className="uk-link-text"
              to={`/characters/${state.character.info.type === 'player' ?
                'players' :
                'npcs'}`}><i className="fas fa-chevron-left" /></Link>
        {state.character.info.name} {state.character.info.private ?
        <span title="This character is private">(<i className="fas fa-user-secret" />)</span> :
        null}
      </h1>
      <div className="uk-section uk-section-default">
        <div className="uk-container padded">
          <ul className="uk-tab">
            {
              ui.tabs.map((tab: PlayerCharacterDetailTabConfig) => tab.condition === undefined || tab.condition() ?
                <li key={tab.key} className={classNames({ 'uk-active': ui.tab === tab.key })}>
                  <a onClick={(e) => ui.navigate(e, tab.key)}>{tab.label}</a>
                </li> : null
              )
            }
          </ul>
          <PlayerCharacterInfoTab character={state.character} active={ui.tab === 'character'} />
          <PlayerCharacterClassTab classes={state.character.classes} active={ui.tab === 'class'} />
          <PlayerCharacterProficiencyTab proficiencies={state.character.proficiencies}
                                         active={ui.tab === 'proficiency'} />
          <PlayerCharacterPersonalityTab personality={state.character.personality} active={ui.tab === 'personality'} />
          {
            state.isSpellcaster ?
              <PlayerCharacterSpellsTab spells={state.character.spells} active={ui.tab === 'spells'} /> :
              null
          }
        </div>
      </div>
    </div> :
    <div className="uk-section uk-section-default">
      <p className="uk-text-center">
        <i className="fas fa-sync fa-spin fa-2x"></i>
      </p>
    </div>
}

export default PlayerCharacterDetailView