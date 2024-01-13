<template>
    <div id="class-info-modal" uk-modal>
        <div class="uk-width-expand uk-modal-dialog uk-modal-body">
            <h2 v-if="ui.activeClass.value" class="uk-modal-title">
                <img style="height: 40px; width: 40px;" :alt="`${ui.activeClass.value.name} Logo`"
                     :src="`/img/classes/${ui.activeClass.value.name}.svg`">
                {{ ui.activeClass.value.name }}
                <span v-if="ui.activeClass.value.spellcaster" class="uk-text-top uk-text-small">
                    <i class="fas fa-hand-sparkles" title="Spellcasting class"></i>
                </span>
            </h2>
            <h2 v-else class="uk-modal-title">Classes</h2>
            <div uk-grid>
                <div class="uk-width-1-5">
                    <ul class="uk-nav uk-nav-default">
                        <template v-for="charClass in ui.classes.value">
                            <li :class="{'uk-active': state.active.class && charClass.id === state.active.class}">
                                <a href="#" @click.prevent="state.setActive(charClass)">
                                    {{ charClass.name }}
                                </a>
                            </li>
                        </template>
                    </ul>
                </div>
                <div class="uk-width-4-5">
                    <div v-if="ui.activeClass.value">
                        <ul uk-tab>
                            <li :class="{'uk-active': state.active.tab === 'description'}">
                                <a href="#" @click.prevent="state.active.tab = 'description'">Description</a>
                            </li>
                            <li :class="{'uk-active': state.active.tab === 'features'}">
                                <a href="#" @click.prevent="state.active.tab = 'features'">Features</a>
                            </li>
                            <li :class="{'uk-active': state.active.tab === 'subclasses'}">
                                <a href="#" @click.prevent="state.active.tab = 'subclasses'">{{
                                        ui.activeClass.value.subclass_flavor
                                    }}</a>
                            </li>
                        </ul>
                        <div v-show="state.active.tab === 'description'"
                             v-html="ui.activeClass.value.description"></div>
                        <div v-show="state.active.tab === 'features'" uk-grid>
                            <div class="uk-width-1-3">
                                <ul class="uk-nav uk-nav-default">
                                    <template v-for="feature in ui.activeClass.value.features">
                                        <li :class="{'uk-active': state.active.feature === feature.id}">
                                            <a href="#" @click.prevent="state.active.feature = feature.id">
                                                {{ feature.name }}
                                            </a>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                            <div class="uk-width-2-3 class-specs">
                                <h4>{{ ui.activeFeature.value.name }}</h4>
                                <div v-html="ui.activeFeature.value.description"></div>
                            </div>
                        </div>
                        <div v-show="state.active.tab === 'subclasses'">
                            <ul uk-tab>
                                <template v-for="subclass in ui.activeClass.value.subclasses">
                                    <li :class="{'uk-active': state.active.subclass == subclass.id}">
                                        <a href="#" @click.prevent="state.setActiveSubclass(subclass.id)">
                                            {{ subclass.name }}
                                            <span v-if="!ui.activeClass.value.spellcaster && subclass.spellcaster">
                                                (<i class="fas fa-hand-sparkles" title="Spellcasting subclass"></i>)
                                            </span>
                                        </a>
                                    </li>
                                </template>
                            </ul>
                            <div v-if="state.active.subclass == subclass.id" class="subclass-info"
                                 v-for="subclass in ui.activeClass.value.subclasses">
                                <div class="class-description" v-html="subclass.description"></div>
                                <h4>Features</h4>
                                <div uk-grid>
                                    <div class="uk-width-1-3">
                                        <ul class="uk-nav uk-nav-default">
                                            <li :class="{'uk-active': state.active.subFeature === feature.id}"
                                                v-for="feature in ui.activeSubclass.value.features">
                                                <a href="#" @click.prevent="state.active.subFeature = feature.id">
                                                    {{ feature.name }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div v-if="ui.activeSubclassFeature.value" class="uk-width-2-3">
                                        <h4>{{ ui.activeSubclassFeature.value.name }}</h4>
                                        <div v-html="ui.activeSubclassFeature.value.description"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="uk-text-center" v-else>
                        <i class="fas fa-2x fa-sync fa-spin"></i>
                    </p>
                </div>
            </div>
            <button class=" uk-modal-close-default uk-close-large" type="button" uk-close></button>
        </div>
    </div>
</template>

<script>
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { computed, reactive, watch } from 'vue'
import { useCharacterStore } from '../../stores/characters'

export default {
    name: 'class-info-modal',
    setup() {
        const store = useCharacterStore()
        const { classes } = storeToRefs(store)

        const state = reactive({
            active: {
                class: null,
                subclass: null,
                tab: 'description',
                feature: null,
                subFeature: null
            },
            setActive(charClass) {
                this.active.class = charClass.id
                const subclass = charClass.subclasses[0]
                this.active.subclass = subclass.id
                this.active.tab = 'description'
                this.active.feature = charClass.features[0].id
                this.active.subFeature = subclass.features[0].id
            },
            setActiveSubclass(subclassId) {
                const subclass = this.activeClass.subclasses.find(item => item.id === subclassId)
                this.active.subclass = subclassId
                this.active.subFeature = subclass.features[0].id
            }
        })

        const activeClass = computed(() => classes.value?.[state.active.class] || null)
        const activeSubclass = computed(
            () => activeClass.value.subclasses.find(item => item.id === state.active.subclass) || null
        )
        const activeFeature = computed(
            () => activeClass.value?.features?.find((feature) => feature.id === state.active.feature) || null
        )

        const activeSubclassFeature = computed(
            () => activeSubclass.value?.features?.find((feature) => feature.id === state.active.subFeature) || null
        )

        watch(classes.value, (value) => {
            if (value) {
                state.setActive(value[Object.keys(value)[0]])
            }
        })

        return { state, ui: { activeClass, activeSubclass, activeFeature, activeSubclassFeature, classes } }
    }
}
</script>