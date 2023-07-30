import { PaginatedData } from "../../../../../repositories/BaseRepository";

type TPage = {
  number: number | string
  active: boolean
}

export const usePagination = (records: PaginatedData<any>, go: (number: number) => void) => {
  const currentPage = records?.meta?.current_page || 1
  const lastPage = records?.meta?.last_page || 1

  const pages = getPages(records, currentPage, lastPage)

  return {
    pages,
    go: (number: number) => {
      if (number !== currentPage.value) {
        go(number)
      }
    },
  }
}

const getPages = (records: PaginatedData<any> | null, currentPage: number, lastPage: number) => {
  if (records === null) return []

  const pages: TPage[] = [
    { number: 1, active: currentPage === 1 }
  ]

  if (currentPage - 1 > 2) {
    pages.push({ number: '...', active: false })
  }

  for (let i = -2; i <= 2; i++) {
    if (currentPage + i > 1 && currentPage + i <= lastPage) {
      pages.push({ number: currentPage + i, active: i === 0 })
    }
  }

  if (lastPage - currentPage > 2) {
    pages.push({ number: '...', active: false })
    pages.push({ number: lastPage, active: false })
  }

  return pages
}