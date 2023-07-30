export interface IFormOption {
  label: string | JSX.Element
  value: string | number
  disabled?: boolean
  type?: 'option'
  children?: IFormOption[]
}

export interface IFormOptionGroup {
  label: string
  disabled?: boolean
  type: 'group'
  children: IFormOption[]
}

export interface InputProps {
  id: string
  name: string
  label: string | JSX.Element
  initialValue?
  errors?: string | string[]
  info?: string | JSX.Element
  required?: boolean
  onChange: (value) => void
}