<template>
    <div>
        <h1>{{ ui.title.value }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="state.user" class="uk-form-stacked">
                    <div class="uk-margin">
                        <label for="name" class="uk-form-label"
                               :class="{'uk-text-danger': state.errors.hasOwnProperty('name')}">Name*</label>
                        <input id="name" title="name" type="text" class="uk-input"
                               :class="{'uk-form-danger': state.errors.hasOwnProperty('name')}"
                               v-model="state.user.name">
                    </div>
                    <div class="uk-margin">
                        <label for="email" class="uk-form-label"
                               :class="{'uk-text-danger': state.errors.hasOwnProperty('email')}">Email address*</label>
                        <input id="email" title="email" type="text" class="uk-input"
                               :class="{'uk-form-danger': state.errors.hasOwnProperty('email')}"
                               v-model="state.user.email">
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label"
                               :class="{'uk-text-danger': state.errors.hasOwnProperty('admin')}">
                            <input id="admin" class="uk-checkbox" type="checkbox"
                                   v-model="state.user.admin"> Administrator
                        </label>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label"
                               :class="{'uk-text-danger': state.errors.hasOwnProperty('active')}">
                            <input id="active" class="uk-checkbox" type="checkbox"
                                   v-model="state.user.active"> Active
                        </label>
                    </div>
                    <p class="uk-margin">
                        <button class="uk-button uk-button-primary uk-margin-right" @click.prevent="state.save">Save
                        </button>
                        <router-link class="uk-button uk-button-danger" :to="{name: 'users'}">
                            Cancel
                        </router-link>
                    </p>
                </form>
                <p v-else class="uk-text-center">
                    <i class="fas fa-2x fa-sync fa-spin"></i>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { useUserStore } from '@admin/stores/users'
import { useMessageStore } from '@stores/messages'
import { computed, onMounted } from 'vue'
import { useUserFormState } from './user-form.state'

export default {
    name: 'user-form',
    setup(props) {
        const store = useUserStore()
        const messages = useMessageStore()
        const { state } = useUserFormState(store, messages)
        const title = computed(() => props.id ? `Edit ${state.user ? state.user.name : 'user'}` : 'Add user')

        onMounted(() => {
            if (props.id) {
                store.find(props.id)
                    .then((userFromState) => state.setUser(userFromState))
            } else {
                state.setUser({ name: '', email: '' })
            }
        })


        return {
            state,
            ui: {
                title
            }
        }
    },
    props: {
        id: {
            required: false
        }
    }
}
</script>