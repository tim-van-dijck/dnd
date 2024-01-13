import { FC } from "react";
import Modal from "../../../../../components/layout/Modal";
import Spellbook from "../Spellbook";
import { SpellbookModalProps } from "./types";

const SpellbookModal: FC<SpellbookModalProps> = ({ name, children }) => {
  return <div id="spellbook">
    {children}
    <Modal id={name} title={<><i className="fas fa-book"></i> Spellbook</>} trigger={<></>}>
      <Spellbook />
      <button className=" uk-modal-close-default uk-close-large" type="button" data-uk-close />
    </Modal>
  </div>
}

export default SpellbookModal