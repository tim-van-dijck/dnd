import { Location } from '@dnd/types'
import { useCampaignRepositories } from '../../../providers/CampaignRepositoryProvider'

export const useLocationOverviewUI = () => {
  const { AuthRepository } = useCampaignRepositories()

  return (
    {
      can: AuthRepository.can,
      actions: [
        {
          name: 'destroy',
          title: 'Delete',
          icon: 'trash',
          classes: 'uk-text-danger',
          condition: ({ id }) => AuthRepository.can('delete', 'location', id)
        },
        {
          name: 'edit',
          title: 'Edit',
          icon: 'edit',
          to: ({ id }) => `/locations/${id}/edit`,
          condition: ({ id }) => AuthRepository.can('edit', 'location', id)
        },
        {
          name: 'view',
          title: 'View',
          icon: 'eye',
          to: ({ id }) => `/locations/${id}`,
          condition: ({ id }) => AuthRepository.can('view', 'location', id)
        }
      ],
      columns: [
        {
          name: 'name',
          title: 'Name'
        },
        {
          name: 'type',
          title: 'Type'
        },
        {
          name: 'location',
          title: 'Location',
          format: (location: Location | undefined) => location?.name || 'N/A'
        }
      ]
    }
  )
}