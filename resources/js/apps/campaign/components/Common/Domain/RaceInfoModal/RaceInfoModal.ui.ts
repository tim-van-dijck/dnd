import { Race, Trait } from '@dnd/types'
import { useEffect, useState } from 'react'
import UIKit from 'uikit'
import { Subrace } from '../../../../../../types/races/subrace'
import { DisplayRace, DisplaySubrace, RaceInfoModalTab } from './RaceInfoModal.types'

export const useRaceInfoModalActiveSelection = (races: Race[]) => {
  const [ tab, setTab ] = useState<RaceInfoModalTab>('description')
  const [ race, setRace ] = useState<DisplayRace | null>(null)
  const [ subrace, setSubrace ] = useState<DisplaySubrace | null>(null)
  const [ trait, setTrait ] = useState<Trait | null>(null)
  const [ subraceTrait, setSubraceTrait ] = useState<Trait | null>(null)

  useEffect(() => {
    if (races) setActiveRace(races[0].id)
  }, [ races ])

  const setActiveRace = (raceId: number) => {
    const race = races.find(({ id }) => id === raceId)
    const subrace = race?.subraces?.[0]

    if (!race) return

    setTab('description')
    setRace(format(race) as DisplayRace)
    setSubrace(subrace ? format(subrace) as DisplaySubrace : null)
    setTrait(race?.traits?.[0] || null)
    setSubraceTrait(subrace?.traits?.[0] || null)
  }
  const setActiveSubrace = (subraceId: number) => {
    const subrace = race?.subraces?.find(({ id }) => id === subraceId)

    if (!subrace) return

    setSubrace(format(subrace) as DisplaySubrace)
    setSubraceTrait(subrace.traits?.[0] || null)
  }
  const setActiveRaceTrait = (traitId: number) => {
    const trait = race?.traits?.find(({ id }) => id === traitId) || null
    setTrait(trait)
  }
  const setActiveSubraceTrait = (subraceTraitId: number) => {
    const trait = subrace?.traits?.find(({ id }) => id === subraceTraitId) || null
    setSubraceTrait(trait)
  }

  return {
    open: () => UIKit.modal('#race-info-modal').show(),
    tab: {
      key: tab,
      set: setTab
    },
    race,
    subrace,
    trait,
    subraceTrait,

    setActiveRace,
    setActiveSubrace,
    setActiveRaceTrait,
    setActiveSubraceTrait
  }
}

const format = (entity: Race | Subrace): DisplayRace | DisplaySubrace => (
  {
    ...entity,
    ability_scores: entity.abilities
      .map((item) => `${item.ability} +${item.bonus}`)
      .join(', '),
    proficiencies: {
      armor: entity.proficiencies.filter(item => item.type === 'Armor' && !item.optional)
        .map(item => item.name),
      skills: entity.proficiencies.filter(item => item.type === 'Skills' && !item.optional)
        .map(item => item.name),
      tools: entity.proficiencies.filter(item => item.type === 'Tools' && !item.optional)
        .map(item => item.name),
      weapons: entity.proficiencies.filter(item => item.type === 'Weapons' && !item.optional)
        .map(item => item.name),
      optional: entity.proficiencies.filter(item => item.optional).map(item => item.name)
    }
  }
)