import { Background, CharacterClass, PlayerCharacterInput, Race } from '@dnd/types'
import { useEffect, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { useMessageBus } from '../../../../../../services/messages'
import { Subrace } from '../../../../../../types/races/subrace'
import { useUpdateField } from '../../../../../admin/utils'
import CharacterFormatter from '../../../../lib/CharacterFormatter'
import { useCampaignRepositories } from '../../../../providers/CampaignRepositoryProvider'
import { useCharacterRepository } from '../../../../repositories/CharacterRepository'

export const usePlayerCharacterFormState = (id?: number) => {
  const [ errors, setErrors ] = useState<Record<string, string[]>>({})
  const [ input, setInput ] = useState<PlayerCharacterInput | null>(null)
  const [ loading, setLoading ] = useState<boolean>(true)
  const messageBus = useMessageBus()
  const navigate = useNavigate()
  const { AuthRepository, PlayerCharacterRepository } = useCampaignRepositories()

  const updateField = useUpdateField(input)
  const update = (field: string, value: unknown) => setInput(updateField(field, value))

  useEffect(() => {
    Promise.all([
      id ? PlayerCharacterRepository.find(id) : Promise.resolve(),
      PlayerCharacterRepository.loadRaces(),
      PlayerCharacterRepository.loadClasses(),
      PlayerCharacterRepository.loadBackgrounds()
    ])
      .then(([ character ]) => {
        setInput(character ? CharacterFormatter.format(character) : getEmptyCharacter())
        setLoading(false)
      })
  }, [])

  const save = () => {
    const promise = id ? PlayerCharacterRepository.update(id, input!) : PlayerCharacterRepository.store(input!)
    return promise.then((characterId) => {
      if (Object.keys(errors).length === 0) {
        navigate(`/characters/players/${characterId}`)
      }
    })
      .catch((error) => {
        setErrors(error.response.data.errors)
        messageBus.error(error.response.data.message)
      })
  }

  return {
    can: AuthRepository.can,
    classes: PlayerCharacterRepository.classes || {},
    errors,
    input,
    loading,
    save,
    update
  }
}

const getEmptyCharacter = (): PlayerCharacterInput => (
  {
    type: 'player',
    ability_scores: {
      STR: 3,
      DEX: 3,
      CON: 3,
      INT: 3,
      WIS: 3,
      CHA: 3
    },
    info: {
      name: '',
      age: '',
      race_id: undefined,
      subrace_id: undefined,
      alignment: undefined
    },
    classes: [],
    personality: {
      trait: '',
      ideal: '',
      bond: '',
      flaw: ''
    },
    proficiencies: {},
    background_id: undefined
  }
)

export const useRelated = (input: PlayerCharacterInput): { background: Background | null, classes: CharacterClass[], race: Race | null, subrace: Subrace | null } => {
  const characterRepository = useCharacterRepository()

  const background = characterRepository.backgrounds?.find(({ id }) => id === input.background_id) || null
  const race = characterRepository.races?.find(({ id }) => id === input.info.race_id) || null
  const subrace = race?.subraces?.find(({ id }) => id === input.info.subrace_id) || null
  const classes = (
    input.classes?.map(({ class_id }) => characterRepository.classes?.[class_id])?.filter(Boolean) || []
  ) as CharacterClass[]

  return { background, classes, race, subrace }
}