import { FC } from "react";
import { formatDate } from "../../../../libs/formatting/date";
import { useCampaignSelector } from "../../store";

const Dashboard: FC = () => {
  const campaign = useCampaignSelector(state => state.campaign.campaign)
  const logs = useCampaignSelector(state => state.campaign.logs)

  if (!campaign) return null

  return <div id="dashboard">
    <h1>{campaign.name}</h1>
    <div className="uk-section uk-section-default" data-uk-grid>
      {
        campaign?.description?.length > 0 ?
          <div className="uk-width-1-2">
            <h3>Welcome to {campaign.name}!</h3>
            <div className="uk-margin" dangerouslySetInnerHTML={{ __html: campaign.description }} />
          </div> : null
      }
      <div className={`uk-width-1-${campaign.description && campaign.description.length > 0 ? 2 : 1}`}>
        <h3>Recent activity</h3>
        {
          logs && logs?.length > 0 ?
            <ul className="uk-list uk-list-divider">
              {
                logs.map((log) => <li>
                  <span dangerouslySetInnerHTML={{ __html: log.message }} />
                  <span className="uk-display-inline-block uk-align-right uk-text-italic">
                    ({formatDate(log.created_at)})
                  </span>
                </li>)
              }
            </ul> :
            <div className="uk-alert uk-alert-primary">
              Nothing to report, you lazy maggots!
            </div>
        }
      </div>
    </div>
  </div>
}

export default Dashboard