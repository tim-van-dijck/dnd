import { useSpellStore } from '@campaign/stores/spells'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { computed } from 'vue'

export const useFormatteValues = (ctx, selection, chosenClasses) => {
    const store = useSpellStore()
    const { spells } = storeToRefs(store)

    const spellsAvailable = computed(() => {
        const casterClasses = []
        console.log(chosenClasses)
        for (const charClass of chosenClasses.value) {
            if (charClass.currentLevel.spells_known > 0) {
                casterClasses.push(charClass)
            }
        }
        if (casterClasses.length === 0) {
            return { maxSpellLevel: 0 }
        }
        let level
        if (casterClasses.length > 1) {
            let spellSlotsLevel = 0
            for (const charClass of casterClasses) {
                let level = (
                    ['Paladin', 'Ranger'].includes(charClass.name)
                        ? charClass.currentLevel.level / 2
                        : charClass.currentLevel.level
                )
                spellSlotsLevel += level
            }
            level = this.multiclassTable[spellSlotsLevel]
        } else {
            level = casterClasses[0].currentLevel
        }
        for (let i = 1; i <= 9; i++) {
            if (level[`spell_slots_level_${i}`] > 0) {
                level.maxSpellLevel = i
            }
        }
        return level
    })

    const spellsKnown = computed(() => {
        let spells = {}
        for (let level = 0; level < 10; level++) {
            const visible = chosenClasses.value
                .every((charClass) => charClass.currentLevel.maxSpellLevel > level || level === 0)
            if (visible) {
                spells[level] = { title: getTitle(level), spells: [] }
            }
        }
        for (const type in selection) {
            for (const spell of selection[type]) {
                if (spells.hasOwnProperty(spell.level)) {
                    spells[spell.level].spells.push(spell)
                }
            }
        }
        return spells
    })

    const classSpells = computed(() => {
        const classSpells = {}
        for (let level = 0; level <= 9; level++) {
            const levelSpells = {
                title: getTitle(level),
                items: {}
            }
            const type = level > 0 ? 'spells' : 'cantrips'
            for (const charClass of chosenClasses.value) {
                const knowsType = charClass.currentLevel[`${type}_known`] > 0
                const canSelect = knowsType &&
                    ((type === 'cantrips' && level === 0) || spellsAvailable.value.maxSpellLevel >= level)

                if (canSelect) {
                    levelSpells.items[charClass.id] = spells
                        .filter(spell => {
                            return selection[type].find(item => item.id === spell.id) == null
                                && spell.level === level
                                && classes[charClass.id].spells.includes(spell.id)
                        })
                        .sort((a, b) => a.name.localeCompare(b.name))

                    if (charClass.subclass) {
                        if (charClass.subclass.spells) {
                            const subclassSpells = spells.filter(spell => {
                                return selection[type].find(item => item.id === spell.id) == null
                                    && spell.level === level
                                    && charClass.subclass.spells.includes(spell.id)
                            })
                            levelSpells.items[charClass.id] = levelSpells.items[charClass.id]
                                .concat(subclassSpells)
                                .sort((a, b) => a.name.localeCompare(b.name))
                        }
                    }
                }
            }
            classSpells[level] = levelSpells
        }
        console.log(classSpells)
        return classSpells
    })

    const next = () => ctx.emit('next')

    return { classSpells, next, spellsAvailable, spellsKnown }
}

const getTitle = (level) => {
    switch (level) {
        case 0:
            return 'Cantrips'
        case 1:
            return '1st level'
        case 2:
            return '2nd level'
        case 3:
            return '3rd level'
        default:
            return `${level}th level`
    }
}