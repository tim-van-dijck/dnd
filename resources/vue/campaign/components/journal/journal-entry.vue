<template>
    <div>
        <h1>{{ entry ? entry.title : '' }}</h1>
        <div class="uk-section uk-section-default">
            <div id="note" v-if="entry" class="uk-container padded">
                <div v-html="entry.content"></div>
                <p class="uk-margin-large-top">
                    <router-link class="uk-button uk-button-text" :to="{name: 'journal'}">
                        <i class="fa fa-chevron-left fa-fw"></i> Back to journal
                    </router-link>
                </p>
            </div>
            <p v-else class="uk-text-center">
                <i class="fas fa-2x fa-sync fa-spin"></i>
            </p>
        </div>
    </div>
</template>

<script>
export default {
    name: "journal-entry",
    props: ['id'],
    created() {
        this.$store.dispatch('Journal/find', this.id)
            .then((entry) => {
                this.entry = entry;
            });
    },
    data() {
        return {
            entry: null
        }
    }
}
</script>