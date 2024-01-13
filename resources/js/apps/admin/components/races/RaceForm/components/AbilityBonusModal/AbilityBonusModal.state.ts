import { useEffect, useState } from "react";
import UIKit from "uikit";
import { useUpdateField } from "../../../../../utils";
import { IFormOption } from "../../../../../../../components/layout/form/types";
import { AbilityBonusSelection } from "../../../../../../../types";

export const useAbilityBonusModalState = (selected, onChange: (value) => void) => {
  const [ input, setInput ] = useState<AbilityBonusSelection | null>({ ...emptyAbility })
  const fieldUpdate = useUpdateField(input)

  useEffect(() => {
    const uk = UIKit.util as any
    uk.on('#ability-select-modal', 'beforehide', reset)
    uk.on('#ability-select-modal', 'beforeshow', () => setInput({ ...emptyAbility }))
  }, [])

  const update = (field: string, value) => {
    setInput(fieldUpdate(field, value))
  }

  const reset = () => setInput(null)

  const save = () => {
    if (input && input.ability.length === 3) {
      onChange({ ...input })
      UIKit.modal('#ability-select-modal').hide()
      reset()
    }
  }

  const options: IFormOption[] = abilities.map((ability) => (
    { ...ability, disabled: selected.includes(ability.value) }
  ))

  return { input, options, reset, save, update }
}

export const abilities = [
  { value: 'STR', label: 'Strength' },
  { value: 'DEX', label: 'Dexterity' },
  { value: 'CON', label: 'Constitution' },
  { value: 'INT', label: 'Intelligence' },
  { value: 'WIS', label: 'Wisdom' },
  { value: 'CHA', label: 'Charisma' }
]

const emptyAbility: AbilityBonusSelection = {
  id: 0,
  ability: '',
  bonus: 1,
  optional: false
}
