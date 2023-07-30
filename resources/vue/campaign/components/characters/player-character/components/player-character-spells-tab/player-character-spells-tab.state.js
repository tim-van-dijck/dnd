import { computed } from 'vue'
import { getTitle } from './player-character-spells-tab.ui'

export const useLevels = (spells) => {
    return computed(() => {
        let levels = {}
        if (spells.cantrips.length > 0) {
            levels[0] = { title: 'Cantrips', spells: spells.cantrips }
        }
        for (let level = 1; level < 10; level++) {
            const spellsForLevel = spells.spells.filter(item => item.level === level)
            if (spellsForLevel.length > 0) {
                levels[level] = { title: getTitle(level), spells: spellsForLevel }
            }
        }
        return levels
    })
}