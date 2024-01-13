import axios from "axios";

export interface PaginatedData<T> {
  data: T[]
  meta,
  links
}

export interface BaseRepository<T> {
  load: (params?: { filters?: Record<string, string>, includes?: string[] }) => Promise<PaginatedData<T>>
  page: (number: number) => Promise<PaginatedData<T>> | null
}

export interface PaginationRepository<T> extends BaseRepository<T> {
  previous: () => Promise<PaginatedData<T>> | null
  next: () => Promise<PaginatedData<T>> | null
}

export const useRepository = <T>(url): BaseRepository<T> => {
  return {
    page(number: number): Promise<PaginatedData<T>> | null {
      return axios.get(`${url}?page[number]=${number}`)
        .then((response) => response.data)
    },
    load(params?: { filters?: Record<string, string>, includes?: string[] }): Promise<PaginatedData<T>> {
      const urlParams = {
        ...Object.fromEntries(Object.entries(params?.filters || {})
          .map(([ field, value ]) => [ `filter[${field}]`, value ])),
        ...(
          Array.isArray(params?.includes) ? { includes: params?.includes.join(',') } : {}
        )
      }
      const query = new URLSearchParams(urlParams)
      return axios.get(`${url}?${query}`)
        .then((response) => response.data)
    }
  }
}