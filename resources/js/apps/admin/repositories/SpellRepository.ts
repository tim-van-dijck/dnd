import { PaginatedData, useRepository } from "../../../repositories/BaseRepository";
import axios from "axios";
import { Spell, SpellInput } from "@dnd/types";
import { SpellRepositoryInterface } from "./types";
import { useMessageBus } from "../../../services/messages";
import { useAdminDispatch, useAdminSelector } from "../store";
import { setSpells } from "../../../stores/spells";

export const useSpellRepository = (): SpellRepositoryInterface => {
  const url = '/api/admin/spells'
  const messageBus = useMessageBus()
  const repo = useRepository<Spell>(url)
  const spells: PaginatedData<Spell> | null = useAdminSelector(state => state.spells.spells) as PaginatedData<Spell> | null
  const dispatch = useAdminDispatch()

  const page = (number: number): Promise<PaginatedData<Spell>> | null => {
    if (spells != null && number > 0 && number <= spells.meta.last_page && number >= 1) {
      return repo.page(number)?.then?.((records) => {
        dispatch(setSpells(records))
        return records
      }) || null
    }
    return null
  }

  return {
    spells,
    previous: () => (
      spells?.meta?.current_page > 1
    ) ? page(spells?.meta?.current_page - 1) : null,
    page,
    next: () =>
      (
        spells?.meta?.current_page < spells?.meta?.last_page
      ) ? page(spells?.meta?.current_page + 1) : null,
    load: () => repo.load().then((records) => {
      dispatch(setSpells(records))
      return records
    }),
    find: (id: number): Promise<Spell> => axios.get(`${url}/${id}`).then((response) => response.data.data),

    store: (spell: SpellInput) => axios.post(url, spell).then(() => messageBus.success('Spell saved!')),
    update: (id: number, spell: SpellInput) => axios.put(`${url}/${id}`, spell)
      .then(() => messageBus.success('Spell saved!')),
    destroy: (id: number) => axios.delete(`${url}/${id}`)
      .then(() => repo.load().then(() => messageBus.success('Spell successfully deleted!'))),
  }
}