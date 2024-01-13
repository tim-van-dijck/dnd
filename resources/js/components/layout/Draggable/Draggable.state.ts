import { DragEvent, useEffect, useRef, useState } from "react";
import { DraggableItem, DraggableUpdate } from "./types";

export const useDraggableState = (items: DraggableItem[], onUpdate: DraggableUpdate) => {
  const draggedItem = useRef<number | undefined>()
  const draggedOverItem = useRef<number | undefined>();
  const [ list, setList ] = useState<DraggableItem[]>([])

  useEffect(() => {
    setList(items || [])
  }, [ items ])

  const dragStart = (event: DragEvent<HTMLDivElement>, position: number) => {
    draggedItem.current = position
  }

  const dragEnter = (event: DragEvent<HTMLDivElement>, position: number) => {
    draggedOverItem.current = position
  }

  const drop = () => {
    const sorted = [ ...list ];
    const draggedItemContent = sorted[draggedItem.current as number];

    sorted.splice(draggedItem.current as number, 1);
    sorted.splice(draggedOverItem.current as number, 0, draggedItemContent);
    draggedItem.current = undefined
    draggedOverItem.current = undefined
    setList(sorted);
    onUpdate(sorted)
  }

  return {
    dragStart,
    dragEnter,
    drop,
    list
  }
}