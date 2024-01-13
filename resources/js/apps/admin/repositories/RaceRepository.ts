import { Race, RaceInput } from '@dnd/types'
import axios from 'axios'
import { PaginatedData, useRepository } from '../../../repositories/BaseRepository'
import { useMessageBus } from '../../../services/messages'
import { useAdminDispatch, useAdminSelector } from '../store'
import { setProficiencies, setRaces, setTraits } from '../stores/races'
import { RaceRepositoryInterface } from './types'

export const useRaceRepository = (): RaceRepositoryInterface => {
  const url = '/api/admin/races'
  const messageBus = useMessageBus()
  const repo = useRepository<Race>(url)
  const proficiencies = useAdminSelector(state => state.races.proficiencies)
  const races = useAdminSelector(state => state.races.races)
  const traits = useAdminSelector(state => state.races.traits)
  const dispatch = useAdminDispatch()
  const page = (number: number): Promise<PaginatedData<Race>> | null => {
    if (races != null && number > 0 && number <= races.meta.last_page && number >= 1) {
      return repo.page(number)?.then?.((records) => {
        dispatch(setRaces(records))
        return records
      }) || null
    }
    return null
  }

  return {
    proficiencies,
    races,
    traits,
    previous: (): Promise<PaginatedData<Race>> | null => (
      races?.meta?.current_page > 1
    ) ? page(races?.meta?.current_page - 1) : null,
    page,
    next: (): Promise<PaginatedData<Race>> | null =>
      (
        races?.meta?.current_page < races?.meta?.last_page
      ) ? page(races?.meta?.current_page + 1) : null,
    load: (): Promise<PaginatedData<Race>> => repo.load().then((records) => {
      dispatch(setRaces(records))
      return records
    }),
    find: (id: number): Promise<Race> => axios.get(`${url}/${id}`).then((response) => response.data.data),

    /*loadLanguages: (): Promise<void> => axios.get(`/api/languages`)
      .then((response) => void (
        dispatch(setLanguages(response.data.data))
      )),*/
    loadProficiencies: (): Promise<void> => axios.get('/api/admin/proficiencies')
      .then((response) => void (
        dispatch(setProficiencies(response.data.data))
      )),
    loadTraits: (): Promise<void> => axios.get(`${url}/traits`)
      .then((response) => void (
        dispatch(setTraits(response.data.data))
      )),

    store: (race: RaceInput): Promise<void> => axios.post(url, race).then(() => messageBus.success('Race saved!')),
    update: (id: number, race: RaceInput): Promise<void> => axios.put(`${url}/${id}`, race)
      .then(() => messageBus.success('Race saved!')),
    destroy: (id: number): Promise<void> => axios.delete(`${url}/${id}`)
      .then(() => repo.load().then(() => messageBus.success('Race successfully deleted!'))),
  }
}