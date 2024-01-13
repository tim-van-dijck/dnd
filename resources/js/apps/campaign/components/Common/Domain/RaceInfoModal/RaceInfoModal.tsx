import { useRaceInfoModalRaces } from './RaceInfoModal.state'
import { useRaceInfoModalActiveSelection } from './RaceInfoModal.ui'
import RaceInfoModalView from './RaceInfoModal.view'

const RaceInfoModal = () => {
  const races = useRaceInfoModalRaces()
  const ui = useRaceInfoModalActiveSelection(races)

  if (!races) return null

  return <RaceInfoModalView state={{ races }} ui={ui} />
}

export default RaceInfoModal