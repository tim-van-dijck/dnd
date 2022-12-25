<template>
    <div class="player-classes">
        <button class="uk-button uk-button-secondary uk-margin-bottom" @click.prevent="ui.openModal">
            Class list
        </button>
        <div v-if="info.classes.value" v-for="(charClass, index) in state.input"
             class="uk-card uk-card-secondary objective">
            <div class="uk-card-header">
                <h3 class="uk-card-title">
                    Level {{ charClass.level }}
                    {{
                        charClass.subclass_id > 0
                            ? info.subclasses.value?.[charClass.class_id]?.[charClass.subclass_id]?.name
                            : ''
                    }}
                    {{ charClass.class_id > 0 ? info.classes.value?.[charClass.class_id]?.name : ' Nobody' }}
                </h3>
                <div class="uk-margin">
                    <label :for="`class_${index}`" class="uk-form-label"
                           :class="{'uk-text-danger': info.errors.hasOwnProperty(`classes.${index}.class_id`)}">Class*</label>
                    <select :id="`class_${index}`" :name="`class_${index}`" class="uk-select"
                            :class="{'uk-form-danger': info.errors.hasOwnProperty(`classes.${index}.class_id`)}"
                            v-model="charClass.class_id" @input="charClass.subclass_id = null">
                        <option :value="null">- Choose a class -</option>
                        <option v-for="classOption in info.classOptions.value" :value="classOption.id">{{
                                classOption.name
                            }}
                        </option>
                    </select>
                </div>
                <div class="uk-margin">
                    <label :for="`subclass_${index}`" class="uk-form-label"
                           :class="{'uk-text-danger': info.errors.hasOwnProperty(`classes.${index}.subclass_id`)}">Subclass*</label>
                    <select :id="`subclass_${index}`" :name="`subclass_${index}`" class="uk-select"
                            :class="{'uk-form-danger': info.errors.hasOwnProperty(`classes.${index}.subclass_id`)}"
                            :disabled="ui.subclassDisabled(charClass)"
                            v-model="charClass.subclass_id">
                        <option :value="null">- Choose a subclass -</option>
                        <option v-for="subclass in info.subclasses.value[charClass.class_id]" :value="subclass.id">
                            {{ subclass.name }}
                        </option>
                    </select>
                </div>
                <div class="uk-margin">
                    <label :for="`level_${index}`" class="uk-form-label"
                           :class="{'uk-text-danger': info.errors.hasOwnProperty(`classes.${index}.level`)}">Level*</label>
                    <input :id="`level_${index}`" type="number" name="level" class="uk-input" min="1" max="20"
                           :class="{'uk-form-danger': info.errors.hasOwnProperty(`classes.${index}.level`)}"
                           v-model="charClass.level"/>
                </div>
                <div v-if="charClass.class_id" uk-accordion>
                    <div class="accordion">
                        <div class="uk-accordion-title">
                            Features
                            <i class="fas fa-xs fa-question-circle"
                               uk-tooltip="title: Check the Class List for the full description of each feature and option; pos: right; delay: 200"></i>
                        </div>
                        <div class="uk-accordion-content uk-form-horizontal">
                            <template v-for="feature in info.classes.value[charClass.class_id].features || []">
                                <div v-if="feature.level <= charClass.level"
                                     class="uk-margin">
                                    <label :class="{'uk-form-label': feature.choose > 0}"
                                           :for="`feature_${feature.id}`">{{ feature.name }}</label>
                                    <div class="uk-form-controls" v-if="feature.choose > 0">
                                        <select :id="`feature_${feature.id}_${index}`" class="uk-select"
                                                v-for="index in feature.choose"
                                                @input="state.changeChoice(charClass, feature.id, index, $event.target.value)">
                                            <option value="">- Make a choice -</option>
                                            <template v-for="choice in feature.choices">
                                                <option v-if="!state.chosen(charClass, feature.id, choice.id, index)"
                                                        :value="choice.id"
                                                        :selected="charClass.hasOwnProperty('features') && (charClass.features[feature.id] || {})[index] == choice.id">
                                                    {{ choice.name }}
                                                </option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <a v-if="state.input.length > 1" class="uk-text-danger uk-float-right"
                   @click.prevent="state.removeClass(index)">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        </div>
        <button class="uk-align-center uk-button uk-button-primary uk-button-round" @click.prevent="state.addClass">
            <i class="fas fa-plus fa-fw"></i>
        </button>
        <class-info-modal/>
        <p class="uk-margin">
            <router-link class="uk-button uk-button-danger" :to="{name: 'player-characters'}">
                Cancel
            </router-link>
            <button class="uk-button uk-button-primary uk-align-right" @click.prevent="ui.next">Next <i
                class="fas fa-chevron-right"></i></button>
        </p>
    </div>
</template>

<script>
import ClassInfoModal from '@campaign/components/classes/class-info-modal'
import { useCharacterStore } from '@campaign/stores/characters'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import UIKit from 'uikit'
import { onMounted, watch } from 'vue'
import { usePlayerCharacterClassState } from './player-character-form-class-tab.state'

export default {
    name: 'player-character-form-class-tab',
    props: ['input', 'errors'],
    components: { ClassInfoModal },
    setup(props, ctx) {
        const store = useCharacterStore()
        const { classes } = storeToRefs(store)
        const { classOptions, state, subclasses } = usePlayerCharacterClassState(props)

        onMounted(() => state.init())
        watch(state, () => ctx.emit('update', state.input))

        return {
            state,
            info: {
                classes,
                classOptions,
                errors: props.errors,
                subclasses
            },
            ui: {
                next: () => ctx.emit('next'),
                openModal: () => UIKit.modal(`#class-info-modal`).show(),
                subclassDisabled(charClass) {
                    return charClass.class_id == null ||
                        classes.value?.[charClass.class_id]?.subclasses?.length === 0 ||
                        classes.value?.[charClass.class_id]?.subclass_level > charClass.level
                }
            }
        }
    }
}
</script>