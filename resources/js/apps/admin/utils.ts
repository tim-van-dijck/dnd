export const useUpdateField = (data) => {
  return (field: string, value) => (
    { ...data, ...newFieldValue(data, field, value) }
  )
}

export const newFieldValue = (data, key: string, value) => {
  const segments = key.split(".")
  if (segments.length === 1) {
    return { [key]: value }
  }

  const firstSegment = segments[0]
  const nestedKey = segments.slice(1).join(".")
  return {
    [firstSegment]: { ...data[firstSegment], ...newFieldValue(data[firstSegment], nestedKey, value) }
  }
}

