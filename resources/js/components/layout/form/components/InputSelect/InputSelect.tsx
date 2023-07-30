import classNames from "classnames";
import { FC } from "react";
import { useInputSelectState } from "./InputSelect.state";
import { SelectProps } from "./types";

const InputSelect: FC<SelectProps> = ({
  id,
  name,
  label,
  emptyLabel,
  initialValue,
  options,
  errors,
  onChange,
  required
}) => {
  const { value, update } = useInputSelectState(initialValue, onChange)


  return <div className="uk-margin">
    {label ?
      <label htmlFor={id}
             className={classNames(
               'uk-form-label',
               {
                 'uk-text-danger': (
                   errors?.length || 0
                 ) > 0
               }
             )}>
        {label}{required ? '*' : ''}
      </label> : null}
    <select id={id}
            name={name}
            className={classNames(
              'uk-input',
              {
                'uk-form-danger': (
                  errors?.length || 0
                ) > 0
              }
            )}
            value={value}
            onChange={(e) => update(e.target.value)}>
      <option value="">- {emptyLabel || 'Make a choice'} -</option>
      {options.map((option) => {
        if (option?.type === "group") {
          return <optgroup key={option.label} label={option.label}>
            {option.children
              .map((child) =>
                <option key={child.value} value={child.value} disabled={!!child.disabled}>{child.label}</option>)}
          </optgroup>
        }
        return <option key={option.value}
                       value={option.value}
                       disabled={!!option.disabled}>{option.label}</option>
      })}
    </select>
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

export default InputSelect