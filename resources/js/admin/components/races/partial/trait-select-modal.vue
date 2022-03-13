<template>
    <div>
        <button class="uk-button uk-button-primary" type="button" uk-toggle="target: #trait-select-modal">
            Select race trait
        </button>
        <div id="trait-select-modal" uk-modal @beforehide="reset()">
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title">Select a race trait</h2>
                <div class="uk-margin">
                    <select name="trait" id="trait" class="uk-select" v-model="trait.id">
                        <option :value="0">- Choose a trait -</option>
                        <option v-for="option in traitOptions" :value="option.id" :disabled="selected.includes(option.id)">{{ option.name }}</option>
                    </select>
                </div>
                <div v-if="selectedTrait" v-html="selectedTrait.description"></div>
                <hr>
                <div uk-accordion>
                    <div class="accordion">
                        <div class="uk-accordion-title">
                            <h2>or create a new one</h2>
                        </div>
                        <div class="uk-accordion-content">
                            <div class="uk-margin">
                                <label for="trait-name" class="uk-form-label">Name*</label>
                                <input id="trait-name" name="name" type="text" class="uk-input" v-model="trait.name">
                            </div>
                            <div class="uk-margin">
                                <label for="trait-description" class="uk-form-label">Description</label>
                                <html-editor id="trait-description" name="trait-description" v-model="trait.description" height="600"></html-editor>
                            </div>
                        </div>
                    </div>
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
import HtmlEditor from "../../../../components/partial/html-editor";
import {mapState} from "vuex";
import tinymce from "tinymce";
import UIkit from "uikit";
import UIKit from "uikit";

export default {
    name: "trait-select-modal",
    components: {HtmlEditor},
    props: {
        selected: {
            type: Array,
            default: () => []
        }
    },
    mounted() {
        this.$store.dispatch('Races/loadTraits');
        UIkit.util.on('#trait-select-modal', 'shown', () => {
            tinymce.init({
                height: this.height || 400,
                plugins: [
                    'link', 'hr', 'anchor', 'fullscreen',
                    'searchreplace', 'autolink', 'table'
                ],
                theme: 'silver',
                skin: 'oxide',
                skin_url: '/skins/ui/oxide',
                toolbar1: 'formatselect | bold italic underline strikethrough forecolor backcolor | link table | alignleft aligncenter alignright  | numlist bullist outdent indent | removeformat',
                init_instance_callback: function() {
                    var freeTiny = document.querySelector('.tox-notifications-container');
                    if (freeTiny) {
                        freeTiny.style.display = 'none';
                    }
                }
            });

        });

        UIkit.util.on('#trait-select-modal', 'beforehiUIkitde', () => {
            tinymce.remove("#trait-description");
        });
    },
    data() {
        return {
            errors: {},
            trait: {
                id: 0,
                name: null,
                description: null
            }
        }
    },
    methods: {
        save() {
            if (this.trait.id > 0 || this.trait.name?.length > 0) {
                const trait = {...this.trait}
                if (this.trait.id > 0) {
                    delete trait.name
                    delete trait.description
                } else {
                    delete trait.id
                }
                this.$emit('input', trait)
                this.close()
            }
        },
        reset() {
            this.$set(this, 'trait', {id: 0, name: null, description: null})
        },
        close() {
            UIkit.modal('#trait-select-modal').hide()
        }
    },
    computed: {
        ...mapState('Races', ['traits']),
        selectedTrait() {
            if (this.trait.id > 0) {
                return this.traits?.find(trait => trait.id === this.trait.id) || null
            }
            return null
        },
        traitOptions() {
            return this.traits?.map(trait => ({id: trait.id, name: trait.name, description: trait.description})) || null
        }
    }
}
</script>