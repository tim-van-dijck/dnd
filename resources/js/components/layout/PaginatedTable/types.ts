import { PaginatedData, PaginationRepository } from '../../../repositories/BaseRepository'

export type IProps = {
  actions: PaginatedTableAction[]
  columns: any[]
  records: PaginatedData<any> | null
  repository: PaginationRepository<any>
  onAction?: (type: string, row: Record<string, any>) => void
}

export type PaginatedTableAction = {
  name: string
  title: string
  to?: string | ((row) => string)
  href?: string | ((row) => string)
  newTab?: boolean
  icon?: string
  classes?: string
  condition?: (row) => boolean
}