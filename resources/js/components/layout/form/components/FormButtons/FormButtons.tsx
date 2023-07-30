import { CFormButtons } from "./types";
import { FC } from "react";

const FormButtons: FC<CFormButtons> = ({ cancel, saveText }) => {
  return <p className="uk-margin">
    <button type="submit" className="uk-button uk-button-primary uk-margin-right">{saveText || 'Save'}</button>
    <button className="uk-button uk-button-danger" onClick={(e) => {
      e.preventDefault()
      cancel()
    }}>Cancel
    </button>
  </p>
}

export default FormButtons