import { useEffect, useState } from "react";
import { useRaceRepository } from "../../../../../repositories/RaceRepository";
import UIKit from "uikit";
import { useUpdateField } from "../../../../../utils";
import { Trait, TraitSelection } from "../../../../../../../types";

export const useTraitModalState = (selected: number[], onChange: (value) => void) => {
  const [ input, setInput ] = useState<TraitSelection | null>({ ...emptyTrait })
  const { traits } = useRaceRepository()
  const fieldUpdate = useUpdateField(input)

  useEffect(() => {
    const uk = UIKit.util as any
    uk.on('#trait-select-modal', 'beforehide', reset)
    uk.on('#trait-select-modal', 'beforeshow', () => setInput({ ...emptyTrait }))
  }, [])
  const update = (field: string, value) => setInput(fieldUpdate(field, value))

  const reset = () => setInput(null)

  const save = () => {
    if (!!input?.id ||
      (
        input?.name?.length || 0
      ) >
      0) {
      const trait = { ...input }
      if (!!trait.id) {
        delete trait.name
        delete trait.description
      } else {
        delete trait.id
      }
      onChange(trait)
      reset()
      UIKit.modal('#trait-select-modal').hide()
    }
  }

  const options = traits?.map(({ id, name }) => (
    { value: id, label: name }
  )) || []

  const selectedTrait: Trait | null = !!input?.id ? traits?.find(trait => trait.id === input.id) || null : null
  return { input, options, save, selectedTrait, update }
}

const emptyTrait: TraitSelection = {
  id: 0,
  name: null,
  description: null
}
