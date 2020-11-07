<template>
    <div id="background-tab">
        <div v-if="backgrounds != null" class="uk-margin-bottom">
            <label for="background-select" class="uk-label"></label>
            <select name="background-select" id="background-select" class="uk-select" v-model="selection">
                <option :value="0">None</option>
                <option v-for="background in backgrounds" :value="background.id">{{ background.name }}</option>
            </select>
        </div>
        <p v-else class="uk-text-center">
            <i class="fas fa-sync fa-spin fa-2x"></i>
        </p>
        <div v-if="selection > 0" id="active-background">
            <h2>{{ selected.name }}</h2>
            <ul>
                <li><b>Skill Proficiencies:</b> {{ selected.skills }}</li>
                <li v-if="selected.tools.length > 0"><b>Tool Proficiencies:</b> {{ selected.tools }}</li>
                <li v-if="selected.language_choices > 0"><b>Languages:</b> {{ `${selected.language_choices} of choice` }}</li>
            </ul>
            <div v-for="feature in selected.features">
                <h4>Feature: {{ feature.name }}</h4>
                <div v-html="feature.description"></div>
            </div>
        </div>
        <div class="uk-alert-primary" v-else-if="selection == ''" uk-alert>
            <p>You've chosen not to use a background</p>
        </div>

        <p class="uk-margin">
            <router-link class="uk-button uk-button-danger" :to="{name: 'player-characters'}">
                Cancel
            </router-link>
            <button class="uk-button uk-button-primary uk-align-right" @click.prevent="$emit('next')">Next <i class="fas fa-chevron-right"></i></button>
        </p>
    </div>
</template>

<script>
import {mapState} from 'vuex';

export default {
    name: "pc-form-background-tab",
    props: ['value'],
    created() {
        this.$store.dispatch('Characters/loadBackgrounds');
        this.selection = this.value || 0;
    },
    data() {
        return {
            selection: 0,
        }
    },
    computed: {
        ...mapState('Characters', ['backgrounds']),
        selected() {
            let bg = this.backgrounds.find((background) => background.id == this.selection);
            if (bg == null) {
                return null;
            }
            let selected = JSON.parse(JSON.stringify(bg));
            selected.skills = selected.skills.map((item) => item.name).join(', ');

            let tools = selected.tools.map((item) => item.name);
            if (selected.tool_choices > 0) {
                tools.push(`${selected.tool_choices} type(s) of tools`);
            }
            if (selected.instrument_choices > 0) {
                tools.push(`${selected.instrument_choices} type(s) of instruments`);
            }
            selected.tools = tools.join(', ');
            return selected;
        }
    },
    watch: {
        selection() {
            this.$emit('input', this.selection);
        }
    }
}
</script>