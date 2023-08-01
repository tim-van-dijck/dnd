import { useParams } from "react-router";
import { Link } from "react-router-dom";
import Private from "../../common/layout/Private";
import QuestObjective from "./components/QuestObjective";
import { useQuestDetailState } from "./QuestDetail.state";

const QuestDetail = () => {
  const { id } = useParams()
  const { quest, objectives, optional } = useQuestDetailState(parseInt(id!))

  return (
    <div>
      <h1>
        {quest ? quest.title : ''}
        {quest?.private ? <Private entity="quest" /> : null}
      </h1>

      <div className="uk-section uk-section-default">
        {
          quest ?
            <div id="quest" data-uk-grid>
              <div className="uk-width-1-2">
                <h2>Description</h2>
                <div className="uk-margin">
                  {
                    quest.description ?
                      <div dangerouslySetInnerHTML={{ __html: quest.description }} /> :
                      'No description'
                  }
                </div>
              </div>
              <div className="uk-width-1-2">
                <h2>Objectives</h2>
                {objectives.map((objective) => <QuestObjective key={objective.id}
                                                               questId={quest.id}
                                                               objective={objective} />)}
                {optional.length > 0 ?
                  <>
                    <h2>Optional</h2>
                    {optional.map((objective) => <QuestObjective key={objective.id}
                                                                 questId={quest.id}
                                                                 objective={objective} />)}
                  </> : null
                }
              </div>
              <br /><br />
            </div>
            : <p className="uk-text-center">
              <i className="fas fa-2x fa-sync fa-spin"></i>
            </p>
        }
      </div>
      <p className="uk-margin">
        <Link className="uk-button uk-button-text" to="/quests">
          <i className="fa fa-chevron-left fa-fw"></i> Back to quests
        </Link>
      </p>
    </div>
  )
}

export default QuestDetail