import { useCampaignRepositories } from "../../../providers/CampaignRepositoryProvider";

export const useLocationOverviewUI = () => {
  const { AuthRepository } = useCampaignRepositories()

  return (
    {
      can: AuthRepository.can,
      actions: [
        {
          name: 'destroy',
          icon: 'trash',
          classes: 'uk-text-danger'
        },
        {
          name: 'edit',
          icon: 'edit',
          to: (location) => `/locations/${location.id}/edit`
        },
        {
          name: 'view',
          icon: 'eye',
          to: (location) => `/locations/${location.id}`
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
          format: (location) => location ? location.name || 'N/A' : 'N/A'
        }
      ]
    }
  );
}