<template>
    <div id="dashboard">
        <h1>{{ campaign.name }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded" uk-grid>
                <div class="uk-width-1-2" v-if="campaign.description && campaign.description.length > 0">
                    <h3>Welcome to {{ campaign.name }}!</h3>
                    <div class="uk-margin" v-html="campaign.description"></div>
                </div>
                <div :class="`uk-width-1-${campaign.description && campaign.description.length > 0 ? 2 : 1}`">
                    <h3>Recent activity</h3>
                    <ul v-if="logs && logs.length > 0" class="uk-list uk-list-divider">
                        <li v-for="log in logs">
                            <span v-html="log.message"></span>
                            <span class="uk-display-inline-block uk-align-right uk-text-italic">({{
                                    formatDate(log.created_at)
                                }})</span>
                        </li>
                    </ul>
                    <div v-else class="uk-alert uk-alert-primary">
                        Nothing to report, you lazy maggots!
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useStore } from 'vuex';
import { formatDate } from "../../libs/formatting/date";
import { onMounted } from "vue";

export default {
    name: "Dashboard",
    setup() {
        const store = useStore()
        onMounted(() => store.dispatch('loadLogs'))
        return {
            campaign: store.state.campaign,
            formatDate,
            logs: store.state.logs
        }
    }
}
</script>