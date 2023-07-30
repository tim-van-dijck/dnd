import { useParams } from "react-router";
import { Link } from "react-router-dom";
import { useLocation } from "./LocationDetail.state";

const LocationDetail = () => {
  const { id: idAsString } = useParams()
  const id: number | undefined = typeof idAsString === 'string' ? parseInt(idAsString) : idAsString
  const { location } = useLocation(id!)

  return <div>
    <h1>
      <Link className="uk-link-text" to="/locations"><i className="fas fa-chevron-left" /></Link>
      {location ? location.name : ''}
      {location?.private ? <span title="This location is private">(<i className="fas fa-user-secret" />)</span> : null}
    </h1>
    <div className="uk-section uk-section-default">
      {
        location ?
          <div data-uk-grid>
            <div className="uk-width-1-2">
              <div className="uk-margin">
                <h3>Type</h3>
                <p>{location.type}</p>
              </div>
              <div className="uk-margin">
                <h3>Location</h3>
                {location.location?.name || 'N/A'}
              </div>
              {
                location.map ? <div className="uk-margin">
                  <h3>Map</h3>
                  <img className="preview-image"
                       v-if="location.map"
                       src={`/storage/${location.map}`}
                       alt="Uploaded map image" />
                </div> : null}
              <hr />
            </div>
            <div className="uk-width-1-2">
              <h3>Description</h3>
              <div dangerouslySetInnerHTML={{ __html: location.description }} />
            </div>
          </div> :
          <p className="uk-text-center"><i className="fas fa-2x fa-sync fa-spin" /></p>
      }
    </div>
  </div>
}

export default LocationDetail