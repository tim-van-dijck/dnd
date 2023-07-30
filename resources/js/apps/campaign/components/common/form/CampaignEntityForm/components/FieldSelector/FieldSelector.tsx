import { FC } from "react";
import InputBoolean from "../../../../../../../../components/layout/form/components/InputBoolean";
import InputCheckbox from "../../../../../../../../components/layout/form/components/InputCheckbox";
import InputEmail from "../../../../../../../../components/layout/form/components/InputEmail";
import InputNumber from "../../../../../../../../components/layout/form/components/InputNumber";
import InputRichText from "../../../../../../../../components/layout/form/components/InputRichText";
import InputSelect from "../../../../../../../../components/layout/form/components/InputSelect";
import InputText from "../../../../../../../../components/layout/form/components/InputText";
import { IFormOption } from "../../../../../../../../components/layout/form/types";
import { FieldSelectorProps } from "./types";

const FieldSelector: FC<FieldSelectorProps> = ({ field, update, initialValue, errors }) => {
  switch (field.type) {
    case 'text':
      return <InputText id={field.name}
                        name={field.name}
                        label={field.label}
                        required={field.required}
                        errors={errors?.[field.name]}
                        initialValue={initialValue as string | undefined}
                        onChange={value => update(field.name, value)} />
    case 'email':
      return <InputEmail id={field.name}
                         name={field.name}
                         label={field.label}
                         required={field.required}
                         errors={errors?.[field.name]}
                         initialValue={initialValue as string | undefined}
                         onChange={value => update(field.name, value)} />
    case 'richtext':
      return <InputRichText id={field.name}
                            name={field.name}
                            label={field.label}
                            required={field.required}
                            errors={errors?.[field.name]}
                            initialValue={initialValue as string | undefined}
                            onChange={value => update(field.name, value)} />
    case 'boolean':
      return <InputBoolean id={field.name}
                           name={field.name}
                           label={field.label}
                           required={field.required}
                           errors={errors?.[field.name]}
                           initialValue={!!initialValue}
                           onChange={value => update(field.name, value)} />
    case 'number':
      return <InputNumber id={field.name}
                          name={field.name}
                          label={field.label}
                          required={field.required}
                          errors={errors?.[field.name]}
                          initialValue={initialValue as number | undefined}
                          onChange={value => update(field.name, value)} />
    case 'checkbox':
      return <InputCheckbox id={field.name}
                            name={field.name}
                            label={field.label}
                            required={field.required}
                            errors={errors?.[field.name]}
                            initialValue={initialValue as string[] | number[] | undefined}
                            options={field.options as IFormOption[]}
                            onChange={value => update(field.name, value)} />
    case 'select':
      return <InputSelect id={field.name}
                          name={field.name}
                          label={field.label}
                          required={field.required}
                          errors={errors?.[field.name]}
                          initialValue={initialValue as string | number | undefined}
                          options={field.options!}
                          onChange={value => update(field.name, value)} />
    default:
      throw new Error(`Unsupported field type [${field.type}] in CampaignEntityForm`)
  }
}

export default FieldSelector