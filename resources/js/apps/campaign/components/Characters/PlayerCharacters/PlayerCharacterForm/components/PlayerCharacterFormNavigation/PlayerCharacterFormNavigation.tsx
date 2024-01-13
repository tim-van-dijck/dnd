import classNames from 'classnames'
import { FC } from 'react'
import { PlayerCharacterFormNavigationProps } from './PlayerCharacterFormNavigation.types'
import { usePlayerCharacterFormTabs, useStyling } from './PlayerCharacterFormNavigation.ui'

const PlayerCharacterFormNavigation: FC<PlayerCharacterFormNavigationProps> = ({
  errors,
  input,
  onNavigate,
  spellcaster,
  tab
}) => {
  const tabs = usePlayerCharacterFormTabs(input, spellcaster, tab, onNavigate)
  const getClasses = useStyling(tabs.enabledTabs, tabs.activeTab)

  return <div id="pc-form-nav">
    <div className="uk-button-group uk-width-expand uk-flex-between uk-visible@m">
      {
        tabs.list.map((tab) => {
          if (tab.hasOwnProperty('condition') && tab.condition === false) return null

          return <button key={tab.key}
                         type="button"
                         className={classNames('uk-button', getClasses(tab.key))}
                         onClick={() => tabs.navigate(tab.key)}>
            {tab.label}
            {
              Object.keys(errors).find(item => item.includes(tab.errorKey || tab.key)) ?
                <i className={classNames(
                  'fas fa-exclamation-triangle',
                  { 'uk-text-danger': tabs.activeTab === tab.key }
                )} />
                : null
            }
          </button>
        })
      }
    </div>

    <nav className="uk-navbar-container uk-hidden@m" data-uk-navbar="dropbar: true; dropbar-mode: push">
      <div className="uk-navbar-center">
        <ul className="uk-navbar-nav">
          <li>
            <button type="button" className="uk-button uk-button-primary uk-width-expand uk-text-center">
              {tabs.activeTab}
              {
                Object.keys(errors).length > 0 ? <i className="fas fa-exclamation-triangle uk-text-danger"></i> : null
              }
            </button>
            <div className="uk-navbar-dropdown uk-navbar-dropdown-width-2">
              <div className="uk-navbar-dropdown-grid uk-child-width" data-uk-grid>
                <div className="uk-width-1-1">
                  <ul className="uk-width uk-nav uk-navbar-dropdown-nav">
                    {
                      tabs.list.map((tab) =>
                        !tab.hasOwnProperty('condition') || !!tab.condition ?
                          <li key={tab.key}>
                            <button className={classNames('uk-button', getClasses(tab.key))}
                                    onClick={(e) => {
                                      e.preventDefault()
                                      tabs.navigate(tab.key)
                                    }}>
                              {tab.label}
                              {
                                Object.keys(errors).find(item => item.includes(tab.errorKey || tab.key)) ?
                                  <i className="fas fa-exclamation-triangle uk-text-danger" />
                                  : null
                              }
                            </button>
                          </li> : null
                      )
                    }
                  </ul>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </nav>
  </div>
}

export default PlayerCharacterFormNavigation
