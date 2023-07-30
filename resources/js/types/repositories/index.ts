import { Spell, SpellInput } from "@dnd/types";
import { BaseRepositoryInterface } from "../../apps/admin/repositories/types";
import { PaginatedData } from "../../repositories/BaseRepository";


export interface SpellRepositoryInterface extends BaseRepositoryInterface<Spell, SpellInput> {
  spells: PaginatedData<Spell> | null
}