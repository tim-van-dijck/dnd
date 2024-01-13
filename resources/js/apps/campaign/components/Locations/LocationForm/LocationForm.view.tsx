import classNames from 'classnames'
import FormButtons from '../../../../../components/layout/form/components/FormButtons'
import InputBoolean from '../../../../../components/layout/form/components/InputBoolean'
import InputImage from '../../../../../components/layout/form/components/InputImage/InputImage'
import InputRichText from '../../../../../components/layout/form/components/InputRichText'
import InputText from '../../../../../components/layout/form/components/InputText'
import PermissionsForm from '../../Common/Form/PermissionsForm'

const LocationFormView = ({ state, ui }) => {
  return <div>
    <h1>{ui.title}</h1>
    <div className="uk-section uk-section-default">
      {
        state.input ?
          <form id="location-form" className="uk-form-stacked" onSubmit={state.save}>
            {
              ui.can('edit', 'role') ?
                <ul data-uk-tab>
                  <li className={classNames({ 'uk-active': ui.tab === 'details' })}>
                    <a href="" onClick={(e) => {
                      e.preventDefault()
                      ui.setTab('details')
                    }}>Details</a>
                  </li>
                  <li className={classNames({ 'uk-active': ui.tab === 'permissions' })}>
                    <a href="" onClick={(e) => {
                      e.preventDefault()
                      ui.setTab('permissions')
                    }}>Permissions</a>
                  </li>
                </ul> : null
            }
            <div className={classNames({ 'uk-hidden': ui.tab !== 'details' })} data-uk-grid>
              <div className="uk-width-1-2">
                <InputText id="name"
                           name="name"
                           label="Name"
                           initialValue={state.input?.name}
                           errors={state.errors?.name}
                           onChange={(value) => state.update('name', value)}
                           required />
                <InputText id="type"
                           name="type"
                           label="Type"
                           initialValue={state.input?.type}
                           errors={state.errors?.type}
                           onChange={(value) => state.update('type', value)}
                           required />
                <InputImage id="map"
                            name="map"
                            label="Map"
                            initialValue={typeof state.input.map === 'string' ? state.input.map : null}
                            errors={state.errors?.map}
                            onChange={(value) => state.update('map', value)} />

                {/*<div class="uk-margin">*/}
                {/*  <label htmlFor="location"*/}
                {/*         className={classNames("uk-form-label", {'uk-text-danger': state.errors.hasOwnProperty('location_id')})}*/}
                {/*  >Location</label>*/}
                {/*  <v-select id="location"*/}
                {/*            name="location_id"*/}
                {/*            className={classNames("uk-select", {'uk-form-danger': state.errors.hasOwnProperty('location_id')})}*/}
                {/*            onSearch={onSearch}*/}
                {/*            options={locations}*/}
                {/*            reduce={item => item.value}*/}
                {/*            v-model="state.location.location_id" />*/}
                {/*</div>*/}

                <hr />
                <InputBoolean id="private"
                              name="private"
                              label="Private"
                              initialValue={state.input?.private}
                              errors={state.errors?.private}
                              onChange={(value) => state.update('private', value)}
                              required />
              </div>
              <div className="uk-width-1-2">
                <InputRichText id="description"
                               name="description"
                               label="Description"
                               initialValue={state.input?.description}
                               errors={state.errors?.description}
                               onChange={(value) => state.update('description', value)}
                               required />
              </div>
            </div>
            <PermissionsForm
              className={classNames({ 'uk-hidden': ui.tab !== 'permissions' || !ui.can('edit', 'role') })}
              id={state.id}
              entity="location"
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

export default LocationFormView