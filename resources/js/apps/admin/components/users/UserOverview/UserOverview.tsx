import PaginatedTable from "../../../../../components/layout/PaginatedTable";
import { ui } from './UserOverview.ui'
import UserInviteModal from "./components/UserInviteModal";
import { useUserOverviewState } from "./UserOverview.state";

const UserOverview = () => {
  const { userRepository, destroy } = useUserOverviewState()

  return (
    <div id="users">
      <h1>Users</h1>
      <div className="uk-section uk-section-default">
        <UserInviteModal onInvite={userRepository.load} />
        {
          (
            userRepository.users?.data?.length || 0
          ) > 0 ?
            <PaginatedTable actions={ui.actions}
                            columns={ui.columns}
                            records={userRepository.users}
                            repository={{
                              previous: userRepository.previous,
                              page: userRepository.page,
                              next: userRepository.next,
                              load: userRepository.load
                            }}
                            onAction={(type: string, row) => {
                              if (type === 'destroy') destroy(row.id)
                            }} /> : <p className="uk-text-center">{userRepository.users === null ?
              <i className="fas fa-sync fa-spin fa-2x"></i> :
              <span>No campaigns found</span>}
            </p>
        }
      </div>
    </div>
  )
}

export default UserOverview