import { FC } from "react";
import { useDraggableState } from "./Draggable.state";
import { DraggableProps } from "./types";

const Draggable: FC<DraggableProps> = ({ items, onUpdate }) => {
  const { dragStart, dragEnter, drop, list } = useDraggableState(items, onUpdate)


  return <div className="draggable">
    {list.map((item, index) => <div key={item.key} className="draggable-item"
                                    draggable
                                    onDragStart={(e) => dragStart(e, index)}
                                    onDragEnd={drop}
                                    onDragEnter={(e) => dragEnter(e, index)}>{item.content}</div>)}
  </div>
}

export default Draggable