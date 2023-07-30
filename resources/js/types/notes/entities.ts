import { NoteInput } from "./input";

export interface Note extends NoteInput {
  id: string
  created_at: Date
  updated_at: Date
}