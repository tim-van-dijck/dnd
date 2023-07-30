import { useEffect, useState } from "react";
import { Spell } from "../../../../../types";
import { useAdminRepositories } from "../../../providers/AdminRepositoryProvider";

const componentMap = { V: 'Verbal', S: 'Somatic', M: 'Material' }

export const useSpellDetailState = (id?: number) => {
  const { SpellRepository } = useAdminRepositories()

  const [ spell, setSpell ] = useState<Spell | null>(null)

  useEffect(() => {
    if (id) SpellRepository.find(id).then((spell) => setSpell(spell))
  }, [ id ])

  const components = spell?.components?.map((component) => {
    let label = componentMap[component]
    if (component === 'M' && spell.materials?.length > 0) label += ` (${spell.materials})`
    return label
  }).join(', ')

  return { components, spell }
}