import { PaginatedTableAction } from '../../../../../components/layout/PaginatedTable/types'
import { useCampaignRepositories } from '../../../providers/CampaignRepositoryProvider'

export const useQuestOverviewUI = () => {
  const { AuthRepository } = useCampaignRepositories()

  return (
    {
      actions: [
        {
          name: 'destroy',
          title: 'Delete',
          icon: 'trash',
          classes: 'uk-text-danger',
          condition: ({ id }) => AuthRepository.can('delete', 'quest', id)
        },
        {
          name: 'edit',
          title: 'Edit',
          icon: 'edit',
          to: ({ id }) => `/quests/${id}/edit`,
          condition: ({ id }) => AuthRepository.can('edit', 'quest', id)
        },
        {
          name: 'view',
          title: 'View',
          icon: 'eye',
          to: ({ id }) => `/quests/${id}`
        }
      ] as PaginatedTableAction[],
      can: AuthRepository.can,
      columns: [
        { title: 'Title', name: 'title' },
        {
          title: 'Completion',
          name: 'objectives',
          format: (objectives) => `${objectives.filter((item) => item.status == 1).length}/${objectives.length}`
        },
        {
          title: 'Location',
          name: 'location',
          format: (location) => location || 'Not specified'
        }
      ]
    }
  )
}