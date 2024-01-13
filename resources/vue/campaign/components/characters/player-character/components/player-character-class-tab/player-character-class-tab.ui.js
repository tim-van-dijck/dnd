import { reactive } from 'vue'

export const useActive = () => {
    return reactive({
        active: {
            features: {}
        },
        setActive(type, classId, object) {
            if (this.active.hasOwnProperty(type)) {
                this.active[type][classId] = object
            }
        }
    })
}