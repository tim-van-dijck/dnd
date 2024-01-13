import { FormEvent, useEffect, useState } from "react";
import { useUpdateField } from "../../../../../admin/utils";
import { useCampaignRepositories } from "../../../../providers/CampaignRepositoryProvider";

export const useCampaignEntityFormState = (
  initialValue: Record<string, any> | null,
  onSubmit: (values: Record<string, any>) => void
) => {
  const { AuthRepository } = useCampaignRepositories()
  const [ input, setInput ] = useState(initialValue ? { ...initialValue } : null)
  const updateField = useUpdateField(input)

  useEffect(() => {
    if (initialValue !== null) setInput({ ...initialValue })
  }, [ JSON.stringify(initialValue) ])
  const submit = (e: FormEvent) => {
    e.preventDefault()
    if (input !== null) onSubmit(input)
  }

  const update = (field: string, value) => setInput(updateField(field, value))

  return { can: AuthRepository.can, input, submit, update }
}