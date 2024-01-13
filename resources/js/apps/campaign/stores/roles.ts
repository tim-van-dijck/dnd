import { createSlice } from "@reduxjs/toolkit";
import { PaginatedData } from "../../../repositories/BaseRepository";
import { Permission, Role } from "../../../types/roles";

type RoleState = {
  roles: PaginatedData<Role> | null
  permissions: Permission[] | null
}

const initialState: RoleState = {
  roles: null,
  permissions: null
}

export const roleSlice = createSlice({
  name: 'roles',
  initialState,
  reducers: {
    setRoles: (state, action) => {
      state.roles = action.payload
    },
    setPermissions: (state, action) => {
      state.permissions = action.payload
    }
  }
})

export const { setRoles, setPermissions } = roleSlice.actions

export default roleSlice.reducer