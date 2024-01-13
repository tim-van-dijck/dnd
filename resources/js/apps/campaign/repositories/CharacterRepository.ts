import { Background, CharacterClass, PlayerCharacter, PlayerCharacterInput, Race } from '@dnd/types'
import axios, { AxiosResponse } from 'axios'
import { PaginatedData, useRepository } from '../../../repositories/BaseRepository'
import { useMessageBus } from '../../../services/messages'
import { useCampaignDispatch, useCampaignSelector } from '../store'
import { setBackgrounds, setCharacters, setClasses, setRaces } from '../stores/characters'
import { PlayerCharacterRepositoryInterface } from '../types/repositories'

export const useCharacterRepository = (): PlayerCharacterRepositoryInterface => {
  const url = '/api/campaign/characters'
  const backgrounds = useCampaignSelector(state => state.characters.backgrounds)
  const characters = useCampaignSelector(state => state.characters.characters)
  const classes = useCampaignSelector(state => state.characters.classes)
  const races = useCampaignSelector(state => state.characters.races)

  const dispatch = useCampaignDispatch()
  const messageBus = useMessageBus()
  const repo = useRepository<PlayerCharacter>(url)

  const page = (number: number): Promise<PaginatedData<PlayerCharacter>> | null => {
    if (characters != null && number > 0 && number <= characters.meta.last_page && number >= 1) {
      return repo.page(number)?.then?.((records) => {
        dispatch(setCharacters(records))
        return records
      }) || null
    }
    return null
  }

  return {
    backgrounds,
    characters,
    npcs: null,
    classes,
    races,
    previous: () => (
      characters?.meta?.current_page > 1
    ) ? page(characters?.meta?.current_page - 1) : null,
    page,
    next: () =>
      (
        characters?.meta?.current_page < characters?.meta?.last_page
      ) ? page(characters?.meta?.current_page + 1) : null,
    load: (filters?: Record<'type' | 'text', string>) => repo.load({
      filters,
      includes: [ 'race', 'subrace', 'classes', 'background' ]
    }).then((records) => {
      dispatch(setCharacters(records))
      return records
    }),

    loadClasses: (): Promise<Record<number, CharacterClass>> => {
      const includes = [
        'subclasses',
        'proficiencies',
        'subclasses.proficiencies',
        'spells',
        'subclasses.spells',
        'features',
        'subclasses.features'
      ]
      return axios.get(`/api/classes?include=${includes.join(',')}`)
        .then((response: AxiosResponse<PaginatedData<CharacterClass>>) => {
          const classes: Record<number, CharacterClass> = Object.fromEntries(response.data.data.map((charClass: CharacterClass) => [
            charClass.id,
            charClass
          ]))
          dispatch(setClasses(classes))
          return classes
        })
    },
    loadRaces: (): Promise<Race[]> => {
      const includes = [
        'proficiencies',
        'languages',
        'abilities',
        'subraces',
        'subraces.abilities',
        'traits',
        'subraces.traits'
      ]
      return axios.get(`/api/races?include=${includes.join(',')}`)
        .then((response: AxiosResponse<PaginatedData<Race>>): Race[] => {
          dispatch(setRaces(response.data.data))
          return response.data.data
        })
    },
    loadBackgrounds: (): Promise<Background[]> => {
      const includes = [
        'proficiencies',
        'languages',
        'features'
      ]
      return axios.get(`/api/backgrounds?include=${includes.join(',')}`)
        .then((response: AxiosResponse<PaginatedData<Background>>) => {
          dispatch(setBackgrounds(response.data.data))
          return response.data.data
        })
    },

    find: (id: number): Promise<PlayerCharacter> => axios.get(`${url}/${id}?includes=classes,race,subrace,proficiencies,languages,spells`)
      .then((response) => response.data.data),
    store: (character: PlayerCharacterInput): Promise<number> => {
      return axios.post(url, character)
        .then((response) => {
          messageBus.success('Player Character saved!')
          return response.data.id
        })
    },
    update: (id: number, character: PlayerCharacterInput): Promise<number> => {
      return axios.put(
        `${url}/${id}`,
        character
      )
        .then((response) => {
          messageBus.success('Player Character saved!')
          return response.data.id
        })
    },
    destroy: (id: number) => axios.delete(`${url}/${id}`)
      .then(() => repo.load().then(() => messageBus.success('Player Character successfully deleted!')))
  }
}