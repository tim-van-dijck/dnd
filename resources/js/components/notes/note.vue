<template>
    <div>
        <h1>{{ note ? note.name : '' }}</h1>
        <div class="uk-section uk-section-default">
            <div id="note" v-if="note" class="uk-container padded">
                <div v-html="note.content"></div>
                <p class="uk-margin">
                    <router-link class="uk-button uk-button-text" :to="{name: 'quests'}">
                        <i class="fa fa-chevron-left fa-fw"></i> Back to notes
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
        name: "Note",
        props: ['id'],
        created() {
            this.$store.dispatch('Notes/find', this.id)
                .then((note) => {
                    this.note = note;
                });
        },
        data() {
            return {
                note: null
            }
        }
    }
</script>