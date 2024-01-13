import { useCharacterStore } from '@campaign/stores/characters'
import { useSpellStore } from '@campaign/stores/spells'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { computed, reactive } from 'vue'

export const usePlayerCharacterSpellState = (input, background, classes, race, subrace, raceCantrips) => {
    const formatSpell = useFormatSpell(
        background,
        classes,
        race,
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
                cantrip.origin_id = input.info.race_id
                cantrip.origin_name = race.value?.name
                cantrip.origin_type = 'Race'
            }
            this.selection.cantrips.push(cantrip)
        },
        removeRaceCantrip() {
            if (raceCantrip) {
                this.selection.cantrips = this.selection.cantrips
                    .filter((item) => item.level === 0 && ['Race', 'Subrace'].includes(item.origin_type))
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
                if (input.spells) {
                    this.setSelection({
                        cantrips: input.spells.cantrips.map(spell => formatSpell(spell)),
                        spells: input.spells.spells.map(spell => formatSpell(spell))
                    })
                }
            })
        }
    })

    const raceCantrip = computed(() =>
        state.selection.cantrips.find((item) => item.level === 0 && ['Race', 'Subrace'].includes(item.origin_type)))

    return {
        raceCantrip,
        state
    }
}

const useFormatSpell = (background, classes, race, subrace) => {
    return (spell) => {
        const formatted = { ...spell }
        switch (formatted.origin_type) {
            case 'background':
                formatted.origin_name = background.value?.name || 'Background'
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
                formatted.origin_name = race.value?.name || 'Race'
                break
            case 'subrace':
                formatted.origin_name = subrace?.id === spell.origin_id ? subrace.name || 'Subrace' : 'Subrace'
                break
        }

        return formatted
    }
}

export const usePlayerCharacterSpellsComputed = (input, race, subrace) => {
    const characters = useCharacterStore()
    const { classes } = storeToRefs(characters)

    // watch(input.value, () => console.log(input.value))

    const chosenClasses = computed(() => {
        console.log('kapot')
        return 'kapot'
        return input.classes.map((cl) => {
            if (classes.value.hasOwnProperty(cl.class_id)) {
                const chosenClass = { ...classes.value[cl.class_id] }
                console.log('HALLO')
                if (cl.subclass_id) {
                    chosenClass.subclass = {
                        ...chosenClass.subclasses?.find(item => item.id === input.info.subclass_id) || {}
                    }
                }

                return chosenClass
            }
            return null
        }).filter(Boolean)
    })

    const raceCantrips = computed(() => {
        let spells = []
        if (race.value) {
            const wizardSpells = Object.values(classes)
                .find(([id, cl]) => cl.name === 'Wizard')
                ?.map(([id, cl]) => cl.spells?.filter((item) => item.level === 0)) || []

            if (race.value.traits.find(item => item.name.includes('Cantrip')) != null) {
                spells = wizardSpells.map(spell => ({
                    ...spell,
                    origin_id: race.value.id,
                    origin_type: 'race',
                    origin_name: race.value.name
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

    return { chosenClasses, classes, raceCantrips }
}
