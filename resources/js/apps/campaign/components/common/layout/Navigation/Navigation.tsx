import classNames from "classnames";
import { Link } from "react-router-dom";
import { useAuthRepository } from "../../../../../../repositories/AuthRepository";
import SpellbookButton from "../../../spells/SpellbookButton";
import SpellbookModal from "../../../spells/SpellbookModal";
import { useNavigationState } from "./Navigation.state";

const Navigation = () => {
  const { user } = useAuthRepository()
  const { logout, navigation } = useNavigationState()

  if (!user) return null

  return <aside id="left-col" className="uk-light uk-visible@m">
    <div className="left-logo uk-flex uk-flex-middle">DUNGEONS & DIARIES</div>
    {
      user && user.hasOwnProperty('permissions') ? <div className="left-nav-wrap">
        <ul className="uk-nav uk-nav-default" data-uk-nav>
          {
            navigation.map((section) => (
              [
                <li key={`section-${section.title}`} className="uk-nav-header">{section.title}</li>,
                ...section.items.map((item) => <li key={`section-${section.title}-${item.to}`} className={classNames({
                  'uk-open': window.location.pathname ===
                    item.to
                })}>
                  <Link to={item.to}><i className={`fas fa-${item.icon} fa-fw`}></i> {item.title}</Link>
                </li>)
              ]
            ))
          }
        </ul>
      </div> : null
    }
    <div className="bar-bottom">
      <ul className="uk-subnav uk-flex uk-flex-center uk-child-width-1-5" data-uk-grid>
        <li><Link to="/dashboard" title="Home"><i className="fas fa-home fa-fw"></i></Link></li>
        <li><a href="/profile" title="Settings"><i className="fas fa-sliders-h fa-fw"></i></a></li>
        <li><SpellbookButton icon={true} name="spellbook-modal" /></li>
        <li>
          <a href="/logout" title="Sign out" onClick={logout}>
            <i className="fas fa-sign-out-alt fa-fw"></i>
          </a>
        </li>
      </ul>
    </div>
    <SpellbookModal name="navbar-spellbook" />
  </aside>
}

export default Navigation