import { FC } from "react";
import { useParams } from "react-router";
import { usePlayerCharacterDetails } from "./PlayerCharacterDetail.state";
import { usePlayerCharacterDetailTabs } from "./PlayerCharacterDetail.ui";
import PlayerCharacterDetailView from "./PlayerCharacterDetail.view";

const PlayerCharacterDetail: FC = () => {
  const { id: idAsString } = useParams()
  const { character, isSpellcaster } = usePlayerCharacterDetails(parseInt(idAsString || ''))
  const { tabs, tab, navigate } = usePlayerCharacterDetailTabs(false)

  return <PlayerCharacterDetailView state={{ character, isSpellcaster: false }} ui={{ navigate, tab, tabs }} />
}

export default PlayerCharacterDetail