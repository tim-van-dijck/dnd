import { Link } from "react-router-dom";
import { ui } from "./SpellOverview.ui";
import { useSpellOverviewState } from "./SpellOverview.state";
import PaginatedTable from "../../../../../components/layout/PaginatedTable";

const SpellOverview = () => {
  const { destroy, SpellRepository } = useSpellOverviewState()

  return <div id="spells">
    <h1>Spells</h1>
    <div className="uk-section uk-section-default">
      <Link className="uk-button uk-button-primary" to="/spells/create">
        <i className="fas fa-plus"></i> Add spell
      </Link>
      {(
        SpellRepository.spells?.data?.length || 0
      ) > 0 ?
        <PaginatedTable
          actions={ui.actions}
          columns={ui.columns}
          records={SpellRepository.spells}
          repository={{
            previous: SpellRepository.previous,
            page: SpellRepository.page,
            next: SpellRepository.next,
            load: SpellRepository.load
          }}
          onAction={(type: string, row) => {
            if (type === 'destroy') destroy(row.id)
          }} />
        // @view="router.push({name: 'spell', params: {id: $event.id}})"
        : <p className="uk-text-center">
          {SpellRepository.spells === null ? <i className="fas fa-sync fa-spin fa-2x"></i>
            : <span>No spells found</span>}
        </p>}
    </div>
  </div>
}

export default SpellOverview