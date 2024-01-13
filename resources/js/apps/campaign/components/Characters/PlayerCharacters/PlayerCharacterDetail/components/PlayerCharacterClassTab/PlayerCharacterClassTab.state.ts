import { CharacterClass, CharacterClassSelection } from "@dnd/types";
import { useEffect } from "react";
import { useCharacterRepository } from "../../../../../../repositories/CharacterRepository";

export const usePlayerCharacterClasses = (classes: CharacterClassSelection[]) => {
  const characterRepository = useCharacterRepository()

  useEffect(() => {
    characterRepository.loadClasses()
  }, []);

  const characterClasses = getFormattedClasses(classes, characterRepository.classes)

  return {
    characterClasses,
    loading: characterRepository.classes === null
  }
}

const getFormattedClasses = (
  classSelection: CharacterClassSelection[],
  classes: Record<number, CharacterClass> | null
) => {
  return classSelection.map((charClass) => {
    if (!classes?.hasOwnProperty(charClass.id)) return null
    const classEntity = classes?.[charClass.id]
    const subclass = classEntity.subclasses.find(({ id }) => id === charClass.subclass_id)

    return (
      {
        ...classEntity,
        level: charClass.level,
        features: charClass.features.sort((a, b) => a.level < b.level ? -1 : (
          a.level > b.level
        ) ? 1 : 0),
        ...(
          subclass ? { subclass } : {}
        )
      }
    );
  }).filter(Boolean)
}