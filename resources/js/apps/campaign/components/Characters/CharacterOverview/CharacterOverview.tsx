import classNames from "classnames";
import { FC } from "react";
import { Link, Outlet, useLocation } from "react-router-dom";

const CharacterOverview: FC = () => {
  const location = useLocation()

  return <div id="characters">
    <h1>Characters</h1>
    <div className="uk-section uk-section-default uk-padding-remove-top">
      <ul className="uk-tab">
        <li className={classNames({
          'uk-active': [
            '/characters/players',
            '/characters'
          ].includes(location.pathname)
        })}>
          <Link to="players">Player Characters</Link>
        </li>
        <li className={classNames({ 'uk-active': location.pathname == '/characters/npcs' })}>
          <Link to="npcs">NPC's</Link>
        </li>
      </ul>
      <Outlet />
    </div>
  </div>
}

export default CharacterOverview