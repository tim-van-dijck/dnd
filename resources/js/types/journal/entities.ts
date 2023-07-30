import { JournalEntryInput } from "./input";

export interface JournalEntry extends JournalEntryInput {
  id: number
  created_at: Date
  updated_at: Date
}