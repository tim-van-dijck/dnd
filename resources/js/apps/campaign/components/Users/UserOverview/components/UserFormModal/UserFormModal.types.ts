import { CampaignUser, CampaignUserErrors, CampaignUserInput } from "@dnd/types";
import { FormEvent } from "react";
import { IFormOption } from "../../../../../../../components/layout/form/types";

export interface UserFormModalProps {
  user: CampaignUser | null
  onFinish?: () => void
}

export interface UserFormModalViewProps {
  state: {
    input: CampaignUserInput,
    user?: CampaignUser,
    errors: CampaignUserErrors
    update: (field: string, value) => void
    roles: IFormOption[]
    save: (e: FormEvent) => void
    onFinish?: () => void
  }
}