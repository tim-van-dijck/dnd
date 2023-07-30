import { useCampaignRepository } from "./CampaignRepository";
import { useRaceRepository } from "./RaceRepository";
import { useSpellRepository } from "./SpellRepository";
import { useUserRepository } from "./UserRepository";
import { BaseRepositoryInterface } from "./types";

export const useRepositoryContext = () => {
  const CampaignRepository = useCampaignRepository()
  const RaceRepository = useRaceRepository()
  const SpellRepository = useSpellRepository()
  const UserRepository = useUserRepository()

  const initial = {
    CampaignRepository,
    RaceRepository,
    SpellRepository,
    UserRepository
  }
}

export const mockInitialRepository = (): BaseRepositoryInterface<any, any> => (
  {
    previous: () => null,
    page: (number: number) => null,
    next: () => null,
    load: () => Promise.resolve({ data: [], meta: {}, links: {} }),
    find: (id: number) => Promise.resolve({}),
    store: (model: any) => Promise.resolve(),
    update: (id: number, model: any) => Promise.resolve(),
    destroy: (id: number) => Promise.resolve()
  }
)