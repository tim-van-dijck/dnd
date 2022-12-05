import { useCharacterStore } from '@campaign/stores/characters'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { computed, reactive } from 'vue'

export const useSkillSelectionState = (props) => {
    const store = useCharacterStore()
    const { classes } = storeToRefs(store)
    const state = reactive({
        selection: [],
        classSelected: {},
        init() {
            if (props?.value?.length > 0) {
                const selection = []
                for (const selected of props.value) {
                    if (selected.origin_type === 'class') {
                        selection.push(selected)
                        if (this.classSelected.hasOwnProperty(selected.origin_id)) {
                            this.classSelected[selected.origin_id]++
                        } else {
                            this.classSelected[selected.origin_id] = 1
                        }
                    }
                }
                this.selection = selection
            }
        },
        addSkillToClass(charClass, input) {
            const skill = classSkills.value[charClass.id].optional.find((item) => item.id == input)
            this.selection.splice(this.selection.length, 1, {
                origin_type: 'class',
                origin_id: charClass.id,
                origin: charClass.name,
                name: skill.name,
                id: input
            })
            if (this.classSelected.hasOwnProperty(charClass.id)) {
                this.classSelected[charClass.id]++
            } else {
                this.classSelected[charClass.id] = 1
            }
        },
        removeSkill(index) {
            const skill = this.selection[index]
            if (skill.origin_type == 'class') {
                this.classSelected[skill.origin_id]--
            }
            this.selection.splice(index, 1)
        }
    })

    const raceSkills = computed(() => {
        let skills = []
        if (props.race) {
            skills = [...(props.race?.proficiencies || [])]
                .filter((item) => item.type === 'Skills')
                .map((item) => (
                    { ...item, origin: props.race?.name }
                ))
            if (props.subrace) {
                const subraceSkills = [...(props.subrace?.proficiencies || [])]
                    .filter((item) => item.type === 'Skills')
                    .map((item) => (
                        { ...item, origin: props.subrace?.name }
                    ))
                skills.concat(subraceSkills)
            }
        }
        return skills
    })
    const classSkills = computed(() => {
        if (Object.keys(classes).length === 0) {
            return {}
        }
        const skills = {}
        for (const chosenClass of props.classes) {
            if (chosenClass.class_id) {
                const charClass = { ...classes[chosenClass.class_id] }
                skills[chosenClass.class_id] = {
                    known: charClass?.proficiencies?.filter(item => item.type === 'Skills' && !item.optional) || [],
                    optional: charClass?.proficiencies?.filter((item) => {
                        if (item.type === 'Skills' && item.optional) {
                            return state.selection.every((skill) => item.id != skill.id) &&
                                raceSkills.value.every((skill) => item.id != skill.id) &&
                                backgroundSkills.value.every((skill) => item.id != skill.id)
                        }
                        return false
                    }) || []
                }
            }
        }
        return skills
    })
    const backgroundSkills = computed(() => {
        return props.background?.skills?.map((item) => (
            {
                id: item.id,
                name: item.name,
                origin: props.background?.name
            }
        )) || []
    })
    const known = computed(() => {
        return [
            ...(raceSkills.value || []),
            ...(
                Object.keys(classSkills.value || [])
                    .flatMap((classId) => classSkills.value?.[classId]?.known || [])
            ),
            ...(backgroundSkills.value || [])
        ]
    })

    return { classes, backgroundSkills, classSkills, known, raceSkills, state }
}