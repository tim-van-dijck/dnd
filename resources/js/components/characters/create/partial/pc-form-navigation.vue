<template>
    <div id="pc-form-nav">
        <div class="uk-button-group uk-width-expand uk-flex-between uk-visible@m">
            <button class="uk-button"
                    :class="{'uk-button-primary': tab === 'details', 'uk-button-default': tab !== 'uk-button-default'}"
                    @click.prevent="emit('details')">
                Details
                <i v-if="Object.keys(errors).find(item => item.includes('info'))"
                   class="fas fa-exclamation-triangle"
                   :class="{'uk-text-danger': tab !== 'details'}"></i>
            </button>
            <button class="uk-button"
                    :class="{'uk-button-primary': tab === 'class', 'uk-button-default': tab !== 'class' && enabledTabs.includes('class'), 'uk-button-muted': !enabledTabs.includes('class')}"
                    @click.prevent="emit('class')">
                Class
                <i v-if="Object.keys(errors).find(item => item.includes('classes'))"
                   class="fas fa-exclamation-triangle"
                   :class="{'uk-text-danger': tab !== 'class'}"></i>
            </button>
            <button class="uk-button"
                    :class="{'uk-button-primary': tab === 'background', 'uk-button-default': tab !== 'background' && enabledTabs.includes('background'), 'disabled': !enabledTabs.includes('background')}"
                    @click.prevent="emit('background')">
                Background
            </button>
            <button class="uk-button"
                    :class="{'uk-button-primary': tab === 'proficiency', 'uk-button-default': tab !== 'proficiency' && enabledTabs.includes('proficiency'), 'uk-button-muted': !enabledTabs.includes('proficiency')}"
                    @click.prevent="emit('proficiency')">
                Languages, Skills & Proficiencies
                <i v-if="Object.keys(errors).find(item => item.includes('proficiencies'))"
                   class="fas fa-exclamation-triangle uk-text-danger"
                   :class="{'uk-text-danger': tab !== 'proficiencies'}"></i>
            </button>
            <button class="uk-button"
                    :class="{'uk-button-primary': tab === 'ability', 'uk-button-default': tab !== 'ability' && enabledTabs.includes('ability'), 'uk-button-muted': !enabledTabs.includes('ability')}"
                    @click.prevent="emit('ability')">
                Abilities
                <i v-if="Object.keys(errors).find(item => item.includes('ability_scores'))"
                   class="fas fa-exclamation-triangle uk-text-danger"
                   :class="{'uk-text-danger': tab !== 'ability'}"></i>
            </button>
            <button class="uk-button"
                    :class="{'uk-button-primary': tab === 'personality', 'uk-button-default': tab !== 'personality' && enabledTabs.includes('personality'), 'disabled': !enabledTabs.includes('personality')}"
                    @click.prevent="emit('personality')">
                Personality
                <i v-if="Object.keys(errors).find(item => item.includes('personality'))"
                   class="fas fa-exclamation-triangle uk-text-danger"
                   :class="{'uk-text-danger': tab !== 'personality'}"></i>
            </button>
            <button class="uk-button"
                    v-if="spellcaster" :class="{'uk-button-primary': tab === 'spells', 'uk-button-default': tab !== 'spells' && enabledTabs.includes('spells'), 'disabled': !enabledTabs.includes('spells')}"
                    @click.prevent="emit('spells')">
                Spells
                <i v-if="Object.keys(errors).find(item => item.includes('spells'))"
                   class="fas fa-exclamation-triangle uk-text-danger"
                   :class="{'uk-text-danger': tab !== 'spells'}"></i>
            </button>
        </div>

        <nav class="uk-navbar-container uk-hidden@m" uk-navbar="dropbar: true; dropbar-mode: push">
            <div class="uk-navbar-center">
                <ul class="uk-navbar-nav">
                    <li>
                        <button type="button" class="uk-button uk-button-primary uk-width-expand uk-text-center">
                            {{ activeTab }}
                            <i v-if="Object.keys(errors).length > 0" class="fas fa-exclamation-triangle uk-text-danger"></i>
                        </button>
                        <div class="uk-navbar-dropdown uk-navbar-dropdown-width-2">
                            <div class="uk-navbar-dropdown-grid uk-child-width" uk-grid>
                                <div class="uk-width-1-1">
                                    <ul class="uk-width uk-nav uk-navbar-dropdown-nav">
                                        <li>
                                            <button class="uk-button"
                                                :class="{'uk-button-primary': tab === 'details'}"
                                                @click.prevent="emit('details')">
                                            Details <i v-if="Object.keys(errors).find(item => item.includes('info'))" class="fas fa-exclamation-triangle uk-text-danger"></i>
                                            </button>
                                        </li>
                                        <li>
                                            <button class="uk-button"
                                                :class="{'uk-button-primary': tab === 'class', 'uk-button-default': tab !== 'class' && enabledTabs.includes('class'), 'disabled': !enabledTabs.includes('class')}"
                                                @click.prevent="emit('class')">
                                            Class <i v-if="Object.keys(errors).find(item => item.includes('classes'))" class="fas fa-exclamation-triangle uk-text-danger"></i>
                                            </button>
                                        </li>
                                        <li>
                                            <button class="uk-button"
                                                :class="{'uk-button-primary': tab === 'background', 'uk-button-default': tab !== 'background' && enabledTabs.includes('background'), 'disabled': !enabledTabs.includes('background')}"
                                                @click.prevent="emit('background')">
                                            Background
                                            </button>
                                        </li>
                                        <li>
                                            <button class="uk-button"
                                                :class="{'uk-button-primary': tab === 'proficiency', 'uk-button-default': tab !== 'proficiency' && enabledTabs.includes('proficiency'), 'disabled': !enabledTabs.includes('proficiency')}"
                                                @click.prevent="emit('proficiency')">
                                            Languages, Skills & Proficiencies <i v-if="Object.keys(errors).find(item => item.includes('proficiencies'))" class="fas fa-exclamation-triangle uk-text-danger"></i>
                                            </button>
                                        </li>
                                        <li>
                                            <button class="uk-button"
                                                :class="{'uk-button-primary': tab === 'ability', 'uk-button-default': tab !== 'ability' && enabledTabs.includes('ability'), 'disabled': !enabledTabs.includes('ability')}"
                                                @click.prevent="emit('ability')">
                                            Abilities <i v-if="Object.keys(errors).find(item => item.includes('ability_scores'))" class="fas fa-exclamation-triangle uk-text-danger"></i>
                                            </button>
                                        </li>
                                        <li>
                                            <button class="uk-button"
                                                :class="{'uk-button-primary': tab === 'personality', 'uk-button-default': tab !== 'personality' && enabledTabs.includes('personality'), 'disabled': !enabledTabs.includes('personality')}"
                                                @click.prevent="emit('personality')">
                                            Personality <i v-if="Object.keys(errors).find(item => item.includes('personality'))" class="fas fa-exclamation-triangle uk-text-danger"></i>
                                            </button>
                                        </li>
                                        <li>
                                            <button class="uk-button"
                                                v-if="spellcaster" :class="{'uk-button-primary': tab === 'spells', 'uk-button-default': tab !== 'spells' && enabledTabs.includes('spells'), 'disabled': !enabledTabs.includes('spells')}"
                                                @click.prevent="emit('spells')">
                                            Spells <i v-if="Object.keys(errors).find(item => item.includes('spells'))" class="fas fa-exclamation-triangle uk-text-danger"></i>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</template>

<script>
export default {
    name: "pc-form-navigation",
    props: ['character', 'spellcaster', 'tab', 'errors'],
    methods: {
        emit(tab) {
            if (this.enabledTabs.includes(tab)) {
                this.$emit('navigate', tab)
            }
        }
    },
    computed: {
        enabledTabs() {
            let enabled = ['details'];
            if (this.character.info.hasOwnProperty('race_id') && this.character.info.race_id != null) {
                enabled.push('class');
                for (let chosenClass of this.character.classes) {
                    if (chosenClass.class_id != null && chosenClass.subclass_id != null) {
                        enabled = enabled.concat(['ability', 'proficiency', 'personality', 'background']);
                        if (this.spellcaster) {
                            enabled.push('spells')
                        }
                    }
                }
            }
            return enabled.filter((value, index, self) => self.indexOf(value) === index);
        },
        activeTab() {
            switch (this.tab) {
                case 'ability':
                    return 'Abilities';
                case 'proficiency':
                    return 'Languages, Skills & Proficiencies';
                default:
                    return _.capitalize(this.tab);
            }
        }
    }
}
</script>