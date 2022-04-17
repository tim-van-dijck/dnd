<template>
    <div>
        <button class="uk-button uk-button-primary" type="button" uk-toggle="target: #ability-select-modal">
            Select ability bonus
        </button>
        <div id="ability-select-modal" uk-modal @beforehide="state.reset()">
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title">Select an ability bonus</h2>
                <div class="uk-margin">
                    <select name="ability" id="ability" class="uk-select" v-model="state.ability.id">
                        <option value="">- Choose a ability -</option>
                        <option v-for="abilityOption in abilities" :value="abilityOption.id"
                                :disabled="selected.includes(abilityOption.id)">{{ abilityOption.name }}
                        </option>
                    </select>
                </div>
                <div class="uk-margin">
                    <label for="bonus">Bonus</label>
                    <input id="bonus" class="uk-input" type="number" min="1" v-model="state.ability.bonus">
                </div>
                <div class="uk-margin">
                    <label for="ability-optional">
                        <input id="ability-optional" class="uk-checkbox" type="checkbox"
                               v-model="state.ability.optional">
                        Optional
                    </label>
                </div>
                <div class="uk-margin">
                    <button class="uk-button uk-button-primary" type="button" @click.prevent="state.save">Select
                    </button>
                </div>
                <button class=" uk-modal-close-default uk-close-large" type="button" uk-close></button>
            </div>
        </div>
    </div>
</template>

<script>
import UIKit from "uikit";
import { useState } from "./ability-bonus-modal.state";

export default {
    name: "ability-bonus-modal",
    props: {
        selected: {
            type: Array,
            default: () => []
        }
    },
    setup(props, ctx) {
        const ui = {
            close: () => UIKit.modal('#ability-select-modal').hide()
        }
        const state = useState(ctx, ui)
        return {
            selected: props.selected,
            state
        }
    }
}
</script>