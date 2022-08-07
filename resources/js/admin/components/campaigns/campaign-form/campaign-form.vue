<template>
    <div>
        <h1>{{ ui.title.value }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="state.campaign" class="uk-form-stacked">
                    <div class="uk-margin">
                        <label for="name" class="uk-form-label"
                               :class="{'uk-text-danger': state.errors.hasOwnProperty('name')}">Name*</label>
                        <input id="name" title="name" type="text" class="uk-input"
                               :class="{'uk-form-danger': state.errors.hasOwnProperty('name')}"
                               v-model="state.campaign.name">
                    </div>
                    <div class="uk-margin">
                        <label for="description" class="uk-form-label"
                               :class="{'uk-text-danger': state.errors.hasOwnProperty('description')}">Description</label>
                        <html-editor id="description" name="description" v-model="state.campaign.description"
                                     height="600"></html-editor>
                    </div>
                    <p class="uk-margin">
                        <button class="uk-button uk-button-primary uk-margin-right" @click.prevent="state.save">Save
                        </button>
                        <router-link class="uk-button uk-button-danger" :to="{name: 'campaigns'}">
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
import HtmlEditor from '@components/partial/html-editor'
import { computed, onMounted } from 'vue'
import { useMessageStore } from '../../../../stores/messages'
import { useCampaignStore } from '../../../stores/campaigns'
import { useCampaignFormState } from './campaign-form.state'

export default {
    name: 'campaign-form',
    setup(props) {
        const store = useCampaignStore()
        const messages = useMessageStore()
        const { state } = useCampaignFormState(store, messages)
        const title = computed(() => props.id
            ? `Edit ${state.campaign ? state.campaign.name : 'campaign'}`
            : 'Add campaign')

        onMounted(() => {
            if (props.id) {
                store.find(props.id)
                    .then((campaignFromState) => state.setCampaign(campaignFromState))
            } else {
                state.setCampaign({ name: '', description: '' })
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
            required: true
        }
    },
    components: { HtmlEditor }
}
</script>