<template>
    <div>
        <button class="uk-button uk-button-primary" type="button" uk-toggle="target: #add-armor-modal">
            <i class="fas fa-plus"></i> Add armor
        </button>
        <div id="add-armor-modal" uk-modal @beforehide="armorId = 0; quantity = 1">
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title">Add a piece of armor</h2>
                <form class="uk-margin" @submit.prevent="addArmor">
                    <label class="uk-form-label" for="armor-selection">Select</label>
                    <div class="uk-flex uk-flex-between">
                        <div class="uk-form-controls">
                            <select class="uk-select uk-width-auto"
                                    :class="{'uk-form-danger': errors.hasOwnProperty('id')}"
                                    id="armor-selection" v-model="armorId">
                                <option :value="0">- Choose a piece of armor -</option>
                                <optgroup v-for="(options, type) in armorOptions" :label="type">
                                    <option v-for="option in options" :value="option.id">{{ option.name }}</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="uk-form-controls">
                            <input class="uk-input uk-width-small"
                                   :class="{'uk-form-danger': errors.hasOwnProperty('quantity')}"
                                   id="quantity-selection" type="number" v-model="quantity">
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
                            {{
                                (
                                    selectedArmor.name === 'Shield'
                                ) ? '+2' : selectedArmor.properties.ac
                            }}
                            <template v-if="selectedArmor.properties.add_dex">+ Dex modifier (max. 2)</template>
                        </p>
                        <p>
                            <span v-for="property in formattedProperties"
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
import UIKit from 'uikit'
import { mapState } from 'vuex'

export default {
    props: ['inventoryId'],
    name: 'add-armor-modal',
    created() {
        this.$store.dispatch('Inventory/loadItems', 'inventory-tab-armor')
    },
    data() {
        return {
            quantity: 1,
            armorId: 0,
            errors: {}
        }
    },
    methods: {
        addArmor() {
            this.$store.dispatch(
                'Inventory/add',
                { inventoryId: this.inventoryId, item: this.selectedArmor, quantity: this.quantity }
            )
                .then(() => {
                    this.$set(this, 'errors', {})
                    UIKit.modal('#add-inventory-tab-armor-modal').hide()
                })
                .catch((exception) => {
                    this.$set(this, 'errors', exception.response.data.errors)
                })
        }
    },
    computed: {
        ...mapState('Inventory', ['items']),
        selectedArmor() {
            if (this.armorId <= 0) {
                return null
            }
            return this.items?.armor.find(pieceOfArmor => pieceOfArmor.id === this.armorId)
        },
        formattedProperties() {
            if (!this.selectedArmor) {
                return []
            }
            const result = []
            for (let property in this.selectedArmor.properties) {
                const value = this.selectedArmor.properties[property]
                switch (property) {
                    case 'ac':
                    case 'add_dex':
                        break
                    case 'strength':
                        result.push({ label: `Min. Strength: ${value}`, type: 'warning' })
                        break
                    case 'stealth_disadvantage':
                        let title = value ? 'Disadvantage on Stealth' : 'No disadvantage on Stealth'
                        result.push({ label: 'Stealth', type: value ? 'danger' : 'success', title })
                        break
                    case 'don':
                    case 'doff':
                        const propertyName = property.replace(/^\w/, (c) => c.toUpperCase())
                        result.push({ label: `${propertyName}: (${value})` })
                        break
                }
            }
            return result
        },
        armorOptions() {
            const armorOptions = this.items?.armor || []
            let grouped = {
                Light: [],
                Medium: [],
                Heavy: [],
                Shield: []
            }
            for (let armor of armorOptions) {
                if (!grouped.hasOwnProperty(armor.type)) {
                    grouped[armor.type] = []
                }
                grouped[armor.type].push(armor)
            }
            return grouped
        }
    }
}
</script>