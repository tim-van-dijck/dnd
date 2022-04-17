import {computed} from "vue";

const title = (id) => {
    return computed(() => id ? `Edit ${this.campaign ? this.campaign.name : 'campaign'}` : '')
}

export const ui = {
    title,

}