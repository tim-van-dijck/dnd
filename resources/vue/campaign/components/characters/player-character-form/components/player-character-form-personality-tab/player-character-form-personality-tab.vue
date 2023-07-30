<template>
    <div class="personality">
        <div class="uk-margin">
            <h2 class="uk-h4">Personality trait</h2>
            <html-editor id="trait" name="trait" v-model="state.input.trait" height="200"></html-editor>
        </div>
        <div class="uk-margin">
            <h2 class="uk-h4">Ideal</h2>
            <html-editor id="ideal" name="ideal" v-model="state.input.ideal" height="200"></html-editor>
        </div>
        <div class="uk-margin">
            <h2 class="uk-h4">Bond</h2>
            <html-editor id="bond" name="bond" v-model="state.input.bond" height="200"></html-editor>
        </div>
        <div class="uk-margin">
            <h2 class="uk-h4">Flaw</h2>
            <html-editor id="flaw" name="flaw" v-model="state.input.flaw" height="200"></html-editor>
        </div>

        <p class="uk-margin">
            <router-link class="uk-button uk-button-danger" :to="{name: 'player-characters'}">
                Cancel
            </router-link>
            <button class="uk-button uk-button-primary uk-align-right" @click.prevent="ui.next">
                <span v-if="spellcaster">Next <i class="fas fa-chevron-right"></i></span>
                <span v-else>Save</span>
            </button>
        </p>
    </div>
</template>

<script>
import HtmlEditor from '@components/partial/html-editor'
import { onMounted, reactive, watch } from 'vue'

export default {
    name: 'player-character-form-personality-tab',
    props: ['input', 'spellcaster'],
    setup(props, ctx) {
        const state = reactive({
            input: {
                trait: '',
                ideal: '',
                bond: '',
                flaw: ''
            },
            setInput(input) {
                this.input = {
                    trait: input?.trait || '',
                    ideal: input?.ideal || '',
                    bond: input?.bond || '',
                    flaw: input?.flaw || ''
                }
            }
        })

        onMounted(() => state.setInput(props.input.personality))

        watch(state, () => ctx.emit('update', { ...state.input }))

        return {
            state,
            ui: {
                next: () => ctx.emit('next'),
                spellcaster: props.spellcaster
            }
        }
    },
    components: { HtmlEditor }
}
</script>