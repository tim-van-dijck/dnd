import { useCampaignFormState } from './CampaignForm.state'
import InputText from "../../../../../components/layout/form/components/InputText";
import FormButtons from "../../../../../components/layout/form/components/FormButtons";
import InputRichText from "../../../../../components/layout/form/components/InputRichText";
import { useParams } from "react-router";
import { useNavigate } from "react-router-dom";

const CampaignForm = () => {
  const { id } = useParams()
  const { campaign, errors, onUpdate, save } = useCampaignFormState(id)
  const navigate = useNavigate()

  const title = id
    ? `Edit ${campaign ? campaign.name : 'campaign'}`
    : 'Add campaign'

  return (
    <div>
      <h1>{title}</h1>
      <div className="uk-section uk-section-default">
        <div className="uk-container padded">
          {
            campaign ?
              <form className="uk-form-stacked" onSubmit={(e) => {
                e.preventDefault()
                void save()
              }}>
                <InputText
                  id="name"
                  name="name"
                  label="Name"
                  initialValue={campaign.name}
                  errors={errors?.name}
                  onChange={(value) => onUpdate('name', value)}
                />
                <InputRichText id="description" name="description" label="Description"
                               initialValue={campaign.description}
                               errors={errors?.description}
                               onChange={(value) => onUpdate('description', value)} />
                <FormButtons cancel={() => navigate("/campaigns")} />
              </form>
              :
              <p className="uk-text-center">
                <i className="fas fa-2x fa-sync fa-spin"></i>
              </p>
          }
        </div>
      </div>
    </div>
  )
}

export default CampaignForm