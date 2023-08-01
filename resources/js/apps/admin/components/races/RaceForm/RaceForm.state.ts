import { RaceAttribute, RaceInput, Trait } from "@dnd/types";
import { FormEvent, useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { useMessageBus } from "../../../../../services/messages";
import { useRaceRepository } from "../../../repositories/RaceRepository";
import { useUpdateField } from "../../../utils";
import { RaceErrors } from "./types";

export const useRaceFormState = (id?: number) => {
  const raceRepository = useRaceRepository()
  const messageBus = useMessageBus()
  const [ race, setRace ] = useState<RaceInput | null>(null)
  const [ errors, setErrors ] = useState<RaceErrors>({})
  const updateField = useUpdateField(race)
  const navigate = useNavigate()

  useEffect(() => {
    setRace(emptyRace)
    if (id) {
      // void raceRepository.find(id).then(({ id, ...race }) => setRace(race));
    } else {
    }
  }, [ id ])

  const onUpdate = (field: string, value) => {
    setRace(updateField(field, value))
  }

  const submit = (e: FormEvent) => {
    e.preventDefault()

    if (race === null) return Promise.resolve()

    const data = { ...race }
    data.traits = race.traits.map((trait) => trait.id ? { id: trait.id } : trait)

    const promise = id ? raceRepository.update(id, data) : raceRepository.store(data)
    promise
      .then(() => navigate('/races'))
      .catch((exception) => {
        setErrors(exception.response.data.errors)
        messageBus.error(exception.response.data.message)
      })
  }

  const addTrait = (trait: Trait | Pick<Trait, 'id'>) => {
    if (!race) return
    setRace({ ...race, traits: [ ...race.traits, trait ] })
  }
  const removeTrait = (index) => {
    if (!race) return
    if (race?.traits?.hasOwnProperty(index)) {
      const traits = race.traits.filter((_, i) => i !== index)
      setRace({ ...race, traits })
    }
  }
  const remove = (type: RaceAttribute, id) => {
    if (!race) return
    const attributes = [
      ...(
        race?.[type] || []
      )
    ]
    const index = attributes.findIndex((item) => item.id === id)

    if (index != null) {
      attributes.splice(index, 1)
      setRace({ ...race, [type]: attributes })
    }
  }

  return {
    errors,
    race,
    sizes: sizes.map((size) => (
      { value: size, label: size }
    )),
    addTrait,
    removeTrait,
    remove,
    submit,
    onUpdate
  }
}

const emptyRace: RaceInput = {
  name: '',
  description: '',
  size: '',
  speed: 30,
  ability_bonuses: [],
  optional_ability_bonuses: 0,
  feats: [],
  optional_feats: 0,
  languages: [],
  optional_languages: 0,
  proficiencies: [],
  optional_proficiencies: 0,
  traits: [],
  optional_traits: 0
}

const sizes = [ 'Tiny', 'Small', 'Medium', 'Large', 'Huge', 'Gargantuan' ]