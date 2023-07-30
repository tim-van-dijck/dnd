import {FC} from "react";
import {logout} from "../../utils";

const HeaderNavbar: FC<{ left?: JSX.Element }> = ({left}) => {
  return (
    <header id="top-head" className="uk-position-fixed">
      <div className="uk-container uk-container-expand uk-background-primary">
        <nav className="uk-navbar uk-light" data-uk-navbar="mode:click; duration: 250">
          <div className="uk-navbar-left">
            {left}
          </div>
          <div className="uk-navbar-right">
            <ul className="uk-navbar-nav">
              <li><a href="/admin" className="uk-navbar-item">Admin</a></li>
              <li><a href="/campaigns" className="uk-navbar-item">My Campaigns</a></li>
              <li><a href="/profile" className="uk-navbar-item">My Profile</a></li>
              <li>
                <a href="/logout" title="Sign out" className="uk-navbar-item" onClick={logout}>
                  <i className="fas fa-sign-out-alt fa-fw"></i>
                </a>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </header>
  )
}

export default HeaderNavbar