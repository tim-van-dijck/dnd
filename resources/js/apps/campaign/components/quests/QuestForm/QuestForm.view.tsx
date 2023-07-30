import classNames from "classnames";
import { FC } from "react";
import FormButtons from "../../../../../components/layout/form/components/FormButtons";
import InputBoolean from "../../../../../components/layout/form/components/InputBoolean";
import InputRichText from "../../../../../components/layout/form/components/InputRichText";
import InputText from "../../../../../components/layout/form/components/InputText";
import PermissionsForm from "../../common/form/PermissionsForm";
import Objectives from "./components/Objectives";
import { QuestFormViewProps } from "./types";

const QuestFormView: FC<QuestFormViewProps> = ({ state, ui }) => {
  return <div>
    <h1>{ui.title}</h1>
    <div className="uk-section uk-section-default">
      {
        state.input ?
          <form onSubmit={state.save}>
            {ui.can('edit', 'role') ?
              <ul data-uk-tab>
                <li className={classNames({ 'uk-active': ui.tab === 'details' })}>
                  <a href="" onClick={() => ui.setTab('details')}>Details</a>
                </li>
                <li className={classNames({ 'uk-active': ui.tab === 'permissions' })}>
                  <a href="" onClick={() => ui.setTab('permissions')}>Permissions</a>
                </li>
              </ul> : null
            }
            <div className={classNames({ 'uk-hidden': ui.tab !== 'details' })} data-uk-grid>
              <div className="uk-width-1-2">
                <h2>Details</h2>
                <InputText id="title"
                           name="title"
                           label="Title"
                           initialValue={state.input?.title}
                           errors={state.errors?.title}
                           onChange={(value) => state.update('title', value)}
                           required />
                <InputRichText id="description"
                               name="description"
                               label="Description"
                               initialValue={state.input?.description}
                               errors={state.errors?.description}
                               onChange={(value) => state.update('description', value)}
                               required />
                <InputBoolean id="private"
                              name="private"
                              label="Private"
                              initialValue={state.input?.private}
                              errors={state.errors?.private}
                              onChange={(value) => state.update('private', value)}
                              required />
              </div>
              <div className="uk-width-1-2">
                <Objectives value={state.input.objectives}
                            errors={Object.fromEntries(Object.entries(state.errors)
                              .filter(([ field, value ]) => field.includes('objectives')))}
                            onChange={(objectives) => state.update('objectives', objectives)} />
              </div>
            </div>
            <PermissionsForm className={classNames({
              'uk-hidden': ui.tab !==
                'permissions' ||
                !ui.can('edit', 'role')
            })}
                             id={state.id}
                             entity="quest"
                             value={state.input.permissions}
                             onChange={(value) => state.update('permissions', value)} />
            <FormButtons cancel={ui.redirect} />
          </form> :
          <p className="uk-text-center">
            <i className="fas fa-2x fa-sync fa-spin"></i>
          </p>
      }
    </div>
  </div>
}

export default QuestFormView