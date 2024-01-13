export interface DraggableProps {
  items: DraggableItem[]
  onUpdate: DraggableUpdate
}

export interface DraggableItem {
  key: string | number
  content: string | JSX.Element
}

export type DraggableUpdate = (items: DraggableItem[]) => void