import { Spell } from "@dnd/types";
import { useState } from "react";
import { useCampaignSelector } from "../../../store";

export const useSpellBookState = () => {
  const spells: Spell[] | null = useCampaignSelector(state => state.spells.spells) as Spell[] | null
  const [ filters, setFilters ] = useState({ ...emptyFilters })
  const [ spell, setSpell ] = useState<Spell | null>(null)

  const toggleFilter = (filter: string) => {
    if ([ 'ritual', 'concentration' ].includes(filter)) {
      const updated = { ...filters }
      switch (updated[filter]) {
        case null:
          updated[filter] = true
          break
        case true:
          updated[filter] = false
          break
        case false:
        default:
          updated[filter] = null
          break
      }
      setFilters(updated)
    }
  }

  const relevantSpells = spells
    ?.filter((spell) => {
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
    ?.sort((a, b) => {
      return (
        a.name > b.name
      ) ? 1 : (
        (
          b.name > a.name
        ) ? -1 : 0
      )
    }) || []

  return {
    filters,
    setFilters,
    spell,
    setSpell,
    toggleFilter,
    relevantSpells
  }
}

const emptyFilters = {
  level: 0,
  ritual: null,
  concentration: null,
  query: ''
}