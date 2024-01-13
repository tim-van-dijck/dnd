import { CampaignEntityFormField, UpdateFunction } from "../../types";

export interface FieldSelectorProps {
  field: CampaignEntityFormField
  update: UpdateFunction
  initialValue: string | boolean | number | string[] | number[] | Record<any, any>
  errors: Record<string, string | string[]>
}