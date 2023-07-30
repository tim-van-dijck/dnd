import { Link } from "react-router-dom"
import classNames from "classnames";
import { useSpellDetailState } from "./SpellDetail.state";
import { useParams } from "react-router";

const SpellDetail = () => {
  const { id } = useParams() as { id: number | undefined }
  const { spell, components } = useSpellDetailState(id)

  return <>
    {
      spell ?
        <div id="user">
          <h1>
            <Link className="uk-link-text" to="/spells"><i className="fas fa-chevron-left" /></Link>
            {spell.name}
          </h1>
          <div data-uk-grid>
            <div className="uk-width-1-1@s uk-width-1-3@l">
              <h2>Info</h2>
              <div className="uk-margin uk-flex uk-flex-between">
                <b>Level</b>
                <span>{spell.level === 0 ? 'Cantrip' : `Level ${spell.level}`}</span>
              </div>
              <div className="uk-margin uk-flex uk-flex-between">
                <b>School</b>
                <span>{spell.school}</span>
              </div>
              <div className="uk-margin uk-flex uk-flex-between">
                <b>Range</b>
                <span>{spell.range}</span>
              </div>
              <div className="uk-margin uk-flex uk-flex-between">
                <b>Casting time</b>
                <span>{spell.casting_time}</span>
              </div>
              <div className="uk-margin uk-flex uk-flex-between">
                <b>Duration</b>
                <span>{spell.duration}</span>
              </div>
              <div className="uk-margin uk-flex uk-flex-between">
                <b>Components</b>
                <span>{components}</span>
              </div>
              <div className="uk-flex uk-flex-between">
                {spell.concentration ? <span className={classNames("uk-label")}>Concentration</span> : null}
                {spell.ritual ? <span className={classNames("uk-label")}>Ritual</span> : null}
              </div>
            </div>
            <div className="uk-width-1-1@s uk-width-2-3@l">
              <h2>Description</h2>
              <div dangerouslySetInnerHTML={{ __html: spell.description }}></div>
              {spell.higher_levels?.length > 0 ?
                <>
                  <h4>At higher levels</h4>
                  <p>{spell.higher_levels}</p>
                </>
                : null}
            </div>
          </div>
        </div> :
        <div className="uk-section uk-section-default">
          <p className="uk-text-center">
            <i className="fas fa-sync fa-spin fa-2x"></i>
          </p>
        </div>
    }
  </>
}

export default SpellDetail