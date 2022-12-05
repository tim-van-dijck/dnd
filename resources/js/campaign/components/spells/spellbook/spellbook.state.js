import { computed, reactive } from 'vue'

export const useSpellBookState = (spells) => {
    return reactive({
        filters: {
            level: 0,
            ritual: null,
            concentration: null,
            query: ''
        },
        setSpell(spell) {
            this.spell = spell
        },
        spell: null,
        toggleFilter(filter) {
            if (['ritual', 'concentration'].includes(filter)) {
                switch (this.filters[filter]) {
                    case null:
                        this.filters[filter] = true
                        break
                    case true:
                        this.filters[filter] = false
                        break
                    case false:
                    default:
                        this.filters[filter] = null
                        break
                }
            }
        }
    })
}

export const useSpellBookRelevantSpells = (spells, filters) => {
    return computed(() => {
        if (spells.value == null) {
            return []
        }
        return spells.value
            .filter((spell) => {
                if ((
                    filters.query || ''
                ).length > 0) {
                    return spell.name.toLowerCase().includes(filters.query.toLowerCase())
                }

                let visible = spell.level === filters.level
                if (filters.ritual != null) {
                    visible = visible && spell.ritual == filters.ritual
                }
                if (filters.concentration != null) {
                    visible = visible && spell.concentration == filters.concentration
                }
                return visible
            })
            .sort((a, b) => {
                return (
                    a.name > b.name
                ) ? 1 : (
                    (
                        b.name > a.name
                    ) ? -1 : 0
                )
            })
    })
}