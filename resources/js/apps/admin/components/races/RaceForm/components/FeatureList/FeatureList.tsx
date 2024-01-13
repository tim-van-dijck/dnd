import { FC } from "react";
import { FeatureListProps } from "./types";

const FeatureList: FC<FeatureListProps> = ({ items, remove }) => {
  if (items.length === 0) return null

  return <ul>
    {
      items.map((item, index) =>
        <li key={`selected-trait-${item.id}`} className="uk-flex uk-flex-between">
          <span title={item.description}>{item.name}{item.optional ? <>&nbsp;<em>(optional)</em></> : null}</span>
          <button className="uk-button uk-button-link uk-text-danger" onClick={(e) => {
            e.preventDefault()
            remove(item.id, index)
          }}>
            <i className="fas fa-trash"></i>
          </button>
        </li>
      )
    }
  </ul>
}

export default FeatureList