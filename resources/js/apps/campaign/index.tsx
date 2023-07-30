import axios from "axios";
import { createRoot } from "react-dom/client";
import Bootstrap from "../../bootstrap";
import Campaign from "./components/Campaign";
import { CampaignRepositoryProvider } from "./providers/CampaignRepositoryProvider";
import { store } from "./store";


axios.defaults.headers.common['Campaign-Id'] =
  document.querySelector('meta[name="campaign-id"]')?.getAttribute('content')!

window.onload = () => {
  createRoot(document.getElementById('app')!).render(<Bootstrap store={store}>
    <CampaignRepositoryProvider>
      <Campaign />
    </CampaignRepositoryProvider>
  </Bootstrap>)
}