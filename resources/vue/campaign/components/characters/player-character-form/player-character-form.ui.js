import { computed, reactive } from 'vue'

export const useFormNavigation = (save) =>
    reactive({
        page: 'form',
        tab: 'details',
        isFormTabActive(tab) {
            return this.page === 'form' && this.tab === tab
        },
        setPage(page) {
            this.page = page
        },
        setTab(tab) {
            this.tab = tab
        },
        nextOrSave(condition, next) {
            if (condition) {
                this.setTab(next)
            } else {
                save()
            }
        }
    })

export const useTitle = (id, character) => computed(() => {
    if (id) {
        return `Edit ${character?.info?.name || 'character'}`
    } else {
        return 'Add character'
    }
})

export const useSpellcaster = (state, classes) => computed(() => {
    if ((
        state.input?.classes || []
    )?.length === 0 || Object.keys(classes.value).length === 0) {
        return false
    }

    for (const characterClass of state.input.classes) {
        if (characterClass.class_id) {
            const chosenClass = classes.value[characterClass.class_id]
            if (chosenClass.spellcaster) {
                return true
            }
            if (chosenClass.subclasses.find(item => item.id == characterClass.subclass_id)?.spellcaster) {
                return true
            }
        }
    }
    return false
})