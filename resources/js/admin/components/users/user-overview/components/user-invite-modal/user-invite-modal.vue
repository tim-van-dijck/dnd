<template>
    <button class="uk-button uk-button-primary" @click.prevent="state.open">
        <i class="fas fa-plus"></i> Invite user
    </button>
    <div id="user-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body">
            <form>
                <div class="uk-margin">
                    <label for="email" class="uk-form-label">Email address</label>
                    <input id="email" name="email" type="text"
                           :class="{'uk-input': true, 'uk-form-danger': state.errors && state.errors.hasOwnProperty('email')}"
                           v-model="state.user.email">
                    <span v-if="state.errors && state.errors.hasOwnProperty('email')"
                          class="uk-text-danger uk-text-small uk-align-right">
                            {{ state.errors.email[0] }}
                        </span>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label"
                           :class="{'uk-text-danger': state.errors.hasOwnProperty('admin')}">
                        <input id="admin" class="uk-checkbox" type="checkbox"
                               v-model="state.user.admin"> Administrator
                    </label>
                </div>
                <p class="uk-margin">
                    <button class="uk-button uk-button-primary uk-margin-right" @click.prevent="submit">
                        Invite
                    </button>
                    <button class="uk-button uk-button-danger" @click.prevent="state.cancel">
                        Cancel
                    </button>
                </p>
            </form>
        </div>
    </div>
</template>

<script>
import { useUserStore } from '../../../../../stores/users'
import { useInviteModalState } from './user-invite-modal.state'

export default {
    name: 'user-invite-modal',
    setup(props, ctx) {
        const store = useUserStore()
        const state = useInviteModalState(store)

        const submit = () => {
            state.save()
                .then((user) => {
                    ctx.emit('invite', user)
                })
        }
        return { state, submit }
    },
    emits: ['invite']
}
</script>