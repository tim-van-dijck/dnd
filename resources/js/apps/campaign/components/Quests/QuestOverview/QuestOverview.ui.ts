import { useCampaignRepositories } from "../../../providers/CampaignRepositoryProvider";

export const useQuestOverviewUI = () => {
  const { AuthRepository } = useCampaignRepositories()

  return (
    {
      actions: [
        {
          name: 'destroy',
          icon: 'trash',
          classes: 'uk-text-danger'
        },
        {
          name: 'edit',
          icon: 'edit',
          to: ({ id }) => `/quests/${id}/edit`
        },
        {
          name: 'view',
          icon: 'eye',
          to: ({ id }) => `/quests/${id}`
        }
      ],
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
  );
}