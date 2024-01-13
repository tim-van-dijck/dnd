export interface FeatureListProps {
  items: FeatureListItem[]
  remove: (id: number, index: number) => void
}

interface FeatureListItem {
  id: number
  name: string
  description?: string
  optional?: boolean
}