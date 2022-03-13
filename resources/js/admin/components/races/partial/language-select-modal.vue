<template>
    <div>
        <button class="uk-button uk-button-primary" type="button" uk-toggle="target: #lang-select-modal">
            Select language
        </button>
        <div id="lang-select-modal" uk-modal @beforehide="reset()">
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title">Select a language proficiency</h2>
                <div class="uk-margin">
                    <select name="language" id="language" class="uk-select" v-model="language.id">
                        <option :value="0">- Make a choice -</option>
                        <option v-for="language in languages" :value="language.id" :disabled="selected.includes(language.id)">
                            {{ language.name }}
                        </option>
                    </select>
                </div>
                <div class="uk-margin">
                    <label for="language-optional">
                        <input id="language-optional" class="uk-checkbox" type="checkbox" v-model="language.optional"> Optional
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
    name: "language-select-modal",
    props: {
        selected: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            language: {
                id: 0,
                optional: false
            }
        }
    },
    computed: {
        ...mapState(['languages'])
    },
    methods: {
        save() {
            if (this.language.id > 0) {
                this.$emit('input', this.language)
                this.close()
            }
        },
        reset() {
            this.$set(this, 'language', {id: 0, optional: false})
        },
        close() {
            UIKit.modal('#lang-select-modal').hide()
        }
    }
}
</script>