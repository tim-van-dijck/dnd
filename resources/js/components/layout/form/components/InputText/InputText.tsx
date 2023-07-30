import classNames from "classnames";
import { FC } from "react";
import { InputTextProps } from "./types";

const InputText: FC<InputTextProps> = ({
  id,
  initialValue,
  info,
  name,
  required,
  label,
  errors,
  onChange,
  multiline
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
    {multiline ?
      <textarea id={id}
                className="uk-width uk-textarea uk-height-max-small"
                name={name}
                onChange={(e) => onChange(e.target.value)} defaultValue={initialValue} /> :
      <input id={id} type="text"
             className={classNames(
               'uk-input',
               {
                 'uk-form-danger': (
                   errors?.length || 0
                 ) > 0
               }
             )}
             defaultValue={initialValue} onChange={(e) => onChange(e.target.value)} />}
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

export default InputText