import { Link } from "react-router-dom";
import { useRoleOverviewState } from './RoleOverview.state'

const RoleOverview = () => {
  const { roles, destroy } = useRoleOverviewState()
  return <div id="roles">
    <h1>Roles</h1>
    <div className="uk-section uk-section-default uk-padding-remove-top">
      <Link className="uk-button uk-button-primary" to="/roles/create"><i className="fas fa-plus" /> Add role</Link>
      {
        roles !== null && roles?.data?.length > 0 ?
          <table className="uk-table uk-table-divider">
            <thead>
            <tr>
              <th></th>
              <th>Name</th>
            </tr>
            </thead>
            <tbody>
            {
              roles.data.map((role) => <tr key={role.id}>
                <td className="uk-width-small">
                  {
                    role.system ? null :
                      <ul className="uk-iconnav">
                        <li>
                          <a className="uk-text-danger" onClick={(e) => {
                            e.preventDefault()
                            destroy(role.id)
                          }}><i className="fas fa-trash" /></a>
                        </li>
                        <li>
                          <Link to={`/roles/${role.id}/edit`}><i className="fas fa-edit" /></Link>
                        </li>
                      </ul>
                  }
                </td>
                <td>{role.name}</td>
              </tr>)
            }
            </tbody>
          </table> : <p className="uk-text-center">
            {roles === null ? <i className="fas fa-sync fa-spin fa-2x" /> : <span>No roles found</span>}
          </p>
      }
    </div>
  </div>
}

export default RoleOverview
