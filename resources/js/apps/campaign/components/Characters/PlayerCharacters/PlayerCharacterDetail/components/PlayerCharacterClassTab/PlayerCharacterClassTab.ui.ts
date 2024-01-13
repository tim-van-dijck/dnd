import { useState } from "react";

export const usePlayerCharacterClassActiveFeatures = () => {
  const [ activeFeatures, setActiveFeatures ] = useState<Record<number, {
    id: number
    name: string
    description: string
  }>>({})

  const setActive = (classId: string, object) => {
    const updated = { ...activeFeatures, [classId]: object }
    setActiveFeatures(updated)
  }
  return { activeFeatures, setActive }
}