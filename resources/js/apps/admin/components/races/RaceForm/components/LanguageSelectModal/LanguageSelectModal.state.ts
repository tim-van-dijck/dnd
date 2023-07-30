import { useEffect, useState } from "react";
import { useUpdateField } from "../../../../../utils";
import UIKit from "uikit";
import { useRaceRepository } from "../../../../../repositories/RaceRepository";
import { IFormOption } from "../../../../../../../components/layout/form/types";
import { LanguageSelection } from "../../../../../../../types";

export const useLanguageModalState = (selected: number[], onChange: (value) => void) => {
  const [ input, setInput ] = useState<LanguageSelection | null>({ ...emptyLanguage })
  const { languages } = useRaceRepository()

  useEffect(() => {
    const uk = UIKit.util as any
    uk.on('#lang-select-modal', 'beforehide', reset)
    uk.on('#lang-select-modal', 'beforeshow', () => setInput({ ...emptyLanguage }))
  }, [])

  const options: IFormOption[] = languages?.map(({ id, name }) => (
    { value: id, label: name, disabled: selected.includes(id) }
  )) || []

  const fieldUpdate = useUpdateField(input)
  const update = (field: string, value) => setInput(fieldUpdate(field, value))

  const reset = () => setInput(null)
  const save = () => {
    onChange({ ...input })
    UIKit.modal('#lang-select-modal').hide()
    reset()
  }

  return { input, options, save, update }
}

const emptyLanguage: LanguageSelection = {
  id: 0,
  optional: false
}