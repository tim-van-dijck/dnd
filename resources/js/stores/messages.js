import { defineStore } from 'pinia/dist/pinia.esm-browser'

export const useMessageStore = defineStore('Messages', {
    state: () => (
        {
            messages: []
        }
    ),
    actions: {
        addMessage(data) {
            const id = new Date().getTime()
            this.messages.unshift({ type: data.type, message: data.message, id })
            setTimeout(() => {
                const index = this.messages.findIndex((message) => {
                    return message.id = id
                })
                if (index != null) {
                    this.messages.splice(index, 1)
                }
            }, 5000)
        },
        success(message) {
            return this.addMessage({ message, type: 'success' })
        },
        error(message) {
            return this.addMessage({ message, type: 'danger' })
        }
    }
})