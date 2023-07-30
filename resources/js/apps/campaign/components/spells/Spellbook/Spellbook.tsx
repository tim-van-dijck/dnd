import { FC } from "react";
import classNames from "classnames";
import { useSpellBookState } from "./Spellbook.state";

const Spellbook: FC = () => {
  const {
    filters,
    setFilters,
    spell,
    setSpell,
    toggleFilter,
    relevantSpells
  } = useSpellBookState()
  return <div id="spellbook">
    <div className="filters">
      Level:
      <select className="uk-select uk-width-auto"
              onChange={(e) => setFilters({ ...filters, level: parseInt(e.target.value) })}>
        <option value="0">Cantrips</option>
        {[ ...Array(9).keys() ].map((level) => <option key={level} value={level}>{level}</option>)}
      </select>
      <div className="uk-button-group">
        <button
          className={classNames({
            'uk-button-primary': !!filters.ritual,
            'uk-button-danger': !filters.ritual
          }, 'uk-button')}
          onClick={(e) => {
            e.preventDefault()
            toggleFilter('ritual')
          }}>
          Ritual
        </button>
        <button
          className={classNames({
            'uk-button-primary': !!filters.concentration,
            'uk-button-danger': !filters.concentration
          }, 'uk-button')}
          onClick={(e) => {
            e.preventDefault()
            toggleFilter('concentration')
          }}>
          Concentration
        </button>
      </div>
      Search:
      <input type="text"
             name="query"
             className="uk-input uk-width-auto"
             onChange={(e) => setFilters({ ...filters, query: e.target.value })} />
      {filters.query.length > 0 ?
        <a className="uk-link-text" href="#"
           onClick={(e) => {
             e.preventDefault()
             setFilters({ ...filters, query: '' })
           }}>
          <i className="fas fa-times"></i>
        </a> : null
      }
    </div>
    <div data-uk-grid>
      <div id="spell-list" className="uk-width-1-4">
        <h3>Spells</h3>
        <ul className="uk-nav uk-nav-default" data-uk-overflow-auto>
          {relevantSpells.map((item) =>
            <li key={item.id} className={classNames({ 'uk-active': spell && item.id === spell.id })}>
              <a href="#" onChange={(e) => {
                e.preventDefault()
                setSpell(item)
              }
              }>
                {item.name}
              </a>
            </li>
          )}
        </ul>
      </div>
      {
        spell ?
          <div className="uk-width-3-4">
            <h3>
              {spell.name}
              {spell.level > 0 ?
                <span className="uk-text-small">
                  (level {spell.level} {spell.school} spell)
                </span> : <span className="uk-text-small">({spell.school} cantrip)</span>
              }
            </h3>
            <ul className="uk-list">
              <li><b>Casting Time:</b> {spell.casting_time}</li>
              <li><b>Range:</b> {spell.range}</li>
              <li><b>Components:</b> {spell.components}</li>
              <li><b>Duration:</b> {spell.duration}</li>
              <li>
                <b>Concentration:</b>
                <i className={classNames(
                  `fas fa-${spell.concentration ? 'check' : 'times'}`,
                  `uk-text-${spell.concentration ? 'success' : 'danger'}`
                )} />
              </li>
              <li>
                <b>Ritual:</b>
                <i className={classNames(
                  `fas fa-${spell.ritual ? 'check' : 'times'}`,
                  `uk-text-${spell.ritual ? 'success' : 'danger'}`
                )} />
              </li>
              <li><b>Description:</b></li>
            </ul>
            <div data-uk-overflow-auto dangerouslySetInnerHTML={{ __html: spell.description }} />
            {
              spell.higher_levels ? <>
                <p><b>At higher levels:</b></p>
                <div dangerouslySetInnerHTML={{ __html: spell.higher_levels }} />
              </> : null
            }
          </div> : null
      }
    </div>
  </div>
}

export default Spellbook