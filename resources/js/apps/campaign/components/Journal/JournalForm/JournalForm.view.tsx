import { FC } from "react";
import FormButtons from "../../../../../components/layout/form/components/FormButtons";
import InputBoolean from "../../../../../components/layout/form/components/InputBoolean";
import InputRichText from "../../../../../components/layout/form/components/InputRichText";
import InputText from "../../../../../components/layout/form/components/InputText";
import { JournalFormViewProps } from "./types";

const JournalFormView: FC<JournalFormViewProps> = ({ state, ui }) => {
  return <div>
    <h1>{ui.title}</h1>
    <div className="uk-section uk-section-default">
      {state.input ?
        <form onSubmit={state.save}>
          <InputText id="name"
                     name="name"
                     label="Name"
                     initialValue={state.input?.title}
                     errors={state.errors?.name}
                     onChange={(value) => state.update('name', value)}
                     required />
          <InputRichText id="content"
                         name="content"
                         label="Content"
                         initialValue={state.input?.content}
                         errors={state.errors?.content}
                         onChange={(value) => state.update('content', value)}
                         required />
          <InputBoolean id="private"
                        name="private"
                        label="Private"
                        initialValue={state.input?.private}
                        errors={state.errors?.private}
                        onChange={(value) => state.update('private', value)}
                        required />
          <FormButtons cancel={ui.redirect} />
        </form> : <p className="uk-text-center"><i className="fas fa-2x fa-sync fa-spin"></i></p>
      }
    </div>
  </div>
}

export default JournalFormView