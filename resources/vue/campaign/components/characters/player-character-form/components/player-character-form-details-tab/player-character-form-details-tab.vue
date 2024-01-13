<template>
    <div id="info-tab" v-if="state.input">
        <div uk-grid>
            <div class="uk-width-1-2">
                <race-info-modal/>
                <div class="uk-margin">
                    <label for="race" class="uk-form-label"
                           :class="{'uk-text-danger': info.errors.hasOwnProperty('info.race_id')}">Race*</label>
                    <select id="race" name="race" class="uk-select"
                            :class="{'uk-form-danger': info.errors.hasOwnProperty('info.race_id')}"
                            v-model="state.input.race_id" @input.stop="state.input.subrace_id = null">
                        <option :value="null">- Choose a race -</option>
                        <option v-for="race in info.races.value" :value="race.id">{{ race.name }}</option>
                    </select>
                </div>
                <div class="uk-margin">
                    <label for="subrace" class="uk-form-label"
                           :class="{'uk-text-danger': info.errors.hasOwnProperty('info.subrace_id')}">Subrace*</label>
                    <select id="subrace" name="subrace" class="uk-select"
                            :class="{'uk-form-danger': info.errors.hasOwnProperty('info.subrace_id')}"
                            v-model="state.input.subrace_id" :disabled="info.subraces.value.length === 0">
                        <option :value="null">- Choose a subrace -</option>
                        <option v-for="subrace in info.subraces.value" :value="subrace.id">{{ subrace.name }}</option>
                    </select>
                </div>
                <hr>
                <div class="uk-margin">
                    <label for="name" class="uk-form-label"
                           :class="{'uk-text-danger': info.errors.hasOwnProperty('info.name')}">Name*</label>
                    <input id="name" title="name" type="text" class="uk-input"
                           :class="{'uk-form-danger': info.errors.hasOwnProperty('info.name')}"
                           v-model="state.input.name">
                </div>
                <div class="uk-margin">
                    <label for="alignment" class="uk-form-label"
                           :class="{'uk-text-danger': info.errors.hasOwnProperty('info.alignment')}">Alignment*</label>
                    <select id="alignment" name="alignment" class="uk-select"
                            :class="{'uk-form-danger': info.errors.hasOwnProperty('info.alignment')}"
                            v-model="state.input.alignment">
                        <option :value="null">- Choose an alignment -</option>
                        <option v-for="alignment in info.alignments" :value="alignment.value">{{
                                alignment.name
                            }}
                        </option>
                    </select>
                </div>
                <div class="uk-margin">
                    <label for="age" class="uk-form-label"
                           :class="{'uk-text-danger': info.errors.hasOwnProperty('info.age')}">Age</label>
                    <input id="age" name="age" type="text" class="uk-input"
                           :class="{'uk-form-danger': info.errors.hasOwnProperty('info.age')}"
                           v-model="state.input.age">
                </div>
                <div class="uk-margin uk-form-controls">
                    <input id="dead" name="dead" type="checkbox" class="uk-checkbox" v-model="state.input.dead">
                    <label for="dead">Dead</label>
                </div>
                <template v-if="ui.isOwner || state.input.owner_id === null">
                    <div class="uk-margin" v-if="ui.isOwner && info.users !== null">
                        <label for="owner_id" class="uk-form-label"
                               :class="{'uk-text-danger': info.errors.hasOwnProperty('info.owner_id')}">Owner*</label>
                        <select id="owner_id" name="owner_id" class="uk-select"
                                :class="{'uk-form-danger': info.errors.hasOwnProperty('info.owner_id')}"
                                v-model="state.input.owner_id">
                            <option :value="null">- Choose an owner -</option>
                            <option v-for="user in info.users.data" :value="user.id">{{ user.name }}</option>
                        </select>
                    </div>
                    <div v-if="ui.isOwner || state.input.owner_id === null" class="uk-margin uk-form-controls">
                        <input id="private" name="private" type="checkbox" class="uk-checkbox" v-model="info.private">
                        <label for="private">Private</label>
                    </div>
                </template>
            </div>
            <div class="uk-width-1-2">
                <label for="bio" class="uk-form-label"
                       :class="{'uk-text-danger': info.errors.hasOwnProperty('info.bio')}">Bio</label>
                <html-editor id="bio" name="bio" :class="{'uk-form-danger': info.errors.hasOwnProperty('info.bio')}"
                             v-model="state.input.bio"/>
            </div>
        </div>

        <p class="uk-margin">
            <router-link class="uk-button uk-button-danger" :to="{name: 'player-characters'}">Cancel</router-link>
            <button class="uk-button uk-button-primary uk-align-right" @click.prevent="next">Next <i
                class="fas fa-chevron-right"></i></button>
        </p>
    </div>
</template>

<script>
import RaceInfoModal from '@campaign/components/races/race-info-modal'
import { useCharacterStore } from '@campaign/stores/characters'
import { useUserStore } from '@campaign/stores/users'
import HtmlEditor from '@components/partial/html-editor'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { onMounted, watch } from 'vue'
import { alignments, usePlayerCharacterDetailState } from './player-character-form-details-tab.state'

export default {
    name: 'player-character-form-details-tab',
    props: ['errors', 'input'],
    setup(props, ctx) {
        const store = useCharacterStore()
        const userStore = useUserStore()
        const { users } = storeToRefs(userStore)
        const { backgrounds, races } = storeToRefs(store)
        const { isOwner, state, subraces } = usePlayerCharacterDetailState(store, userStore, props)

        onMounted(() => state.init())

        watch(state, () => ctx.emit('update', { ...state.input }))

        return {
            state,
            info: {
                alignments,
                backgrounds,
                errors: props.errors,
                races,
                subraces,
                users
            },
            ui: {
                isOwner
            },
            next: () => ctx.emit('next')
        }
    },
    components: { RaceInfoModal, HtmlEditor }
}
</script>