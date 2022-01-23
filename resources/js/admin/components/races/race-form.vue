<template>
    <div>
        <h1>{{ title }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="race" class="uk-form-stacked" uk-grid>
                    <div class="uk-width-1-2">
                        <div class="uk-margin">
                            <label for="name" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('name')}">Name*</label>
                            <input id="name" title="name" type="text" class="uk-input" :class="{'uk-form-danger': errors.hasOwnProperty('name')}"
                                   v-model="race.name">
                        </div>
                        <div class="uk-margin">
                            <label for="size" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('size')}">Size*</label>
                            <select id="size" name="size" type="text" class="uk-input" :class="{'uk-form-danger': errors.hasOwnProperty('size')}"
                                    v-model="race.size">
                                <option value="">- Choose a size -</option>
                                <option v-for="size in sizes" :value="size">{{ size }}</option>
                            </select>
                        </div>
                        <div class="uk-margin">
                            <label for="speed" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('Speed')}">Speed*</label>
                            <input id="speed" title="speed" type="number" min="0" class="uk-input" :class="{'uk-form-danger': errors.hasOwnProperty('speed')}"
                                   v-model="race.speed">
                        </div>
                        <div class="uk-margin">
                            <label for="description" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('description')}">Description</label>
                            <html-editor id="description" name="description" v-model="race.description" height="600"></html-editor>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <h3>Ability Bonuses</h3>
                        <h3>Proficiencies</h3>
                        <h3>Languages</h3>
                    </div>
                    <p class="uk-margin">
                        <button class="uk-button uk-button-primary" @click.prevent="save">Save</button>
                        <router-link class="uk-button uk-button-danger" :to="{name: 'races'}">
                            Cancel
                        </router-link>
                    </p>
                </form>
                <p v-else class="uk-text-center">
                    <i class="fas fa-2x fa-sync fa-spin"></i>
                </p>
            </div>
        </div>
    </div>
</template>

<script>

import HtmlEditor from "../../../components/partial/html-editor";
export default {
    name: "race-form",
    components: {HtmlEditor},
    data() {
        return {
            errors: {},
            race: {
                name: '',
                description: '',
                size: '',
                speed: 0
            },
            sizes: [
                'Tiny',
                'Small',
                'Medium',
                'Large',
                'Huge',
                'Gargantuan',
            ]
        }
    },
    computed: {
        title() {
            if (this.id) {
                return 'Edit ' + (this.race ? this.race.name : 'race');
            } else {
                return 'Add race';
            }
        }
    }
}
</script>