import { FC } from "react";
import { SpellbookButtonProps } from "./types";

const SpellbookButton: FC<SpellbookButtonProps> = ({ button, children, icon, name }) => {
  if (button) {
    return <button data-uk-toggle="target: #spellbook-modal" className="uk-button uk-button-secondary" type="button">
      {icon ? <i className="fas fa-book fa-fw"></i> : null}
      {children}
    </button>
  }

  return <a href="/" title="Spellbook" data-uk-toggle={`target: #${name}`}>
    {icon ? <i className="fas fa-book fa-fw"></i> : null}
    {children}
  </a>
}

export default SpellbookButton