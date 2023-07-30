import classNames from "classnames";
import { FC, useEffect, useState } from "react";
import { InputCheckboxProps } from "./types";

const InputCheckbox: FC<InputCheckboxProps> = ({ id, label, initialValue, options, errors, onChange, required }) => {
  const [ checked, setChecked ] = useState<Array<string | number>>(initialValue || [])

  const toggleCheck = (value: string | number, inputChecked: boolean) => {
    if (inputChecked) {
      setChecked([ ...checked, value ].filter((value, index, self) => self.indexOf(value) === index))
    } else {
      setChecked(checked.filter((option) => option != value))
    }
  }

  useEffect(() => onChange(checked), [ checked ])

  return <div className="uk-margin">
    <label className={classNames(
      "uk-form-label",
      {
        'uk-text-danger': (
          errors?.length || 0
        ) > 0
      }
    )}>
      {label}{required ? '*' : ''}
    </label>
    <div className="uk-form-controls">
      {options.map((option) => (
        <label key={`label-${id}-${option.value}`} htmlFor={`${id}_check_${option.value}`} className={classNames(
          "uk-form-label",
          {
            'uk-text-danger': (
              errors?.length || 0
            ) > 0
          }
        )}>
          <input id={`${id}_check_${option.value}`}
                 className="uk-checkbox uk-margin-small-right"
                 type="checkbox"
                 value={option.value}
                 checked={checked.includes(option.value)}
                 onChange={(e) => toggleCheck(option.value, e.target.checked)} />
          {option.label}
        </label>
      ))}
    </div>
    {errors ? (
      <div className="invalid-feedback">
        {typeof errors === 'string' ?
          <><span>{errors}</span><br /></>
          : Object.values(errors).map((error) => (
            <span key={`error-${btoa(error)}`}>{error}</span>
          ))}
      </div>
    ) : null}
  </div>
}

export default InputCheckbox