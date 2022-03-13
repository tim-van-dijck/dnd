<template>
    <div>
        <button class="uk-button uk-button-primary" type="button" uk-toggle="target: #ability-select-modal">
            Select ability bonus
        </button>
        <div id="ability-select-modal" uk-modal @beforehide="reset()">
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title">Select an ability bonus</h2>
                <div class="uk-margin">
                    <select name="ability" id="ability" class="uk-select" v-model="ability.id">
                        <option value="">- Choose a ability -</option>
                        <option v-for="ability in abilities" :value="ability.id" :disabled="selected.includes(ability.id)">{{ ability.name }}</option>
                    </select>
                </div>
                <div class="uk-margin">
                    <label for="bonus">Bonus</label>
                    <input id="bonus" class="uk-input" type="number" min="1" v-model="ability.bonus">
                </div>
                <div class="uk-margin">
                    <label for="ability-optional">
                        <input id="ability-optional" class="uk-checkbox" type="checkbox" v-model="ability.optional"> Optional
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
import UIKit from "uikit";

export default {
    name: "ability-bonus-modal",
    props: {
        selected: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            ability: {
                id: '',
                bonus: 1,
                optional: false
            },
            abilities: [
                {id: 'STR', name: 'Strength'},
                {id: 'DEX', name: 'Dexterity'},
                {id: 'CON', name: 'Constitution'},
                {id: 'INT', name: 'Intelligence'},
                {id: 'WIS', name: 'Wisdom'},
                {id: 'CHA', name: 'Charisma'}
            ]
        }
    },
    methods: {
        save() {
            if (this.ability.id.length === 3) {
                this.$emit('input', {...this.ability})
                this.close()
            }
        },
        reset() {
            this.$set(this, 'ability', {id: '', bonus: 1, optional: false})
        },
        close() {
            UIKit.modal('#ability-select-modal').hide()
        }
    }
}
</script>