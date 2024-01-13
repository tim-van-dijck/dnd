import { FC } from "react";
import { PlayerCharacterPersonalityTabProps } from "./PlayerCharacterPersonalityTab.types";

const PlayerCharacterPersonalityTab: FC<PlayerCharacterPersonalityTabProps> = ({ personality, active }) => {
  if (!active) return null

  return <div className="personality uk-child-width-1-2@m uk-grid-small uk-grid-match" data-uk-grid>
    <div>
      <div className="uk-card uk-card-secondary uk-card-body">
        <h2 className="uk-card-title">Personality trait</h2>
        {
          personality.trait ?
            <div dangerouslySetInnerHTML={{ __html: personality.trait }}></div> :
            <p className="uk-text-center uk-text-italic">This character doesn't have a personality trait</p>
        }
      </div>
    </div>
    <div>
      <div className="uk-card uk-card-secondary uk-card-body">
        <h2 className="uk-card-title">Ideal</h2>
        {
          personality.ideal ?
            <div dangerouslySetInnerHTML={{ __html: personality.ideal }}></div> :
            <p className="uk-text-center uk-text-italic">This character doesn't have an ideal</p>
        }
      </div>
    </div>
    <div>
      <div className="uk-card uk-card-secondary uk-card-body">
        <h2 className="uk-card-title">Bond</h2>
        {
          personality.bond ?
            <div dangerouslySetInnerHTML={{ __html: personality.bond }}></div> :
            <p className="uk-text-center uk-text-italic">This character doesn't have a bond</p>
        }
      </div>
    </div>
    <div>
      <div className="uk-card uk-card-secondary uk-card-body">
        <h2 className="uk-card-title">Flaw</h2>
        {personality.flaw ?
          <div dangerouslySetInnerHTML={{ __html: personality.flaw }} /> :
          <p className="uk-text-center uk-text-italic">This character doesn't have a flaw</p>}
      </div>
    </div>
  </div>
}

export default PlayerCharacterPersonalityTab