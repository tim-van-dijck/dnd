<template>
    <div id="pc-detail" v-if="character">
        <h1>{{ character.info.name }} <span v-if="character.info.private" title="This character is private">(<i class="fas fa-user-secret"></i>)</span></h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <ul class="uk-tab">
                    <li :class="{'uk-active': tab === 'character'}">
                        <a @click.prevent="tab = 'character'">Character</a>
                    </li>
                    <li :class="{'uk-active': tab === 'class'}">
                        <a @click.prevent="tab = 'class'">Class</a>
                    </li>
                    <li :class="{'uk-active': tab === 'proficiency'}">
                        <a @click.prevent="tab = 'proficiency'">Languages, Skills & Proficiencies</a>
                    </li>
                    <li :class="{'uk-active': tab === 'ideal'}">
                        <a @click.prevent="tab = 'ideal'">Personality</a>
                    </li>
                    <li :class="{'uk-active': tab === 'spells'}">
                        <a @click.prevent="tab = 'spells'">Spells</a>
                    </li>
                </ul>
                <pc-detail-character-tab v-show="tab === 'character'" :character="character" />
                <pc-detail-class-tab v-show="tab === 'class'" :classes="character.classes" />
                <pc-detail-proficiency-tab v-show="tab === 'proficiency'" :proficiencies="character.proficiencies" />
                <pc-detail-personality-tab v-show="tab === 'ideal'" :personality="character.personality" />
                <pc-detail-spells-tab v-show="tab === 'spells'" :spells="character.spells" />
            </div>
        </div>
    </div>
    <div v-else class="uk-section uk-section-default">
        <p class="uk-text-center">
            <i class="fas fa-sync fa-spin fa-2x"></i>
        </p>
    </div>
</template>

<script>
    import PcDetailCharacterTab from "./tabs/pc-detail-character-tab";
    import PcDetailProficiencyTab from "./tabs/pc-detail-proficiency-tab";
    import PcDetailPersonalityTab from "./tabs/pc-detail-personality-tab";
    import PcDetailSpellsTab from "./tabs/pc-detail-spells-tab";
    import PcDetailClassTab from "./tabs/pc-detail-class-tab";

    export default {
        name: "pc-detail",
        components: {
            PcDetailClassTab,
            PcDetailSpellsTab, PcDetailPersonalityTab, PcDetailCharacterTab,PcDetailProficiencyTab},
        props: ['id'],
        created() {
            this.$store.dispatch('Characters/find', this.id)
                .then((character) => {
                    this.character = character;
                })
        },
        data() {
            return {
                character: null,
                tab: 'character'
            }
        }
    }
</script>