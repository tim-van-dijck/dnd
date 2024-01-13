import { CharacterClassInput, CharacterClassSelection, PlayerCharacter, PlayerCharacterInput } from '@dnd/types'

export default {
  format(character: PlayerCharacter): PlayerCharacterInput {
    return {
      info: {
        ...character.info,
        race_id: character.race.id,
        subrace_id: character.race.subrace ? character.race.subrace.id : null
      },
      type: 'player',
      classes: this.formatClasses(character.classes),
      personality: character.personality,
      ability_scores: character.ability_scores,
      background_id: character.background_id,
      proficiencies: {
        ...character.proficiencies,
        languages: character.proficiencies?.languages?.map(({ id }) => id) || [],
      },
      spells: character.spells
    }
  },

  formatClasses(classes: CharacterClassSelection[]): CharacterClassInput[] {
    return classes.map((charClass) => {
      const features = Object.fromEntries(
        charClass.features
          .filter((feature) => feature.choose > 0 &&
            Array.isArray(feature.choices) &&
            (
              feature.choices || []
            ).length > 0)
          ?.map((feature) =>
            [
              feature.id,
              Object.fromEntries(feature.choices?.map((choice, index) => [
                index + 1,
                choice.id
              ]) || []) as Record<number, number>
            ])) as Record<number, Record<number, number>>

      return {
        class_id: charClass.id,
        level: charClass.level,
        subclass_id: charClass.subclass?.id,
        features
      }
    })
  }
}