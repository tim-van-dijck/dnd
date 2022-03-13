<template>
    <div>
        <button class="uk-button uk-button-primary" type="button" uk-toggle="target: #proficiency-select-modal">
            Select proficiency
        </button>
        <div id="proficiency-select-modal" uk-modal @beforehide="reset()">
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title">Select a proficiency</h2>
                <div class="uk-margin">
                    <select name="proficiency" id="proficiency" class="uk-select" v-model="proficiency.id">
                        <option :value="0">- Choose a proficiency -</option>
                        <optgroup v-for="(options, type) in proficiencyOptions" :label="type">
                            <option v-for="option in options" :value="option.id" :disabled="selected.includes(option.id)">{{ option.name }}</option>
                        </optgroup>
                    </select>
                </div>
                <div class="uk-margin">
                    <label for="proficiency-optional">
                        <input id="proficiency-optional" class="uk-checkbox" type="checkbox" v-model="proficiency.optional"> Optional
                    </label>
                </div>
                <div class="uk-margin">
                    <button class="uk-button uk-button-primary" type="button" @click.prevent="save">Select</button>
                </div>
                <button class=" uk-modal-close-default uk-close-large" type="button" uk-close></button>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from "vuex";
import UIKit from "uikit";

export default {
    name: "proficiency-select-modal",
    props: {
        selected: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            proficiency: {
                id: 0,
                optional: false
            }
        }
    },
    computed: {
        ...mapState('Races', ['proficiencies']),
        proficiencyOptions() {
            const proficiencies = {};
            for (const proficiency of this.proficiencies || []) {
                if (!proficiencies.hasOwnProperty(proficiency.type)) {
                    proficiencies[proficiency.type] = []
                }
                proficiencies[proficiency.type].push(proficiency)
            }
            return proficiencies;
        }
    },
    created() {
        this.$store.dispatch('Races/loadProficiencies')
    },
    methods: {
        save() {
            if (this.proficiency.id > 0) {
                this.$emit('input', this.proficiency)
                this.close()
            }
        },
        reset() {
            this.$set(this, 'proficiency', {id: 0, optional: false})
        },
        close() {
            UIKit.modal('#proficiency-select-modal').hide()
        }
    }
}
</script>