import { createRoot } from "react-dom/client";
import Bootstrap from "../../bootstrap";
import Admin from './components/Admin'
import { AdminRepositoryProvider } from "./providers/AdminRepositoryProvider";
import { store } from "./store";

window.onload = () => {
  createRoot(document.getElementById('admin-app')!).render(
    <Bootstrap store={store}>
      <AdminRepositoryProvider><Admin /></AdminRepositoryProvider>
    </Bootstrap>)
}