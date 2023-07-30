import { reactive } from 'vue'

export const useCharacterDetailTabs = () => {
    return reactive({
        tab: 'character',
        setTab(tab) {
            this.tab = tab
        }
    })
}