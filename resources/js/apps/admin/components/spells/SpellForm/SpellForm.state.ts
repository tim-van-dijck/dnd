import { useMessageBus } from "../../../../../services/messages";
import { SpellInput } from "@dnd/types";
import { useUpdateField } from "../../../utils";
import { useNavigate } from "react-router-dom";
import { SpellErrors } from "./types";
import { FormEvent, useEffect, useState } from "react";
import { IFormOption } from "../../../../../components/layout/form/types";
import { useAdminRepositories } from "../../../providers/AdminRepositoryProvider";

const emptySpell: SpellInput = {
  name: '',
  range: '',
  components: [],
  materials: '',
  ritual: false,
  concentration: false,
  duration: '',
  casting_time: '',
  level: 0,
  school: '',
  description: '',
  higher_levels: ''
}

const schools = [
  'Abjuration',
  'Conjuration',
  'Divination',
  'Enchantment',
  'Evocation',
  'Illusion',
  'Necromancy',
  'Transmutation'
]

export const useSpellFormState = (id?: string) => {
  const { SpellRepository } = useAdminRepositories()
  const messageBus = useMessageBus()
  const [ spell, setSpell ] = useState<SpellInput | null>(null)
  const [ errors, setErrors ] = useState<SpellErrors>({})
  const updateField = useUpdateField(spell)
  const navigate = useNavigate()

  const componentOptions = [ 'Verbal', 'Somatic', 'Material' ].map((component) => (
    { value: component[0], label: component }
  ))

  const levels = [ ...Array(10).keys() ].map((level) => (
    {
      label: level === 0 ? 'Cantrip' : `Level ${level}`,
      value: level
    }
  ))

  const schoolOptions = schools.map((school) => (
    { value: school, label: school }
  )) as IFormOption[]

  const onUpdate = (field: string, value) => {
    setSpell(updateField(field, value))
  }

  useEffect(() => {
    if (id) {
      SpellRepository.find(parseInt(id)).then((spell) => setSpell(spell));
    } else {
      setSpell(emptySpell)
    }
  }, [ id ])

  const submit = (e: FormEvent) => {
    e.preventDefault()

    if (spell === null) return Promise.resolve()

    const promise = id ? SpellRepository.update(parseInt(id), spell) : SpellRepository.store(spell)
    promise
      .then(() => navigate('/spells'))
      .catch((error) => {
        setErrors(error.response.data.errors)
        messageBus.error(error.response.data.message)
      })
  }

  return { spell, componentOptions, errors, levels, schoolOptions, onUpdate, submit }
}