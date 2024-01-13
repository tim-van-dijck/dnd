import { useParams } from 'react-router'
import { usePlayerCharacterFormState } from './PlayerCharacterForm.state'
import { usePlayerCharacterFormNavigation, useSpellcaster, useTitle } from './PlayerCharacterForm.ui'
import PlayerCharacterFormView from './PlayerCharacterForm.view'

const PlayerCharacterForm = () => {
  const { id: idAsString } = useParams()
  const id = idAsString ? parseInt(idAsString) : undefined
  const { can, classes, errors, input, loading, save, update } = usePlayerCharacterFormState(id)
  const title = useTitle(id, input)
  const spellcaster = useSpellcaster(input, classes)
  const navigation = usePlayerCharacterFormNavigation(save)

  return <PlayerCharacterFormView state={{ errors, id, input, save, update }}
                                  ui={{ can, loaded: !loading, navigation, spellcaster, title }} />
}

export default PlayerCharacterForm