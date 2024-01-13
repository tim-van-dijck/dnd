import { FC } from "react";
import { ModalViewProps } from "./types";

const ModalView: FC<ModalViewProps> = ({ ui, children }) => {
  return <div className="uk-modal-dialog uk-modal-body">
    <>
      {ui.title ? <h2 className="uk-modal-title">{ui.title}</h2> : null}
      {children}
      <button className=" uk-modal-close-default uk-close-large" type="button" data-uk-close />
    </>
  </div>
}

export default ModalView