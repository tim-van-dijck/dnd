import { LocationInput } from "./input";

export interface Location extends LocationInput {
  id: number
  location?: Location
  created_at: Date
  updated_at: Date
}