import { FC } from 'react'
import { useCharacterProficiencies } from './PlayerCharacterProficiencyTab.state'
import { PlayerCharacterProficiencyTabProps } from './PlayerCharacterProficiencyTab.types'
import PlayerCharacterProficiencyTabView from './PlayerCharacterProficiencyTab.view'

const PlayerCharacterProficiencyTab: FC<PlayerCharacterProficiencyTabProps> = ({ active, proficiencies }) => {
  const { armor, characterLanguages, instruments, loading, skills, tools, weapons } = useCharacterProficiencies(
    proficiencies
  )

  if (!active) return null

  return <PlayerCharacterProficiencyTabView state={{ armor, characterLanguages, instruments, skills, tools, weapons }}
                                            ui={{ loading }} />
}

export default PlayerCharacterProficiencyTab