import { BrowserRouter, Route, Routes } from "react-router-dom";
import HeaderNavbar from "../../../components/layout/HeaderNavbar";
import { MessagesProvider } from "../../../components/layout/Messages";
import router from '../router'
import Navigation from "./layout/Navigation";

const Admin = () => {
  return (
    <>
      <div id="admin-app">
        <BrowserRouter basename="/admin">
          <HeaderNavbar />
          <Navigation />
          <MessagesProvider>
            <div id="content" data-uk-height-viewport="expand: true">
              <div className="uk-container uk-container-expand">
                <div className="uk-section uk-section-default">
                  <div className="uk-container padded">
                    <Routes>
                      {router.map((route) =>
                        <Route key={route.path} path={route.path} element={route.element} />)}
                    </Routes>
                  </div>
                </div>
                <footer className="uk-section uk-section-small uk-text-center">
                  <hr />
                  <p className="uk-text-small uk-text-center">
                    Made with love by <a href="https://bit.ly/2Rz0xNG" target="_blank">Tim van Dijck</a>
                  </p>
                </footer>
              </div>
            </div>
          </MessagesProvider>
        </BrowserRouter>
      </div>
    </>
  )
}

export default Admin