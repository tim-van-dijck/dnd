import { useCharacterStore } from '@campaign/stores/characters'
import { useSpellStore } from '@campaign/stores/spells'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { computed, reactive } from 'vue'

export const usePlayerCharacterSpellState = (props, backgrounds, classes, races, subrace, raceCantrips) => {
    const formatSpell = useFormatSpell(
        props.info,
        backgrounds,
        classes,
        races,
        subrace
    )

    const state = reactive({
        selection: {
            cantrips: [],
            spells: []
        },
        classSelected: {},
        setSelection(selection) {
            this.selection = selection
        },
        select(level, charClass, id) {
            const spell = this.classSpells[level].items[charClass.id].find(item => item.id === id)
            if (spell) {
                const type = level > 0 ? 'spells' : 'cantrips'
                this.selection[type].push({
                    id: spell.id,
                    name: spell.name,
                    level: spell.level,
                    school: spell.school,
                    origin_id: charClass.id,
                    origin_name: charClass.name,
                    origin_type: 'Class'
                })
                this.addToClassCount(type, charClass.id)
            }
        },
        remove(type, originType, classId, id) {
            const selection = this.selection
            selection[type] = selection[type].filter((item) => item.id !== id)
            this.setSelection(selection)
            if (originType === 'Class') {
                this.classSelected[classId][type]--
            }
        },
        selectRaceCantrip(id) {
            this.removeRaceCantrip()
            const cantrip = raceCantrips.value?.find(item => item.id === id)
            if (subrace.value?.traits?.find(item => item.name.includes('Cantrip'))) {
                cantrip.origin_id = subrace.value.id
                cantrip.origin_name = subrace.value.name
                cantrip.origin_type = 'Subrace'
            } else {
                cantrip.origin_id = props.info.race_id
                cantrip.origin_name = races.value?.[props.info.race_id].name
                cantrip.origin_type = 'Race'
            }
            this.selection.cantrips.push(cantrip)
        },
        removeRaceCantrip() {
            if (raceCantrip) {
                this.selection.cantrips = this.selection.cantrips
                    .filter((item) => item.level === 0 && item.origin_type === 'Race')
            }
        },
        addToClassCount(type, classId) {
            if (!this.classSelected.hasOwnProperty(classId)) {
                this.classSelected[classId] = {}
            }
            if (!this.classSelected[classId].hasOwnProperty(type)) {
                this.classSelected[classId][type] = 0
            }
            this.classSelected[classId][type]++
        },
        init() {
            const store = useSpellStore()
            store.load().then(() => {
                if (props.value) {
                    this.setSelection({
                        cantrips: props.value.cantrips.map(spell => formatSpell(spell)),
                        spells: props.value.spells.map(spell => formatSpell(spell))
                    })
                }
            })
        }
    })

    const raceCantrip = computed(() => {
        return state.selection.cantrips.find((item) => {
            return item.level === 0 && ['Race', 'Subrace'].includes(item.origin_type)
        })
    })

    return {
        raceCantrip,
        state
    }
}

const useFormatSpell = (info, backgrounds, classes, races, subrace) => {
    return (spell) => {
        const formatted = { ...spell }
        switch (formatted.origin_type) {
            case 'background':
                const background = backgrounds.find(item => item.id === formatted.origin_id)
                formatted.origin_name = background?.name || 'Background'
                break
            case 'class':
                formatted.origin_name = classes[formatted.origin_id]?.name || 'Class'
                break
            case 'subclass':
                const selectedClass = classes.find((item) => {
                    return item.subclasses.find(subclass => subclass.id === formatted.origin_id) != null
                })
                if (selectedClass) {
                    const subclass = selectedClass.subclasses.find(subclass => subclass.id === formatted.origin_id)
                    formatted.origin_name = subclass?.name || 'Subclass'
                } else {
                    formatted.origin_name = 'Subclass'
                }
                break
            case 'race':
                const race = races.find((race) => race.id === spell.origin_id === info.race_id)
                formatted.origin_name = race.name || 'Race'
                break
            case 'subrace':
                formatted.origin_name = subrace?.id === spell.origin_id ? subrace.name || 'Subrace' : 'Subrace'
                break
        }

        return formatted
    }
}

export const usePlayerCharacterSpellsComputed = (info, characterClasses) => {
    const characters = useCharacterStore()
    const spellStore = useSpellStore()
    const { spells } = storeToRefs(spellStore)
    const { backgrounds, classes, races } = storeToRefs(characters)

    const subrace = computed(() => races?.[info.subrace_id]?.subraces.find(item => item.id === info.subrace_id) || null)
    const chosenClasses = computed(() => {
        return characterClasses?.filter((item) => item.class_id && classes.hasOwnProperty(item.class_id))
            ?.map((charClass) => {
                const chosenClass = { ...(classes[charClass.class_id] || {}) }
                const currentLevel = chosenClass.levels.find(lvl => lvl.level === charClass.level)
                chosenClass.currentLevel = currentLevel || {}
                if (charClass.subclass_id) {
                    chosenClass.subclass = chosenClass.subclasses.find(item => item.id === charClass.subclass_id)
                }
                return chosenClass
            }) || []
    })

    const raceCantrips = computed(() => {
        let spells = []
        if (info.race_id > 0) {
            const wizardSpells = Object.values(classes)
                .find(([id, cl]) => cl.name === 'Wizard')
                ?.map(([id, cl]) => cl.spells?.filter((item) => item.level === 0)) || []

            const race = races[info.race_id]
            if (race.traits.find(item => item.name.includes('Cantrip')) != null) {
                spells = wizardSpells.map(spell => ({
                    ...spell,
                    origin_id: race.id,
                    origin_type: 'race',
                    origin_name: race.name
                }))
            }

            if (subrace.value?.traits?.find(item => item.name.includes('Cantrip')) != null) {
                spells = wizardSpells.map(spell => ({
                    ...spell,
                    origin_id: subrace.value.id,
                    origin_type: 'race',
                    origin_name: subrace.value.name
                }))
            }
        }
        return spells
    })

    return { backgrounds, chosenClasses, classes, races, subrace, raceCantrips, spells }
}
