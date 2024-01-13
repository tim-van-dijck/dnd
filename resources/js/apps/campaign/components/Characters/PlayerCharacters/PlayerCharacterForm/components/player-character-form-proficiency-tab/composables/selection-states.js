import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { computed, reactive } from 'vue'
import { useCharacterStore } from '../../../../../../stores/characters'
import { useRelated } from '../../../PlayerCharacterForm.state'

export const useSelectionState = (props, stateType) => {
  if (![
    'Skills',
    'Tools',
    'Instruments'
  ].includes(stateType)) {
    throw new Error(`Invalid Selection state type [${stateType}]`)
  }

  const store = useCharacterStore()
  const { classes } = storeToRefs(store)
  const { background, race, subrace } = useRelated(props.form)

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
        this.selection = [...selection]
      }
    },
    setSelection(selection) {
      this.selection = [...selection]
    },
    addToClass(charClass, input) {
      const item = classProficiencies.value[charClass.id].optional.find((item) => item.id == input)
      if (this.selection.filter((item) => item.id == input).length === 0) {
        this.setSelection([
          ...this.selection, {
            origin_type: 'class',
            origin_id: charClass.id,
            origin: charClass.name,
            name: item.name,
            id: input
          }
        ])
        if (this.classSelected.hasOwnProperty(charClass.id)) {
          this.classSelected[charClass.id]++
        } else {
          this.classSelected[charClass.id] = 1
        }
      }
    },
    remove(index) {
      const proficiency = this.selection[index]
      if (proficiency.origin_type === 'class') {
        this.classSelected[proficiency.origin_id]--
      }
      this.selection.splice(index, 1)
    }
  })

  const raceProficiencies = computed(() => {
    let proficiencies = []
    if (race.value) {
      proficiencies = [...(race.value?.proficiencies || [])]
        .filter((item) => item.type === stateType)
        .map((item) => (
          { ...item, origin: race.value?.name }
        ))
      if (subrace.value) {
        const subraceProficiencies = [...(subrace.value?.proficiencies || [])]
          .filter((item) => item.type === stateType)
          .map((item) => ({ ...item, origin: props.subrace?.name }))
        proficiencies.concat(subraceProficiencies)
      }
    }
    return proficiencies
  })
  const classProficiencies = computed(() => {
    if (Object.keys(classes).length === 0) {
      return {}
    }

    const proficiencies = {}
    for (const chosenClass of props.form.classes) {
      if (chosenClass.class_id) {
        const charClass = { ...classes.value[chosenClass.class_id] }
        proficiencies[chosenClass.class_id] = {
          known: charClass?.proficiencies?.filter(item => item.type === stateType && !item.optional) || [],
          optional: charClass?.proficiencies?.filter((proficiency) => {
            if (proficiency.type === stateType && proficiency.optional) {
              return [
                ...state.selection,
                ...raceProficiencies.value,
                ...backgroundProficiencies.value
              ].every((item) => item.id != proficiency.id)
            }
            return false
          }) || []
        }
      }
    }
    return proficiencies
  })
  const backgroundProficiencies = computed(() => background.value?.[stateType.toLowerCase()]?.map((item) => (
    {
      id: item.id,
      name: item.name,
      origin: background.value?.name
    }
  )) || [])
  const known = computed(() => [
    ...(raceProficiencies.value || []),
    ...(
      Object.keys(classProficiencies.value || [])
        .flatMap((classId) => classProficiencies.value?.[classId]?.known || [])
    ),
    ...(backgroundProficiencies.value || [])
  ])

  return {
    state,
    computed: {
      background, backgroundProficiencies, classProficiencies, classes, known, race, raceProficiencies, subrace
    },
    isAlreadySelected(classId, proficiencyId) {
      return classProficiencies.value[classId].known.includes(proficiencyId) ||
        state.selection.filter((selected) => selected.id == proficiencyId).length > 0
    }
  }
}

export const useProficiencyTypeMetrics = (raceProficiencies, classProficiencies, backgroundProficiencies) => {
  const hasProficiencyType = computed(() => {
    return (raceProficiencies.value.length + classProficiencyCount.value + backgroundProficiencies.value.length) > 0
  })

  const classProficiencyCount = computed(() => {
    let count = 0
    for (const classId in classProficiencies.value) {
      count += classProficiencies.value[classId].known.length
      count += classProficiencies.value[classId].optional.length
    }
    return count
  })

  return { hasProficiencyType, classProficiencyCount }
}