<template>
    <div>
        <h1>{{ ui.title }}</h1>
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
                        <button class="uk-button uk-button-primary" @click.prevent="save">Save</button>
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
import HtmlEditor from "@components/partial/html-editor";
import { useStore } from "vuex";
import { computed, onMounted } from "vue";
import { state } from "./campaign-form.state";

export default {
    name: "campaign-form",
    setup(props) {
        const store = useStore()

        onMounted(() => {
            if (props.id) {
                store.dispatch('Campaigns/find', props.id)
                    .then((campaignFromState) => state.setCampaign(campaignFromState));
            }

        })

        return {
            state,
            ui: {
                title: computed(() => props.id ? `Edit ${state.campaign ? state.campaign.name : 'campaign'}` : '')
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