import { FC } from "react";
import { useCampaignEntityFormState } from "./CampaignEntityForm.state";
import { useCampaignEntityFormUi } from "./CampaignEntityForm.ui";
import CampaignEntityFormView from "./CampaignEntityForm.view";
import { CampaignEntityFormProps } from "./types";

const CampaignEntityForm: FC<CampaignEntityFormProps> = ({
  id,
  entity,
  fields,
  permissions,
  redirectPath,
  initialValue,
  errors,
  onSubmit
}) => {
  const { redirect, tab, setTab } = useCampaignEntityFormUi(redirectPath)
  const { can, submit, update, input } = useCampaignEntityFormState(initialValue, onSubmit)

  return <CampaignEntityFormView state={{ entity, id, input, submit, update, errors }}
                                 ui={{ can, permissions: !!permissions, fields, tab, setTab, redirect }} />
}

export default CampaignEntityForm