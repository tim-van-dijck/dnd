import { PaginatedTableAction } from '../../../../../components/layout/PaginatedTable/types'
import { useCampaignRepositories } from '../../../providers/CampaignRepositoryProvider'

export const useNoteOverviewTable = (): { actions: PaginatedTableAction[], columns: any[] } => {
  const { AuthRepository } = useCampaignRepositories()

  return (
    {
      columns: [
        {
          title: 'Name',
          name: 'name'
        }
      ],
      actions: [
        {
          name: 'destroy',
          title: 'Delete',
          icon: 'trash',
          classes: 'uk-text-danger',
          condition: ({ id }) => AuthRepository.can('delete', 'note', id)
        },
        {
          name: 'edit',
          title: 'Edit',
          icon: 'edit',
          to: ({ id }) => `/notes/${id}/edit`,
          condition: ({ id }) => AuthRepository.can('edit', 'note', id)
        },
        {
          name: 'view',
          title: 'View',
          icon: 'eye',
          to: ({ id }) => `/notes/${id}`,
          condition: ({ id }) => AuthRepository.can('view', 'note', id)
        }
      ]
    }
  )
}