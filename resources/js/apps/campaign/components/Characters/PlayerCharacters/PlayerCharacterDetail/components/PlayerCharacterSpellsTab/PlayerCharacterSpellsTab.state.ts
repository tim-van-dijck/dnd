import { SpellLevelCategory } from './PlayerCharacterSpells.type'
import { getTitle } from './PlayerCharacterSpellsTab.ui'

export const useLevels = (spells) => {
  const levels: SpellLevelCategory[] = []
  if (spells.cantrips.length > 0) {
    levels.push({ level: 0, title: 'Cantrips', spells: spells.cantrips })
  }
  for (let level = 1; level < 10; level++) {
    const spellsForLevel = spells.spells.filter(item => item.level === level)
    if (spellsForLevel.length > 0) {
      levels.push({ level, title: getTitle(level), spells: spellsForLevel })
    }
  }
  return levels.filter((level) => level.spells.length > 0)
}