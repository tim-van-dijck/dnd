import { Language } from '@dnd/types'
import axios from 'axios'
import { useEffect, useState } from 'react'

export const useLanguages = (): { languages: Language[] | null } => {
  const [ languages, setLanguages ] = useState<Language[] | null>(null)

  useEffect(() => {
    loadLanguages().then((results) => setLanguages(results))
  }, [])

  return { languages }
}

const loadLanguages = (): Promise<Language[]> => {
  return axios.get(`/api/languages`)
    .then((response) => response.data.data)
}