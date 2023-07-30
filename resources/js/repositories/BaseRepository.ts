import axios from "axios";

export interface PaginatedData<T> {
  data: T[]
  meta,
  links
}

export interface BaseRepository<T> {
  load: (filters?: { [k: string]: string }) => Promise<PaginatedData<T>>
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
    load(filters?: { [k: string]: string }): Promise<PaginatedData<T>> {
      const params = {}
      if (filters != null) {
        for (let key in filters || {}) {
          params[`filters[${key}]`] = filters[key]
        }
      }
      const query = new URLSearchParams(params)
      return axios.get(`${url}?${query}`)
        .then((response) => response.data)
    }
  }
}