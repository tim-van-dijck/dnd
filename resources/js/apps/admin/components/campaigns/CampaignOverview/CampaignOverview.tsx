import { FC } from "react";
import { useCampaignOverviewState } from './CampaignOverview.state'
import { ui } from './CampaignOverview.ui'
import PaginatedTable from "../../../../../components/layout/PaginatedTable";

const CampaignOverview: FC = () => {
  const { campaignRepository, destroy } = useCampaignOverviewState()

  return (
    <div id="campaigns">
      <h1>Campaigns</h1>
      <div className="uk-section uk-section-default">
        {
          (
            campaignRepository.campaigns?.data?.length || 0
          ) > 0 ?
            <PaginatedTable
              actions={ui.actions}
              columns={ui.columns}
              records={campaignRepository.campaigns}
              repository={{
                previous: campaignRepository.previous,
                page: campaignRepository.page,
                next: campaignRepository.next,
                load: campaignRepository.load
              }}
              onAction={(type: string, row) => {
                if (type === 'destroy') destroy(row.id)
              }} />
            : <p className="uk-text-center">
              {
                campaignRepository.campaigns === null ?
                  <i className="fas fa-sync fa-spin fa-2x"></i> :
                  <span>No campaigns found</span>
              }
            </p>
        }
      </div>
    </div>
  )
}

export default CampaignOverview