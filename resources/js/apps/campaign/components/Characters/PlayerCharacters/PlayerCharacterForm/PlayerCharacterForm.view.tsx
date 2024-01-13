import classNames from 'classnames'
import { FC } from 'react'
import PermissionsForm from '../../../Common/Form/PermissionsForm'
import PlayerCharacterFormDetailsTab from './components/PlayerCharacterFormDetailsTab'
import PlayerCharacterFormNavigation from './components/PlayerCharacterFormNavigation'
import {
  PlayerCharacterFormPage,
  PlayerCharacterFormTab,
  PlayerCharacterFormViewProps
} from './PlayerCharacterForm.types'

const PlayerCharacterFormView: FC<PlayerCharacterFormViewProps> = ({ state, ui }) => {
  return <div id="pc-form">
    <h1>{ui.title}</h1>
    <div className="uk-section uk-section-default">
      <div className="uk-container padded">
        {
          !!state.input && ui.loaded ?
            <form id="character-form" className="uk-form-stacked">
              {
                ui.can('edit', 'role') ?
                  <ul data-uk-tab>
                    <li className={classNames({ 'uk-active': ui.navigation.page === PlayerCharacterFormPage.FORM })}>
                      <a href="" onClick={(e) => {
                        e.preventDefault()
                        ui.navigation.setPage(PlayerCharacterFormPage.FORM)
                      }}>Details</a>
                    </li>
                    <li className={classNames({
                      'uk-active': ui.navigation.page ===
                        PlayerCharacterFormPage.PERMISSIONS
                    })}>
                      <a href="" onClick={(e) => {
                        e.preventDefault()
                        ui.navigation.setPage(PlayerCharacterFormPage.PERMISSIONS)
                      }}>Permissions</a>
                    </li>
                  </ul>
                  : null
              }
              {
                ui.navigation.page === PlayerCharacterFormPage.FORM ?
                  <PlayerCharacterFormNavigation
                    input={state.input}
                    spellcaster={ui.spellcaster}
                    tab={ui.navigation.tab}
                    errors={state.errors}
                    onNavigate={ui.navigation.setTab}
                  />
                  : null
              }

              {
                ui.navigation.isFormTabActive(PlayerCharacterFormTab.DETAILS) ?
                  <PlayerCharacterFormDetailsTab value={state.input.info}
                                                 errors={state.errors}
                                                 onUpdate={(info) => state.update('info', info)}
                                                 onNext={() => ui.navigation.setTab(PlayerCharacterFormTab.CLASS)} />
                  : null
              }
              {/*
              <player-character-form-class-tab v-show="ui.navigation.isFormTabActive('class')"
              :input="state.input"
              :errors="state.errors"
              @update="state.setClasses($event)"
              @next="ui.navigation.setTab('background')"/>
              <player-character-form-background-tab v-show="ui.navigation.isFormTabActive('background')"
              :input="state.input.background_id"
              @update="state.setBackground($event)"
              :errors="state.errors"
              @next="ui.navigation.setTab('proficiency')"/>
              <player-character-form-proficiency-tab v-show="ui.navigation.isFormTabActive('proficiency')"
              :input="state.input"
              @update="state.setProficiencies($event)"
              :errors="state.errors"
              @next="ui.navigation.setTab('ability')"/>
              <player-character-form-abilities-tab v-show="ui.navigation.isFormTabActive('ability')"
              :input="state.input"
              @update="state.setAbilityScores($event)"
              :errors="state.errors"
              @next="ui.navigation.setTab('personality')"/>
              <player-character-form-personality-tab v-show="ui.navigation.isFormTabActive('personality')"
              :input="state.input"
              @update="state.setPersonality($event)"
              :spellcaster="ui.spellcaster"
              @next="ui.navigation.nextOrSave(ui.spellcaster, 'spells')"/>
              <player-character-form-spell-tab v-if="ui.spellcaster.value && state.input.classes.length > 0"
                                               v-show="ui.spellcaster.value && ui.navigation.isFormTabActive('spells')"
              :input="state.input"
              :classes="state.input.classes"
              @update="state.setSpells($event)"
              :errors="state.errors"
              @next="state.save"/>*/}

              {
                ui.navigation.page === PlayerCharacterFormPage.PERMISSIONS && ui.can('edit', 'role') ?
                  <PermissionsForm entity="character" id={state.id} value={state.input.permissions} onChange={() => {
                  }} /> : null
              }
            </form> :
            <p className="uk-text-center">
              <i className="fas fa-2x fa-sync fa-spin"></i>
            </p>
        }
      </div>
    </div>
  </div>
}

export default PlayerCharacterFormView