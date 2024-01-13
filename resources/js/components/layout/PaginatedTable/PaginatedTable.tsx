import { FC } from 'react'
import { Link } from 'react-router-dom'
import Pagination from './components/Pagination'
import { useSearch } from './PaginatedTable.state'
import { getHref, getTo, getValue } from './PaginatedTable.ui'
import { IProps } from './types'

const PaginatedTable: FC<IProps> = ({ actions, columns, repository, records, onAction }) => {
  const { query, setQuery, search } = useSearch(repository)

  return <>
    <div className="table-paginated">
      <table className="uk-table uk-table-divider">
        <thead>
        <tr>
          <th></th>
          {columns.map((column) => <th key={`header_${column.name}`}>{column.title}</th>)}
        </tr>
        </thead>
        <tbody>
        {records === null ?
          <tr key="empty">
            <td className="uk-text-center" colSpan={columns.length + 1}><i className="fas fa-sync fa-spin"></i></td>
          </tr> :
          records?.data?.map((row) => (
            <tr key={row.id}>
              <td key={`${row.id}_actions`} className="uk-width-small">
                <ul className="uk-iconnav">
                  {actions.filter(({ condition }) => condition == undefined || condition?.(row)).map((action) => (
                    <li key={`${row.id}_action_${action.name}`}>
                      {action.to ?
                        <Link to={getTo(action.to, row)} title={action.title}>
                          <i className={`fas fa-${action.icon}`} />
                        </Link> : action.href ?
                          <a href={getHref(action.href, row)}
                             target={action.newTab ? '_blank' : ''} title={action.title}>
                            <i className={`fas fa-${action.icon}`}></i>
                          </a> :
                          <a href="/" className={action.classes || ''} onClick={(e) => {
                            e.preventDefault()
                            onAction?.(action.name, row)
                          }}
                             title={action.title}>
                            <i className={`fas fa-${action.icon}`}></i>
                          </a>
                      }
                    </li>
                  ))}
                </ul>
              </td>
              {
                columns.map((column) => <td key={`${row.id}_${column.name}`}>
                    {column?.format?.(getValue(row, column.name), row) || getValue(row, column.name)}
                  </td>
                )
              }
            </tr>
          ))
        }
        </tbody>
      </table>
      {records === null ? null :
        <div className="uk-flex uk-flex-between uk-margin uk-margin-medium-top">
          <div><span>{records.meta.from} - {records.meta.to} of {records.meta.total}</span></div>
          <Pagination records={records} repository={repository} />
        </div>
      }
    </div>
  </>
}

export default PaginatedTable