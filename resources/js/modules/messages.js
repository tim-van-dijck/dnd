export const Messages = {
    namespaced: true,
    state: {
        messages: []
    },
    actions: {
        addMessage({commit}, data) {
            commit('ADD_MESSAGE', data);
        },
        success({commit}, message) {
            commit('ADD_MESSAGE', {message: message, type: 'success'})
        },
        error({commit}, message) {
            commit('ADD_MESSAGE', {message: message, type: 'danger'});
        }
    },
    mutations: {
        ADD_MESSAGE(state, data) {
            let id = new Date().getTime();
            state.messages.unshift({type: data.type, message: data.message, id});
            setTimeout(function () {
                let index = state.messages.findIndex((message) => {
                    return message.id = id;
                });
                if (index != null) {
                    state.messages.splice(index,1);
                }
            }, 5000)
        }
    }
};