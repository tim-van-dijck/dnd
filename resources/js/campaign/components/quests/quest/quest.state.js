import { reactive } from 'vue'

export const useQuestDetailState = (store) => {
    return reactive({
        quest: { objectives: [] },
        setQuest(quest) {
            this.quest = quest
        },
        complete(objective) {
            return store.toggleObjective(this.quest.id, objective.id, objective.status == 1 ? 0 : 1)
                .then((response) => {
                    objective.status = response.data.status
                })
        },
        fail(objective) {
            return store.toggleObjective(this.quest.id, objective.id, objective.status < 2 ? 2 : 0)
                .then((response) => {
                    objective.status = response.data.status
                })
        }
    })
}