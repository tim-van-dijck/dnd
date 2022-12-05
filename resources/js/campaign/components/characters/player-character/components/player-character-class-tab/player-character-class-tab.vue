<template>
    <div id="class-tab" class="uk-width">
        <div v-if="Object.keys(state.availableClasses).length > 0" class="uk-card uk-card-body uk-card-secondary"
             v-for="charClass in state.characterClasses.value">
            <div v-if="charClass.id" uk-accordion>
                <div class="accordion">
                    <div class="uk-accordion-title uk-card-title">
                        Level {{ charClass.level }}
                        {{ charClass.subclass ? charClass.subclass.name : '' }}
                        {{ charClass.name }}
                    </div>
                    <div class="uk-accordion-content uk-form-horizontal">
                        <h3>Features</h3>
                        <div uk-grid>
                            <div class="uk-width-1-3">
                                <ul class="uk-nav uk-nav-default">
                                    <template v-for="feature in charClass.features">
                                        <li v-if="feature.choose === 0"
                                            :class="{'uk-active': charClass.activeFeature === feature}">
                                            <a href="#"
                                               @click.prevent="ui.setActive('features', charClass.id, feature)">
                                                {{ feature.name }}
                                            </a>
                                        </li>
                                        <li v-else-if="(feature.choices || []).length > 0"
                                            v-for="choice in feature.choices"
                                            :class="{'uk-active': charClass.activeFeature === choice}">
                                            <a href="#" @click.prevent="ui.setActive('features', charClass.id, choice)">
                                                {{ choice.name }}
                                            </a>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                            <div class="uk-width-2-3 class-specs" v-if="ui.active.features[charClass.id]">
                                <h4>{{ ui.active.features[charClass.id].name }}</h4>
                                <div v-html="ui.active.features[charClass.id].description"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p v-else class="uk-text-center">
            <i class="fas fa-2x fa-sync fa-spin"></i>
        </p>
    </div>
</template>

<script>
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { computed, onMounted } from 'vue'
import { useCharacterStore } from '../../../../../stores/characters'
import { useActive } from './player-character-class-tab.ui'

export default {
    name: 'player-character-class-tab',
    props: ['classes'],
    setup: function (props) {
        const store = useCharacterStore()
        const ui = useActive()

        const { classes } = storeToRefs(store)

        onMounted(() => store.loadClasses())
        const characterClasses = computed(() => {
            const formatted = []
            if (Object.keys(classes.value || {}).length === 0) {
                return formatted
            }
            for (const charClass of props.classes) {
                const classObject = {
                    ...(
                        classes.value[charClass.id] || {}
                    ),
                    level: charClass.level,
                    features: charClass.features.sort((a, b) => a.level < b.level ? -1 : (
                        a.level > b.level
                    ))
                }

                if (charClass.subclass_id) {
                    classObject.subclass = classObject.subclasses.find(item => item.id === charClass.subclass_id)
                }
                formatted.push(classObject)
            }
            return formatted
        })

        return { state: { availableClasses: classes, characterClasses }, ui }
    }
}
</script>