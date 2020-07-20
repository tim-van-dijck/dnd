<template>
    <div>
        <h1>{{ title }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="character" id="character-form" class="uk-form-stacked">
                    <ul class="uk-tab">
                        <li :class="{'uk-active': tab == 'info'}">
                            <a @click.prevent="tab = 'info'">Info</a>
                        </li>
                        <li :class="{'uk-active': tab == 'race'}">
                            <a @click.prevent="tab = 'race'">Race</a>
                        </li>
                    </ul>
                    <pc-form-info-tab v-show="tab == 'info'" v-model="character.info" />

                    <p class="uk-margin">
                        <button class="uk-button uk-button-primary" @click.prevent="save">Save</button>
                        <router-link class="uk-button uk-button-danger" :to="{name: type == 'player' ? 'player-characters' : 'npcs'}">
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
    import {mapState} from 'vuex';
    import PcFormInfoTab from "./partial/pc-form-info-tab";

    export default {
        name: "character-form",
        props: ['id', 'type'],
        created() {
            this.character = {
                type: 'player',
                info: {}
            };
            if (this.id) {
                this.$store.dispatch('Characters/loadCharacter', {campaign_id: 1, id: this.id})
                    .then((character) => {
                        this.character = JSON.parse(JSON.stringify(character));
                    });
            }
        },
        data() {
            return {
                character: null,
                tab: 'info'
            }
        },
        methods: {
            save() {
                let data = {campaign_id: 1, character: this.character};
                if (this.id) {
                    data.id = this.id;
                    this.$store.dispatch('Characters/updateCharacter', data);
                } else {
                    this.$store.dispatch('Characters/storeCharacter', data);
                }
            }
        },
        computed: {
            ...mapState('Characters', ['characters', 'errors']),
            title() {
                if (this.id) {
                    return 'Edit ' + (this.character ? this.character.name : 'character');
                } else {
                    return 'Add character';
                }
            }
        },
        components: {
            PcFormInfoTab,
        }
    }
</script>