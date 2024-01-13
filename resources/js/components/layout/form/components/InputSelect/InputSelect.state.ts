import { useEffect, useState } from "react";

export const useInputSelectState = (
  initialValue: string | number | undefined,
  onChange: (value: string | number) => void
) => {
  const [ value, setValue ] = useState<string | number | undefined>(initialValue)

  useEffect(() => {
    if (!!initialValue) setValue(initialValue)
  }, [ initialValue ])
  const update = (updatedValue: string | number) => {
    const parsed = (
      typeof updatedValue === "string" && !isNaN(parseInt(updatedValue))
    ) ? parseInt(updatedValue) : updatedValue

    setValue(parsed)
    onChange(parsed)
  }

  return { update, value, setValue }
}