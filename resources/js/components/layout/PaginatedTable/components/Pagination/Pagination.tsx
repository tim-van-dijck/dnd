import classNames from "classnames";
import { usePagination } from "./Pagination.state";
import { FC } from "react";
import { IProps } from "./types";

const Pagination: FC<IProps> = ({ records, repository }) => {
  const { pages, go } = usePagination(records, repository.page)

  return pages.length > 1 ?
    <ul className="uk-pagination uk-margin-remove">
      {records.meta.current_page > 1 ?
        <li key="previous">
          <a href="#" onClick={repository.previous}>
            <span data-uk-pagination-previous></span>
          </a>
        </li> : null
      }
      {
        pages.map((page) =>
          <li key={`page_${page.number}`} className={classNames({ 'uk-active': page.active })}>
            {
              typeof page.number === 'string' ? <span>{page.number}</span> :
                page.active ?
                  <span>{page.number}</span> :
                  <a href="#" onClick={() => go(page.number as number)}>{page.number}</a>
            }

          </li>
        )
      }
      <li key="next">
        {
          records.meta.current_page != records.meta.last_page ?
            <a href="#" onClick={repository.next}><span data-uk-pagination-next></span></a>
            : null
        }
      </li>
    </ul> : null
}

export default Pagination