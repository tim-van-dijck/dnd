import { useLevels } from './PlayerCharacterSpellsTab.state'

const PlayerCharacterSpellsTab = ({ active, spells }) => {
  const levels = useLevels(spells)

  if (!active) return null

  return <div>
    <button data-uk-toggle="target: #spellbook-modal" className="uk-button uk-button-secondary" type="button">
      <i className="fas fa-book"></i> Spellbook
    </button>
    <div className="uk-margin spells-known">
      {
        (
          spells.cantrips.length + spells.spells.length
        ) > 0 ?
          levels.map((level) =>
            <div className="spell-level">
              <h3>{level.title}</h3>
              <div className="uk-child-width-1-2@m uk-grid-small uk-grid-match" data-uk-grid>
                {level.spells.map((spell) => <div>
                  <div className="uk-card uk-card-body uk-card-primary">
                    <div className="uk-card-title">{spell.name}</div>
                    <p>({spell.school})</p>
                  </div>
                </div>)}
              </div>
            </div>
          ) :
          <div className="uk-alert-primary" data-uk-alert>
            Character doesn't have any known spells
          </div>
      }
    </div>
  </div>
}

export default PlayerCharacterSpellsTab