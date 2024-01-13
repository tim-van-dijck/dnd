import classNames from "classnames";
import { FC } from "react";
import { InputEmailProps } from "./types";

const InputEmail: FC<InputEmailProps> = ({ id, initialValue, info, name, required, label, errors, onChange }) => {
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
      {`${label}${required ? '*' : ''}`}
    </label>
    <input id={id} type="email"
           className={classNames(
             'uk-input',
             {
               'uk-form-danger': (
                 errors?.length || 0
               ) > 0
             }
           )}
           defaultValue={initialValue} onChange={(e) => onChange(e.target.value)} />
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

export default InputEmail