import {PaginatedData, PaginationRepository} from "../../../repositories/BaseRepository";

export type IProps = {
  actions: any[]
  columns: any[]
  records: PaginatedData<any> | null
  repository: PaginationRepository<any>
  onAction?: (type: string, row: Record<string, any>) => void
}