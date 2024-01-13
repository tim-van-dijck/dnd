import { Ability, Alignment, PlayerCharacter } from '@dnd/types'

export const usePlayerCharacterInfo = (character: PlayerCharacter) => {
  const abilityScores = abilities.map((ability) => {
    const bonus = Math.floor((
      character.ability_scores[ability] - 10
    ) / 2)

    return {
      name: ability,
      score: character.ability_scores[ability],
      bonus: `${bonus >= 0 ? '+' : '-'} ${Math.abs(bonus)}`
    }
  })

  const alignment = character.info.alignment ? alignments[character.info.alignment] : null

  return { abilityScores, alignment }
}
const abilities: Ability[] = [ 'STR', 'DEX', 'CON', 'INT', 'WIS', 'CHA' ]

export const alignments: Record<Alignment, string> = {
  'LG': 'Lawful Good',
  'NG': 'Neutral Good',
  'CG': 'Chaotic Good',
  'LN': 'Lawful Neutral',
  'TN': 'True Neutral',
  'CN': 'Chaotic Neutral',
  'LE': 'Lawful Evil',
  'NE': 'Neutral Evil',
  'CE': 'Chaotic Evil'
}