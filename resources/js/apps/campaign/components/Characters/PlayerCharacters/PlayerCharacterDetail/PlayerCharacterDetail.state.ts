import { PlayerCharacter } from "@dnd/types"
import { useEffect, useState } from "react"
import { useCharacterRepository } from "../../../../repositories/CharacterRepository"

export const usePlayerCharacterDetails = (id: number) => {
  const characterRepository = useCharacterRepository()
  const [ character, setCharacter ] = useState<PlayerCharacter | null>(null)

  useEffect(() => void characterRepository.find(id).then(setCharacter), [ id ])

  const cantripCount = character?.spells?.cantrips?.length || 0
  const spellCount = character?.spells?.spells?.length || 0
  const isSpellcaster = cantripCount + spellCount > 0

  return { character, isSpellcaster }
}