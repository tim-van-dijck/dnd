import { CharacterClass, PlayerCharacterInput } from '@dnd/types'
import { useState } from 'react'
import { PlayerCharacterFormPage, PlayerCharacterFormTab } from './PlayerCharacterForm.types'

export const usePlayerCharacterFormNavigation = (save: () => Promise<void>) => {
  const [ page, setPage ] = useState<PlayerCharacterFormPage>(PlayerCharacterFormPage.FORM)
  const [ tab, setTab ] = useState<PlayerCharacterFormTab>(PlayerCharacterFormTab.DETAILS)

  const nextOrSave = (condition: boolean, nextTab: PlayerCharacterFormTab) => {
    if (condition) {
      setTab(nextTab)
      return Promise.resolve()
    } else {
      return save()
    }
  }

  return {
    isFormTabActive: (formTab: PlayerCharacterFormTab) => page === 'form' && tab === formTab,
    nextOrSave,
    page,
    tab,
    setPage,
    setTab,
  }
}

export const useTitle = (id: number | undefined, character: PlayerCharacterInput | null): string => {
  if (id) {
    return `Edit ${character?.info?.name || 'character'}`
  } else {
    return 'Add character'
  }
}

export const useSpellcaster = (
  input: PlayerCharacterInput | null,
  classes: Record<number, CharacterClass>
): boolean => {
  if ((
    input?.classes || []
  )?.length === 0 || Object.keys(classes).length === 0) {
    return false
  }

  return input?.classes?.some((characterClass) => {
    if (!characterClass.class_id) return false

    const chosenClass = classes[characterClass.class_id]
    if (chosenClass.spellcaster) return true

    return !!chosenClass?.subclasses?.find(item => item.id == characterClass.subclass_id)?.spellcaster
  }) || false
}

