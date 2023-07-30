export interface PermissionFormProps {
  entity: string
  id?: number
  onChange: (value) => void
  value?: Record<string, any>
  className?: string
}