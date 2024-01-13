import { Editor } from "@tinymce/tinymce-react";
import classNames from "classnames";
import { FC, useEffect, useRef, useState } from "react";
import { InputRichTextProps } from "./types";

const InputRichText: FC<InputRichTextProps> = ({
  id,
  initialValue,
  info,
  name,
  required,
  label,
  errors,
  height,
  onChange
}) => {
  const editorRef = useRef<any>(null);
  const [ value, setValue ] = useState<string>(initialValue || '')

  useEffect(() => onChange(value), [ value ])

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
    <Editor
      id={id}
      onInit={(evt, editor) => editorRef.current = editor}
      value={value}
      init={{
        height: height || 400,
        plugins: [
          'link', 'anchor', 'fullscreen',
          'searchreplace', 'autolink', 'table'
        ],
        theme: 'silver',
        skin: 'oxide',
        skin_url: '/skins/ui/oxide',
        toolbar1: 'formatselect | bold italic underline strikethrough forecolor backcolor | link table | alignleft aligncenter alignright  | numlist bullist outdent indent | removeformat',
        init_instance_callback() {
          const freeTiny = document.querySelector('.tox-notifications-container') as HTMLElement
          if (freeTiny) freeTiny.style.display = 'none'
        }
      }}
      onEditorChange={(value) => setValue(value)}
    />
  </div>
}

export default InputRichText