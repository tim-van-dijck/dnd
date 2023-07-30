import { PaginatedData, PaginationRepository } from "../../../../../repositories/BaseRepository";

export interface IProps {
  records: PaginatedData<any>
  repository: PaginationRepository<any>
}