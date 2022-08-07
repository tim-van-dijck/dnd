export const Messages = {
    namespaced: true,
    state: () => (
        {
            messages: []
        }
    ),
    actions: {
        addMessage({ state }, data) {
            const id = new Date().getTime()
            state.messages.unshift({ type: data.type, message: data.message, id })
            setTimeout(function () {
                let index = state.messages.findIndex((message) => {
                    return message.id = id
                })
                if (index != null) {
                    state.messages.splice(index, 1)
                }
            }, 5000)
        },
        success({ dispatch }, message) {
            return dispatch('addMessage', { message: message, type: 'success' })
        },
        error({ dispatch }, message) {
            return dispatch('addMessage', { message: message, type: 'danger' })
        }
    }
}