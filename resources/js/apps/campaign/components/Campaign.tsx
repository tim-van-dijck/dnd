import { useEffect } from 'react'
import { BrowserRouter, Routes } from 'react-router-dom'
import HeaderNavbar from '../../../components/layout/HeaderNavbar'
import { MessagesProvider } from '../../../components/layout/Messages'
import { useCampaignRepositories } from '../providers/CampaignRepositoryProvider'
import router from '../router'
import Navigation from './Common/Layout/Navigation'

const Campaign = () => {
  const { AuthRepository, CampaignRepository } = useCampaignRepositories()

  useEffect(() => {
    AuthRepository.loadUser()
    CampaignRepository.loadCampaign()
  }, [])

  if (AuthRepository.user === null) return null

  return (
    <>
      <div id="app">
        <BrowserRouter basename="/">
          <HeaderNavbar />
          <Navigation />
          <MessagesProvider>
            <div id="content" data-uk-height-viewport="expand: true">
              <div className="uk-container uk-container-expand">
                <div className="uk-section uk-section-default">
                  <div className="uk-container padded">
                    <Routes>{router.get()}</Routes>
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

export default Campaign