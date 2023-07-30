import { FC } from "react";

const NotFound: FC = () => {
  return <div>
    <h1>Page Not Found</h1>
    <div className="uk-section uk-section-default">
      <div className="uk-container padded">
        <p>Whoops! You found a page that doesn't do anything. Best try somewhere else.</p>
      </div>
    </div>
  </div>
}

export default NotFound