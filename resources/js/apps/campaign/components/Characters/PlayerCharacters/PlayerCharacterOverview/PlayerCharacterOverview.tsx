import { Link } from 'react-router-dom'
import PaginatedTable from '../../../../../../components/layout/PaginatedTable'
import { useCharacterOverviewState } from './PlayerCharacterOverview.state'
import { usePlayerCharacterOverviewUi } from './PlayerCharacterOverview.ui'

const PlayerCharacterOverview = () => {
  const { characterRepository, destroy } = useCharacterOverviewState()
  const { actions, columns } = usePlayerCharacterOverviewUi()

  return <div className="player-characters">
    <Link className="uk-button uk-button-primary" to="/characters/players/create">
      <i className="fas fa-plus"></i> Add character
    </Link>
    {
      (
        characterRepository.characters !== null && characterRepository.characters.data.length > 0
      ) ?
        <PaginatedTable
          actions={actions}
          columns={columns}
          repository={{
            previous: characterRepository.previous,
            page: characterRepository.page,
            next: characterRepository.next,
            load: characterRepository.load
          }}
          records={characterRepository.characters}
          onAction={(type: string, row) => {
            if (type === 'destroy') characterRepository.destroy(row.id)
          }}
        /> :
        <p className="uk-text-center">
          {
            characterRepository.characters == null ?
              <i className="fas fa-sync fa-spin fa-2x" /> :
              <span>No characters found</span>
          }
        </p>
    }
  </div>
}

export default PlayerCharacterOverview