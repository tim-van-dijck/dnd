import { Action } from "@dnd/types";
import { FormEvent } from "react";
import { IFormOption, IFormOptionGroup } from "../../../../../../components/layout/form/types";
import { CampaignEntity } from "../../../../types";

export type CampaignEntityFormField = {
  label: string | JSX.Element
  name: string
  type: 'boolean' | 'select' | 'text' | 'richtext' | 'checkbox' | 'email' | 'number'
  required?: boolean
  options?: (IFormOption | IFormOptionGroup)[]
}

export type UpdateFunction = (
  field: string,
  value: string | number | boolean | string[] | number[] | Record<any, any>
) => void

type CampaignEntityFormTab = 'details' | 'permissions'

export interface CampaignEntityFormProps {
  id?: number
  entity: CampaignEntity
  fields: CampaignEntityFormField[]
  initialValue: Record<string, any> | null
  errors: Record<string, string | string[]>
  permissions?: boolean
  redirectPath: string
  onSubmit: (values: Record<string, any>) => void
}

export interface CampaignEntityFormViewProps {
  state: {
    id?: number
    entity: CampaignEntity
    input: Record<string, any> | null
    submit: (e: FormEvent) => void
    update: UpdateFunction
    errors: Record<string, string | string[]>
  }
  ui: {
    can: (permission: Action, entity: string, id?: number | null) => boolean
    fields: CampaignEntityFormField[]
    permissions: boolean
    redirect: () => void
    setTab: (tab: CampaignEntityFormTab) => void
    tab: CampaignEntityFormTab
  }
}