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
        return `Edit ${character ? character.info.name : 'character'}`
    } else {
        return 'Add character'
    }
})

export const useSpellcaster = (character, classes) => computed(() => {
    if ((
        character?.classes || []
    )?.length === 0 || Object.keys(classes).length === 0) {
        return false
    }

    for (const characterClass of character.classes) {
        if (characterClass.class_id) {
            let chosenClass = classes[characterClass.class_id]
            if (chosenClass.spellcaster) {
                return true
            }
            let subclass = chosenClass.subclasses.find(item => item.id == characterClass.subclass_id)
            if (subclass && subclass.spellcaster) {
                return true
            }
        }
    }
    return false
})