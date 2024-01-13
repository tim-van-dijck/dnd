import classNames from "classnames";
import { FC, useEffect, useState } from "react";
import { InputBooleanProps } from "./types";

const InputBoolean: FC<InputBooleanProps> = ({ id, initialValue, info, name, required, label, errors, onChange }) => {
  const [ checked, setChecked ] = useState<boolean>(!!initialValue)

  useEffect(() => void onChange(checked), [ checked ])

  return (
    <div className="uk-margin">
      <label htmlFor={id}
             className={classNames('uk-form-label', { 'uk-text-danger': Object.keys(errors || {}).length > 0 })}>
        <input id={id} className="uk-checkbox uk-margin-small-right" type="checkbox" name={name}
               checked={checked} onChange={(e) => {
          setChecked(e.target.checked)
        }} />
        {label}{required ? '*' : ''}
      </label>
    </div>
  )
}

export default InputBoolean