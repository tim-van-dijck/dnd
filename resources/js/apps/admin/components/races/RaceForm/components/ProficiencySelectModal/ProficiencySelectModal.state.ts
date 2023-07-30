import { useEffect, useState } from "react";
import { useUpdateField } from "../../../../../utils";
import UIKit from "uikit";
import { useRaceRepository } from "../../../../../repositories/RaceRepository";
import { Proficiency } from "../../../../../../../types";
import { IFormOption, IFormOptionGroup } from "../../../../../../../components/layout/form/types";
import { ProficiencyInput } from "./types";

export const useProficiencyModalState = (selected: number[], onChange: (value: ProficiencyInput) => void) => {
  const [ input, setInput ] = useState<ProficiencyInput | null>(null)
  const fieldUpdate = useUpdateField(input)
  const { proficiencies } = useRaceRepository()
  const options = formatOptions(proficiencies || [], selected)

  useEffect(() => {
    const uk = UIKit.util as any
    uk.on('#proficiency-select-modal', 'beforehide', reset)
    uk.on('#proficiency-select-modal', 'beforeshow', () => setInput({ ...emptyProficiency }))
  }, [])

  const update = (field: string, value: number | boolean) => setInput(fieldUpdate(field, value))

  const reset = () => setInput(null)
  const save = () => {
    onChange({ ...input } as ProficiencyInput)
    UIKit.modal('#proficiency-select-modal').hide()
    reset()
  }

  return { input, options, save, update }
}

const emptyProficiency = {
  id: 0,
  optional: false
}

const formatOptions = (proficiencies: Proficiency[], selected: number[]): IFormOptionGroup[] => {
  return proficiencies
    .map((proficiency) => (
      { label: proficiency.type, type: 'group', children: [] } as IFormOptionGroup
    ))
    .filter((val, i, self) => self.findIndex((item) => val.label === item.label) === i)
    .map((group) => {
      const children: IFormOption[] = proficiencies
        .filter((proficiency) => proficiency.type === group.label)
        .map(({ id, name }) => (
          { value: id, label: name, disabled: selected.includes(id) }
        ))

      return (
        {
          ...group,
          children,
          disabled: children.length === 0
        }
      );
    })
}