import { Location, LocationInput } from "@dnd/types";
import axios from "axios";
import { PaginatedData, useRepository } from "../../../repositories/BaseRepository";
import { useMessageBus } from "../../../services/messages";
import { useCampaignDispatch, useCampaignSelector } from "../store";
import { setLocations } from "../stores/locations";
import { LocationRepositoryInterface } from "../types/repositories";

export const useLocationRepository = (): LocationRepositoryInterface => {
  const locations = useCampaignSelector(state => state.locations.locations)
  const dispatch = useCampaignDispatch()
  const url = '/api/campaign/locations'
  const messageBus = useMessageBus()
  const repo = useRepository<Location>(url)

  const page = (number: number): Promise<PaginatedData<Location>> | null => {
    if (locations != null && number > 0 && number <= locations.meta.last_page && number >= 1) {
      return repo.page(number)?.then?.((records) => {
        dispatch(setLocations(records))
        return records
      }) || null
    }
    return null
  }

  return {
    locations,
    previous: () => (
      locations?.meta?.current_page > 1
    ) ? page(locations?.meta?.current_page - 1) : null,
    page,
    next: () =>
      (
        locations?.meta?.current_page < locations?.meta?.last_page
      ) ? page(locations?.meta?.current_page + 1) : null,
    load: () => repo.load().then((records) => {
      dispatch(setLocations(records));
      return records
    }),
    find: (id: number): Promise<Location> => axios.get(`${url}/${id}`).then((response) => response.data.data),
    store: (location: LocationInput) => {
      return axios.post(url, generateFormData(location), { headers: { 'Content-Type': 'multipart/form-data' } })
        .then(() => messageBus.success('Location saved!'));
    },
    update: (id: number, location: LocationInput) => {
      const formData = generateFormData(location)
      formData.append('_method', 'PUT')
      return axios.post(
        `${url}/${id}`,
        formData,
        { headers: { 'Content-Type': 'multipart/form-data' } }
      )
        .then(() => messageBus.success('Location saved!'));
    },
    destroy: (id: number) => axios.delete(`${url}/${id}`)
      .then(() => repo.load().then(() => messageBus.success('Location successfully deleted!')))
  }
}

const generateFormData = (input: LocationInput): FormData => {
  const location = new FormData()
  if (input?.map instanceof File) {
    location.append('map', input.map)
  }
  if (typeof input?.location_id === 'number' && input?.location_id > 0) {
    location.append('location_id', input?.location_id?.toString())
  }

  for (const prop of [ 'name', 'type', 'description' ]) {
    if (input?.[prop] !== '' && input?.[prop] != null) {
      location.append(prop, input?.[prop])
    }
  }

  location.append('private', input?.private ? '1' : '0')
  if (!!input.permissions) {
    for (const userId in input?.permissions) {
      const permission = input?.permissions?.[userId]
      location.append(`permissions[${userId}][view]`, permission?.view ? '1' : '0')
      location.append(`permissions[${userId}][edit]`, permission?.edit ? '1' : '0')
      location.append(`permissions[${userId}][delete]`, permission?.delete ? '1' : '0')
    }
  }
  return location
}