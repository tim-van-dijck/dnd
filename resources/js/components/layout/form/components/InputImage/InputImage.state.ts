import { ChangeEvent, useEffect, useState } from "react";

export const useImageHandling = (onChange: (value: File | null) => void) => {
  const [ src, setSrc ] = useState<string | null>(null)
  const [ value, setValue ] = useState<File | null>(null)

  useEffect(() => {
    onChange(value)
  }, [ value ])

  const handleFileChange = (e: ChangeEvent<HTMLInputElement>) => {
    const files = e.target.files
    if (files?.length) {
      createImage(files[0])
    }
  }

  const createImage = (file) => {
    setValue(file)
    const reader = new FileReader()
    reader.onload = (e) => {
      setSrc(e.target!.result as string)
    }
    reader.readAsDataURL(file)
  }

  return { handleFileChange, src }
}