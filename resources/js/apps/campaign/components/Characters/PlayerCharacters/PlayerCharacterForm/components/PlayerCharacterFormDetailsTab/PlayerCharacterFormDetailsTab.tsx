import { FC } from 'react'
import { alignments, usePlayerCharacterFormDetailState } from './PlayerCharacterFormDetailsTab.state'
import PlayerCharacterFormDetailsTabView from './PlayerCharacterFormDetailsTab.view'
import { PlayerCharacterFormDetailsTabProps } from './PlayerCharacterFormDetailsTabView.types'

const PlayerCharacterFormDetails: FC<PlayerCharacterFormDetailsTabProps> = ({ errors, value, onNext, onUpdate }) => {
  const { input, isOwner, races, subraces, users, update } = usePlayerCharacterFormDetailState(value, onUpdate)

  if (!input) return null

  return <PlayerCharacterFormDetailsTabView state={{
    alignments,
    input,
    isOwner,
    errors,
    races,
    subraces,
    users,
    next: onNext,
    update
  }} />
}

export default PlayerCharacterFormDetails