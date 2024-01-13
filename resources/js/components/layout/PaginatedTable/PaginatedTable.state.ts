import { debounce } from 'lodash'
import { useEffect, useState } from "react";

export const useSearch = (store) => {
  const [ query, setQuery ] = useState('')
  const search = debounce(function () {
    if (query.length === 0) {
    }
    if (query.length >= 3) {
      store.load({ query: query })
    }
  }, 500)

  useEffect(() => {
    search()
  }, [ query ])

  return { query, setQuery, search }
}