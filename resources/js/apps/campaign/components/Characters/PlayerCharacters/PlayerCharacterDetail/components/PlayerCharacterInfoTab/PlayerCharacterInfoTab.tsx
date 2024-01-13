import { FC } from 'react'
import { Link } from 'react-router-dom'
import { usePlayerCharacterInfo } from './PlayerCharacterInfoTab.state'
import { PlayerCharacterInfoTabProps } from './PlayerCharacterInfoTab.types'

const PlayerCharacterInfoTab: FC<PlayerCharacterInfoTabProps> = ({ character, active }) => {
  const { abilityScores, alignment } = usePlayerCharacterInfo(character)

  if (!active) return null

  return <div id="info-tab" data-uk-grid>
    <div className="uk-width-1-1@s uk-width-1-2@l uk-width-1-3@xl">
      <h2>Info</h2>
      <div className="uk-flex uk-flex-between">
        <b>Name</b>
        <span>{character.info.name} {character.info.dead ? <span title="This character is dead">(<i
          className="fas fa-skull"></i>)</span> : null}</span>
      </div>
      <div className="uk-flex uk-flex-between">
        <b>Race</b>
        <span>{
          `${character.race.name} ${character.race.subrace
            ? '(' + character.race.subrace.name + ')'
            : ''}`
        }</span>
      </div>
      <div className="uk-flex uk-flex-between">
        <b>Alignment</b>
        <span>{alignment}</span>
      </div>
      <div className="uk-flex uk-flex-between">
        <b>Age</b>
        <span>{character.info.age}</span>
      </div>
      {character.info.inventory_id ?
        <Link className="uk-button uk-margin uk-width" to={`/inventory/${character.info.inventory_id}`}>
          <i className="fas fa-shopping-bag" /> To inventory</Link> : null
      }
      <div className="uk-width uk-margin">
        <h2>Race Traits</h2>
        <div className="uk-alert-primary" data-uk-alert>These aren't available yet.</div>
      </div>
    </div>
    <div className="uk-width-1-1@s uk-width-1-2@l uk-width-2-3@xl">
      <h2>Ability Scores</h2>
      <div className="uk-child-width-1-6@m uk-child-width-1-3 uk-grid-small uk-grid-match" data-uk-grid>
        {abilityScores.map(({ name, score, bonus }) =>
          <div key={name}>
            <h3 className="uk-text-center uk-margin-remove uk-text-bold">{name}</h3>
            <h3 className="uk-text-center uk-margin-top uk-margin-bottom">{bonus}</h3>
            <p className="uk-text-center uk-margin-remove">{score}</p>
          </div>
        )}
      </div>
      <h2>Bio</h2>
      {
        (
          character.info.bio?.length || 0
        ) > 0 ?
          <div className="uk-card uk-card-body uk-card-secondary"
               dangerouslySetInnerHTML={{ __html: character.info.bio! }} /> :
          <div className="uk-alert-primary" data-uk-alert>This character has no bio</div>
      }
    </div>
  </div>
}

export default PlayerCharacterInfoTab