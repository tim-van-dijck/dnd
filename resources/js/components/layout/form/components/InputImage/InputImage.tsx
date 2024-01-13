import classNames from "classnames";
import { FC } from "react";
import { useImageHandling } from "./InputImage.state";
import { InputImageProps } from "./InputImage.types";

const InputImage: FC<InputImageProps> = ({ id, name, label, required, errors, initialValue, onChange }) => {
  const { src, handleFileChange } = useImageHandling(onChange)

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
    {src ?
      <img className="preview-image"
           src={src}
           alt="Uploaded image"
           width="300"
           height="300" /> :
      null
    }
    <input id="map"
           name="map"
           type="file"
           className={classNames({ 'uk-form-danger': errors?.hasOwnProperty('map') })}
           onChange={handleFileChange} />
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

export default InputImage