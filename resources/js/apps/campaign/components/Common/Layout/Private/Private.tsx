import { FC } from "react";
import { CampaignEntity } from "../../../../types";

const Private: FC<{ entity: CampaignEntity }> = ({ entity }) => {
  const title = `This ${entity} is private`
  return <span className="private" title={title}><i className="fas fa-user-secret" /></span>
}

export default Private