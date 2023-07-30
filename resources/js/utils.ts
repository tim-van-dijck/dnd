import axios from "axios";

export const logout = () => axios.post('/logout').then(() => document.location.href = '/')