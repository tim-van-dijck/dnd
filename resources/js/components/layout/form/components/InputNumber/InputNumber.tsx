import classNames from "classnames";
import { FC } from "react";
import { InputNumberProps } from "./types";

const InputNumber: FC<InputNumberProps> = ({
  id,
  initialValue,
  info,
  name,
  required,
  label,
  errors,
  onChange,
  min,
  max,
  step
}) => {
  return <div className="uk-margin">
    <label htmlFor={id}
           className={classNames(
             "uk-form-label",
             {
               'uk-text-danger': (
                 errors?.length || 0
               ) > 0
             }
           )}>
      {label}{required ? '*' : ''}
    </label>

    <input id={id}
           type="number"
           min={min}
           max={max}
           step={step}
           className={classNames(
             'uk-input',
             {
               'uk-form-danger': (
                 errors?.length || 0
               ) > 0
             }
           )}
           defaultValue={initialValue} onChange={(e) => onChange(parseInt(e.target.value || '0'))} />
    {errors ? (
      <div className="invalid-feedback">
        {typeof errors === 'string' ?
          <><span>{errors}</span><br /></>
          : Object.values(errors).map((error) => (
            <>
              <span>{error}</span>
              <br />
            </>
          ))}
      </div>
    ) : null}
  </div>
}

export default InputNumber