<template>
    <div id="user" v-if="state.user">
        <h1>
            <router-link class="uk-link-text" :to="{name: 'users'}"><i class="fas fa-chevron-left"/>
            </router-link>
            {{ state.user.name }} <span v-if="state.user.admin" title="Admin">(<i
            class="fas fa-user-shield"/>)</span>
        </h1>
        <div uk-grid>
            <div class="uk-width-1-1@s uk-width-1-3@l">
                <h2>Info</h2>
                <div class="uk-margin uk-flex uk-flex-between">
                    <b>Name</b>
                    <span>{{ state.user.name }}</span>
                </div>
                <div class="uk-margin uk-flex uk-flex-between">
                    <b>Email</b>
                    <span>{{ state.user.email }}</span>
                </div>
                <div class="uk-flex uk-flex-between">
                    <b>Status</b>
                    <span class="uk-label"
                          :class="{'uk-label-success': state.user.active, 'uk-label-danger': !state.user.active}">
                        {{ state.user.active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            <div class="uk-width-1-1@s uk-width-2-3@l">
                <h2>Campaigns</h2>
                <table v-if="state.campaigns">
                    <tbody>
                    <tr v-if="state.campaigns === null">
                        <td class="uk-text-center" colspan="2"><i class="fas fa-sync fa-spin"/></td>
                    </tr>
                    <tr v-else-if="(state.campaigns || []).length === 0">
                        <td class="uk-text-center uk-text-italic" colspan="2">This user is not part of any campaigns
                        </td>
                    </tr>
                    <tr v-else v-for="campaign in state.campaigns">
                        <td>{{ campaign.name }}</td>
                        <td>{{ }}</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
        <div class="uk-flex uk-margin-large-top">
            <router-link :to="{name: 'user-edit', params: {id: state.user.id}}"
                         class="uk-button uk-button-primary uk-margin-right">
                <i class="fas fa-edit"/> Edit
            </router-link>
            <button class="uk-button" @click.prevent="resetPassword"><i class="fas fa-lock"/> Reset password</button>
        </div>
    </div>
    <div v-else class="uk-section uk-section-default">
        <p class="uk-text-center">
            <i class="fas fa-sync fa-spin fa-2x"></i>
        </p>
    </div>
</template>

<script>
import { onMounted } from 'vue'
import { useModals } from '../../../modals'
import { useCampaignStore } from '../../../stores/campaigns'
import { useUserStore } from '../../../stores/users'
import { useUserDetailState } from './user-detail.state'

export default {
    name: 'user-detail',
    props: ['id'],
    setup(props) {
        const users = useUserStore()
        const modals = useModals()
        const campaigns = useCampaignStore()
        const { state } = useUserDetailState()

        onMounted(() => {
            users.find(props.id).then((user) => state.setUser(user))
            campaigns.loadForUser(props.id).then((campaigns) => state.setCampaigns(campaigns))
        })

        const resetPassword = () => {
            modals.confirm(
                `Are you sure you want to send a password reset link to ${state.user.email}?`,
                () => users.resetPassword(state.user.id)
            )
        }

        return { state, resetPassword }
    }
}
</script>