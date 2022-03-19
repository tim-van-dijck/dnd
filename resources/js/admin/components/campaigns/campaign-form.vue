<template>
    <div>
        <h1>{{ title }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="campaign" class="uk-form-stacked">
                    <div class="uk-margin">
                        <label for="name" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('name')}">Name*</label>
                        <input id="name" title="name" type="text" class="uk-input" :class="{'uk-form-danger': errors.hasOwnProperty('name')}"
                               v-model="campaign.name">
                    </div>
                    <div class="uk-margin">
                        <label for="description" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('description')}">Description</label>
                        <html-editor id="description" name="description" v-model="campaign.description" height="600"></html-editor>
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
import {mapState} from "vuex";

export default {
    name: "campaign-form",
    props: {
        id: {
            required: true
        }
    },
    components: {HtmlEditor},
    data() {
        return {
            campaign: null
        }
    },
    created() {
        if (this.id) {
            this.$store.dispatch('Campaigns/find', this.id)
                .then((campaign) => {
                    this.campaign = campaign;
                });
        }
    },
    computed: {
        ...mapState('Campaigns', ['errors']),
        title() {
            if (this.id) {
                return 'Edit ' + (this.campaign ? this.campaign.name : 'campaign');
            } else {
                return '';
            }
        }
    },
    methods: {
        save() {
            this.$store.dispatch('Campaigns/update', {id: this.id, campaign: this.campaign})
                .then(() => {
                    this.$router.push({name: 'campaigns'});
                })
                .catch((error) => {
                    this.$store.commit('Campaigns/SET_ERRORS', error.response.data.errors);
                    this.$store.dispatch('Messages/error', error.response.data.message, {root: true});
                });
        }
    }
}
</script>