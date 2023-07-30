import { Link } from "react-router-dom";
import { FC } from "react";
import { withUser } from "../../../../providers/UserProvider";
import { User } from "@dnd/types";

const Navigation: FC<{ user: User }> = ({ user }) => {
  return (
    <aside id="left-col" className="uk-light uk-visible@m">
      <div className="left-logo uk-flex uk-flex-middle">DUNGEONS & DIARIES</div>
      <div className="left-nav-wrap">
        <ul className="uk-nav uk-nav-default" data-uk-nav>
          {
            user && user.hasOwnProperty('permissions') ?
              <>
                <li className="uk-nav-header">PLATFORM</li>
                <li><Link to="/campaigns"><i className="fas fa-feather-alt fa-fw"></i> Campaigns</Link></li>
                <li><Link to="/users"><i className="fas fa-users-cog fa-fw"></i> Users</Link></li>
                <li className="uk-nav-header">CONTENT</li>
                <li><Link to="/races"><i className="fas fa-users fa-fw"></i> Races</Link></li>
                <li><Link to="/classes"><i className="fas fa-hat-wizard fa-fw"></i> Classes</Link></li>
                <li><Link to="/spells"><i className="fas fa-hand-sparkles fa-fw"></i> Spells</Link></li>
              </> :
              [ ...Array(5) ].map((e, i) => <li key={i}>
                <span className="navigation-placeholder">&nbsp;</span>
              </li>)
          }
        </ul>
      </div>
    </aside>
  )
}

export default withUser(Navigation)