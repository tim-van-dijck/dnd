import { FC } from "react";
import { usePlayerCharacterClasses } from "./PlayerCharacterClassTab.state";
import { PlayerCharacterClassTabProps } from "./PlayerCharacterClassTab.types";
import { usePlayerCharacterClassActiveFeatures } from "./PlayerCharacterClassTab.ui";
import PlayerCharacterClassTabView from "./PlayerCharacterClassTab.view";

const PlayerCharacterClassTab: FC<PlayerCharacterClassTabProps> = ({ classes, active }) => {
  const { loading, characterClasses } = usePlayerCharacterClasses(classes)
  const { activeFeatures, setActive } = usePlayerCharacterClassActiveFeatures()

  if (!active) return null

  return <PlayerCharacterClassTabView state={{ loading, characterClasses }} ui={{ activeFeatures, setActive }} />
}

export default PlayerCharacterClassTab