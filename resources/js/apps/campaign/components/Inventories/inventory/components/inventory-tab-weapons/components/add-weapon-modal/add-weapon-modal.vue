<template>
    <div>
        <button class="uk-button uk-button-primary" type="button" uk-toggle="target: #add-weapon-modal">
            <i class="fas fa-plus"></i> Add weapon
        </button>
        <div id="add-weapon-modal" uk-modal @beforehide="state.reset">
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title">Add a weapon</h2>
                <form class="uk-margin" @submit.prevent="state.addWeapon">
                    <label class="uk-form-label" for="weapon-selection">Select</label>
                    <div class="uk-flex uk-flex-between">
                        <div class="uk-form-controls">
                            <select class="uk-select uk-width-auto"
                                    :class="{'uk-form-danger': state.errors.hasOwnProperty('id')}"
                                    id="weapon-selection" v-model="state.weaponId">
                                <option :value="0">- Choose a weapon -</option>
                                <optgroup v-for="(options, type) in ui.weaponOptions.value" :label="type">
                                    <option v-for="option in options" :value="option.id">{{ option.name }}</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="uk-form-controls">
                            <input class="uk-input uk-width-small"
                                   :class="{'uk-form-danger': state.errors.hasOwnProperty('quantity')}"
                                   id="quantity-selection" type="number" v-model="state.quantity">
                        </div>
                        <button type="submit" class="uk-button uk-button-primary">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>
                </form>
                <template v-if="selectedWeapon !== null">
                    <hr>
                    <div class="uk-margin">
                        <h3>{{ selectedWeapon.name }}</h3>
                        <p>
                            <span class="uk-label uk-margin-small-right"
                                  :class="{'uk-label-danger': selectedWeapon.properties.damage > 0, 'uk-label-warning': (selectedWeapon.properties.damage || 0) == 0}">
                                <template v-if="selectedWeapon.properties.damage > 0">
                                    Damage:
                                    {{ selectedWeapon.properties.damage }}{{
                                        selectedWeapon.properties.damage_dice
                                    }} {{ selectedWeapon.properties.damage_type }}
                                </template>
                                <template v-else>No Damage</template>
                            </span>
                            <span v-for="property in ui.formattedProperties.value"
                                  class="uk-label uk-margin-small-right">
                                {{ property }}
                            </span>
                        </p>
                        <p v-if="selectedWeapon.properties.special">{{ selectedWeapon.properties.special }}</p>
                    </div>
                </template>
                <button class="uk-modal-close-default uk-close-large" type="button" uk-close></button>
            </div>
        </div>
    </div>
</template>

<script>
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { onMounted } from 'vue'
import { useInventoryStore } from '../../../../../../../stores/inventory'
import { useAddWeaponModalState } from './add-weapon-modal.state'

export default {
    props: ['inventoryId'],
    name: 'add-weapon-modal',
    setup(props) {
        const store = useInventoryStore()
        const { items } = storeToRefs(store)
        const { formattedProperties, selectedWeapon, state, weaponOptions } = useAddWeaponModalState(
            props.inventoryId,
            store
        )

        onMounted(() => store.loadItems('weapons'))

        return {
            items,
            state,
            ui: {
                formattedProperties,
                weaponOptions
            },
            selectedWeapon
        }
    }
}
</script>