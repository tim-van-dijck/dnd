import { Link } from "react-router-dom"
import { useParams } from "react-router";
import { FC } from "react";
import { useRaceDetailState } from "./RaceDetail.state";

const RaceDetail: FC = () => {
  const { id } = useParams() as { id: number | undefined }
  const { race } = useRaceDetailState(id)

  if (!race) return null

  return <div>
    <h1><Link className="uk-link-text" to="/races"><i className="fas fa-chevron-left"></i></Link>{race.name}</h1>
    <div className="uk-section uk-section-default">
      <div className="uk-container padded">
        <div data-uk-grid>
          <div className="uk-width-1-1@s uk-width-1-2@l uk-width-2-3@xl">
            <h2>Description</h2>
            <div dangerouslySetInnerHTML={{ __html: race.description }} />
            {
              race.subraces.length > 0 ? <>
                <h2>Subraces</h2>
                <div className="uk-child-width" data-uk-grid>
                  {race.subraces.map((subrace) => <div key={`subrace-${subrace.id}`}>
                    <div className="uk-card uk-card-body uk-card-secondary">
                      <div className="uk-card-title">{subrace.name}</div>
                    </div>
                  </div>)}
                </div>
              </> : null
            }
          </div>
          <div className="uk-width-1-1@s uk-width-1-2@l uk-width-1-3@xl">
            <h2>Stats</h2>
            <div className="uk-width uk-flex uk-flex-between">
              <b>Size</b>
              <span>{race.size}</span>
            </div>
            <div className="uk-width uk-flex uk-flex-between">
              <b>Speed</b>
              <span>{race.speed}ft.</span>
            </div>
            <div className="uk-width uk-flex uk-flex-between">
              <b>Ability Bonuses</b>
              <div>
                <ul className="uk-list">
                  {
                    race.ability_bonuses.filter((item) => !item.optional)
                      .map((ability) => <li key={`ability-${ability.ability}`}>{ability.ability} +{ability.bonus}</li>)
                  }
                  {
                    race.optional_ability_bonuses > 0 ? <li>{race.optional_ability_bonuses} optional bonuses</li> : null
                  }
                </ul>
              </div>
            </div>
            {
              race.proficiencies.length > 0 ? <div className="uk-width uk-flex uk-flex-between">
                <b>Proficiencies</b>
                <div>
                  <ul className="uk-list">
                    {
                      race.proficiencies.map((proficiency) =>
                        <li key={`proficiency-${proficiency.id}`}>{proficiency.name} {proficiency.optional ?
                          ' (optional)' :
                          ''}</li>)
                    }
                  </ul>
                </div>
              </div> : null
            }
            <div className="uk-width uk-flex uk-flex-between">
              <b>Languages</b>
              <div>
                <ul className="uk-list">
                  {
                    race.languages.filter((language) => !language.optional)
                      .map((language) => <li key={`language-${language.id}`}>{language.name}</li>)
                  }
                  {
                    race.optional_languages > 0 ? <li>{race.optional_languages} optional languages</li> : null
                  }
                </ul>
              </div>
            </div>
            {
              race.optional_feats > 0 ? <div className="uk-width uk-flex uk-flex-between">
                <b>Feat choices</b>
                <span>{race.optional_feats}</span>
              </div> : null
            }
            {
              race.traits.length > 0 ?
                <div className="uk-margin uk-width">
                  <h2>Race Traits</h2>
                  <div className="uk-child-width" data-uk-grid>
                    {
                      race.traits.map((trait) =>
                        <div key={`trait-${trait.id}`}>
                          <div className="uk-card uk-card-body uk-card-secondary">
                            <div className="uk-card-title">{trait.name}</div>
                            <div dangerouslySetInnerHTML={{ __html: trait.description }}></div>
                          </div>
                        </div>
                      )
                    }
                  </div>
                </div> : null
            }
          </div>
        </div>
      </div>
    </div>
  </div>
}

export default RaceDetail