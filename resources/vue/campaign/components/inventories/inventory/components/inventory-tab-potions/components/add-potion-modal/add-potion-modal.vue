<template>
    <div>
        <button class="uk-button uk-button-primary" type="button" uk-toggle="target: #add-potion-modal">
            <i class="fas fa-plus"></i> Add potion
        </button>
        <div id="add-potion-modal" uk-modal @beforehide="state.reset">
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title">Add a potion</h2>
                <form class="uk-margin" @submit.prevent="state.addPotion">
                    <label class="uk-form-label" for="potion-selection">Select</label>
                    <div class="uk-flex uk-flex-between">
                        <div class="uk-form-controls">
                            <select class="uk-select uk-width-auto"
                                    :class="{'uk-form-danger': state.errors.hasOwnProperty('id')}"
                                    id="potion-selection" v-model="state.potionId">
                                <option :value="0">- Choose a potion -</option>
                                <optgroup v-for="(options, type) in ui.potionOptions.value" :label="type">
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
                <template v-if="selectedPotion !== null">
                    <hr>
                    <div class="uk-margin">
                        <h3>{{ selectedPotion.name }}</h3>
                        <p>{{ selectedPotion.description }}</p>
                    </div>
                </template>
                <button class="uk-modal-close-default uk-close-large" type="button" uk-close></button>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted } from 'vue'
import { useInventoryStore } from '../../../../../../../stores/inventory'
import { useAddPotionModalState } from './add-potion-modal.state'

export default {
    props: ['inventoryId'],
    name: 'add-potion-modal',
    setup(props) {
        const store = useInventoryStore()
        const { potionOptions, selectedPotion, state } = useAddPotionModalState(store, props.inventoryId)

        onMounted(() => store.loadItems('potions'))

        return {
            selectedPotion,
            state,
            ui: {
                potionOptions
            }
        }
    }
}
</script>