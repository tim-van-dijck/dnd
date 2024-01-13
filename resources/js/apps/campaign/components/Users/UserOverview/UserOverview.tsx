import { FC } from "react";
import UserFormModal from "./components/UserFormModal";
import { useUserOverview } from "./UserOverview.state";

const UserOverview: FC = () => {
  const { can, invite, input, me, onFinish, edit, userRepository, destroy } = useUserOverview()

  return <div id="users">
    <h1>Users</h1>
    <div className="uk-section uk-section-default uk-padding-remove-top">
      <button className="uk-button uk-button-primary" onClick={invite}>
        <i className="fas fa-plus" /> Invite user
      </button>
      {
        userRepository.users != null && userRepository.users.length > 0 ?
          <table className="uk-table uk-table-divider">
            <thead>
            <tr>
              <th></th>
              <th>Name</th>
              <th>Role</th>
            </tr>
            </thead>
            <tbody>
            {
              userRepository.users.map((user) => <tr>
                <td className="uk-width-small">
                  <ul className="uk-iconnav">
                    {
                      can('delete', 'user') && user.id !== me?.id ?
                        <li><a title="Revoke campaign access"
                               className="uk-text-danger"
                               onClick={(e) => {
                                 e.preventDefault()
                                 destroy(user.id);
                               }}><i className="fas fa-ban" /></a>
                        </li> : null
                    }
                    {
                      can('edit', 'user') ?
                        <li><a title="Edit user" onClick={(e) => {
                          e.preventDefault()
                          edit(user);
                        }}><i className="fas fa-edit" /></a></li> : null
                    }
                  </ul>
                </td>
                <td>{user.name != '' ? user.name : user.email}</td>
                <td>{user.role}</td>
              </tr>)
            }
            </tbody>
          </table> :
          <p className="uk-text-center">
            {userRepository.users === null ?
              <i className="fas fa-sync fa-spin fa-2x" /> :
              <span>No users found</span>
            }
          </p>
      }
    </div>
    <UserFormModal user={input} onFinish={onFinish} />
  </div>
}

export default UserOverview