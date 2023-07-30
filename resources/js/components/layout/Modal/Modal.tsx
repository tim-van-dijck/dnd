import { cloneElement, FC, FunctionComponentElement, useEffect, useRef, useState } from "react";
import { createPortal } from "react-dom";
import ModalView from "./Modal.view";
import { ModalProps } from "./types";

const Modal: FC<ModalProps> = ({ children, id, title, trigger }) => {
  const ref = useRef<HTMLDivElement | null>(null)
  const [ triggerElement, setTriggerElement ] = useState<FunctionComponentElement<any> | null>(null)

  useEffect(() => {
    const modalRoot = document.createElement('div');
    modalRoot.setAttribute('uk-modal', '');
    modalRoot.id = id;
    ref.current = modalRoot
    document.body.appendChild(ref.current);

    return () => {
      if (ref.current) {
        ref.current.remove()
        ref.current = null
      }
    }
  }, [])

  useEffect(() => {
    if (trigger) {
      const cloned = cloneElement(trigger, { 'data-uk-toggle': `#${id}` })
      setTriggerElement(cloned)
    }
  }, [ trigger ])

  return <>
    {triggerElement}
    {
      ref.current ? createPortal(<ModalView ui={{ title }}>{children}</ModalView>, ref.current) :
        <ModalView ui={{ title }}>{children}</ModalView>
    }
  </>
}

export default Modal;