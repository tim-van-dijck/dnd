import { Link } from "react-router-dom"
import classNames from "classnames";
import { useUserDetailState } from "./UserDetail.state";
import { useParams } from "react-router";

const UserDetail = () => {
  const { id } = useParams() as { id: number | undefined }
  const { user, campaigns, resetPassword } = useUserDetailState(id)

  return <>
    {
      user ?
        <div id="user">
          <h1>
            <Link className="uk-link-text" to="/users"><i className="fas fa-chevron-left" /></Link>
            {user.name} {
            user.admin ? <span title="Campaign">(<i
              className="fas fa-user-shield" />)</span> : null}
          </h1>
          <div data-uk-grid>
            <div className="uk-width-1-1@s uk-width-1-3@l">
              <h2>Info</h2>
              <div className="uk-margin uk-flex uk-flex-between">
                <b>Name</b>
                <span>{user.name}</span>
              </div>
              <div className="uk-margin uk-flex uk-flex-between">
                <b>Email</b>
                <span>{user.email}</span>
              </div>
              <div className="uk-flex uk-flex-between">
                <b>Status</b>
                <span className={classNames(
                  "uk-label",
                  { 'uk-label-success': user.active, 'uk-label-danger': !user.active }
                )}>
                {user.active ? 'Active' : 'Inactive'}
              </span>
              </div>
            </div>
            <div className="uk-width-1-1@s uk-width-2-3@l">
              <h2>Campaigns</h2>
              <table className="uk-table uk-table-divider">
                <tbody>
                {
                  campaigns === null ?
                    <tr>
                      <td className="uk-text-center" colSpan={2}><i className="fas fa-sync fa-spin" /></td>
                    </tr> :
                    (
                      campaigns || []
                    ).length === 0 ?
                      <tr>
                        <td className="uk-text-center uk-text-italic" colSpan={2}>
                          This user is not part of any campaigns
                        </td>
                      </tr> :
                      campaigns.map((campaign) =>
                        <tr key={campaign.id}>
                          <td>{campaign.name}</td>
                          <td>{}</td>
                        </tr>
                      )
                }
                </tbody>
              </table>
            </div>
          </div>
          <div className="uk-flex uk-margin-large-top">
            <Link to={`/users/${user.id}/edit`} className="uk-button uk-button-primary uk-margin-right">
              <i className="fas fa-edit" /> Edit
            </Link>
            <button className="uk-button" onClick={(e) => {
              e.preventDefault()
              resetPassword()
            }}>
              <i className="fas fa-lock" /> Reset password
            </button>
          </div>
        </div> :
        <div className="uk-section uk-section-default">
          <p className="uk-text-center">
            <i className="fas fa-sync fa-spin fa-2x"></i>
          </p>
        </div>
    }
  </>
}

export default UserDetail