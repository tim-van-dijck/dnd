import { FC } from "react";
import { useParams } from "react-router";
import { useLocationFormState } from "./LocationForm.state";
import { useLocationFormUI } from "./LocationForm.ui";
import LocationFormView from "./LocationForm.view";

const LocationForm: FC = () => {
  const { id: idAsString } = useParams()
  const id: number | undefined = typeof idAsString === 'string' ? parseInt(idAsString) : idAsString
  const { errors, input, save, update } = useLocationFormState(id)
  const { can, tab, setTab, redirect, title } = useLocationFormUI(input!, id)

  return <LocationFormView state={{ errors, input, save, update }} ui={{ can, redirect, tab, setTab, title }} />
}

export default LocationForm