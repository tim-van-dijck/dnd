<template>
    <div>
        <button class="uk-button uk-button-primary" type="button" uk-toggle="target: #add-armor-modal">
            <i class="fas fa-plus"></i> Add armor
        </button>
        <div id="add-armor-modal" uk-modal @beforehide="armorId = 0; quantity = 1">
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title">Add a piece of armor</h2>
                <form class="uk-margin" @submit.prevent="state.addArmor">
                    <label class="uk-form-label" for="armor-selection">Select</label>
                    <div class="uk-flex uk-flex-between">
                        <div class="uk-form-controls">
                            <select class="uk-select uk-width-auto"
                                    :class="{'uk-form-danger': state.errors.hasOwnProperty('id')}"
                                    id="armor-selection" v-model="state.armorId">
                                <option :value="0">- Choose a piece of armor -</option>
                                <optgroup v-for="(options, type) in ui.armorOptions.value" :label="type">
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
                <template v-if="selectedArmor !== null">
                    <hr>
                    <div class="uk-margin">
                        <h3>{{ selectedArmor.name }}
                            <template v-if="selectedArmor.type !== 'Shield'"> ({{ selectedArmor.type }})</template>
                        </h3>
                        <p>{{ selectedArmor.description }}</p>
                        <p>
                            <b>AC:</b>
                            {{ selectedArmor.name === 'Shield' ? '+2' : selectedArmor.properties.ac }}
                            <template v-if="selectedArmor.properties.add_dex">+ Dex modifier (max. 2)</template>
                        </p>
                        <p>
                            <span v-for="property in ui.formattedProperties.value"
                                  class="uk-label uk-margin-small-right"
                                  :title="property.title"
                                  :class="`uk-label-${property.type || 'default'}`">
                                {{ property.label }}
                            </span>
                        </p>
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
import { useAddArmorModalState } from './add-armor-modal.state'

export default {
    props: ['inventoryId'],
    name: 'add-armor-modal',
    setup(props) {
        const store = useInventoryStore()
        const { items } = storeToRefs(store)
        const { armorOptions, formattedProperties, selectedArmor, state } = useAddArmorModalState(
            props.inventoryId,
            store
        )

        onMounted(() => store.loadItems('armor'))

        return {
            items,
            state,
            ui: {
                armorOptions,
                formattedProperties
            },
            selectedArmor
        }
    }
}
</script>