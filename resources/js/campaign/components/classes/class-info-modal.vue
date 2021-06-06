<template>
    <div id="class-info-modal" uk-modal>
        <div class="uk-width-expand uk-modal-dialog uk-modal-body">
            <h2 v-if="activeClass" class="uk-modal-title">
                <img style="height: 40px; width: 40px;" :src="`/img/classes/${activeClass.name}.svg`">
                {{ activeClass.name }}
                <span v-if="activeClass.spellcaster" class="uk-text-top uk-text-small">
                    <i class="fas fa-hand-sparkles" title="Spellcasting class"></i>
                </span>
            </h2>
            <h2 v-else class="uk-modal-title">Classes</h2>
            <div uk-grid>
                <div class="uk-width-1-5">
                    <ul class="uk-nav uk-nav-default">
                        <li :class="{'uk-active': active.class && charClass.id === active.class}"
                            v-for="charClass in classes">
                            <a href="#" @click.prevent="setActive(charClass)">
                                {{ charClass.name }}
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="uk-width-4-5" v-if="activeClass">
                    <div v-if="activeClass">
                        <ul uk-tab>
                            <li :class="{'uk-active': active.tab === 'description'}">
                                <a href="#" @click.prevent="active.tab = 'description'">Description</a>
                            </li>
                            <li :class="{'uk-active': active.tab === 'features'}">
                                <a href="#" @click.prevent="active.tab = 'features'">Features</a>
                            </li>
                            <li :class="{'uk-active': active.tab === 'subclasses'}">
                                <a href="#" @click.prevent="active.tab = 'subclasses'">{{ activeClass.subclass_flavor }}</a>
                            </li>
                        </ul>
                        <div v-show="active.tab == 'description'" v-html="activeClass.description"></div>
                        <div v-show="active.tab == 'features'" uk-grid>
                            <div class="uk-width-1-3">
                                <ul class="uk-nav uk-nav-default">
                                    <li :class="{'uk-active': active.feature === feature.id}"
                                        v-for="feature in activeClass.features">
                                        <a href="#" @click.prevent="active.feature = feature.id">
                                            {{ feature.name }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="uk-width-2-3 class-specs">
                                <h4>{{ activeFeature.name }}</h4>
                                <div v-html="activeFeature.description"></div>
                            </div>
                        </div>
                        <div v-show="active.tab === 'subclasses'">
                            <ul uk-tab>
                                <li :class="{'uk-active': active.subclass == subclass.id}" v-for="subclass in activeClass.subclasses">
                                    <a href="#" @click.prevent="setActiveSubclass(subclass.id)">
                                        {{ subclass.name }}
                                        <span v-if="!activeClass.spellcaster && subclass.spellcaster">
                                            (<i class="fas fa-hand-sparkles" title="Spellcasting subclass"></i>)
                                        </span>
                                    </a>
                                </li>
                            </ul>
                            <div v-if="active.subclass == subclass.id" class="subclass-info" v-for="subclass in activeClass.subclasses">
                                <div class="class-description" v-html="subclass.description"></div>
                                <h4>Features</h4>
                                <div uk-grid>
                                    <div class="uk-width-1-3">
                                        <ul class="uk-nav uk-nav-default">
                                            <li :class="{'uk-active': active.subFeature === feature.id}"
                                                v-for="feature in activeSubclass.features">
                                                <a href="#" @click.prevent="active.subFeature = feature.id">
                                                    {{ feature.name }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div v-if="activeSubclassFeature" class="uk-width-2-3">
                                        <h4>{{ activeSubclassFeature.name }}</h4>
                                        <div v-html="activeSubclassFeature.description"></div>
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
    import {mapState} from "vuex";

    export default {
        name: "class-info-modal",
        data() {
            return {
                active: {
                    class: null,
                    subclass: null,
                    tab: 'description',
                    feature: null,
                    subFeature: null
                }
            }
        },
        methods: {
            setActive(charClass) {
                this.active.class = charClass.id;
                let subclass = charClass.subclasses[0];
                this.active.subclass = subclass.id;
                this.active.tab = 'description';
                this.active.feature = charClass.features[0].id;
                this.active.subFeature = subclass.features[0].id;
            },
            setActiveSubclass(subclassId) {
                let subclass = this.activeClass.subclasses.find(item => item.id === subclassId);
                this.active.subclass = subclassId;
                this.active.subFeature = subclass.features[0].id;
            }
        },
        computed: {
            ...mapState('Characters', ['classes']),
            activeClass() {
                if (this.classes && this.active.class) {
                    return this.classes[this.active.class] || null;
                }
                return null;
            },
            activeSubclass() {
                if (this.activeClass && this.active.subclass) {
                    return this.activeClass.subclasses.find(item => item.id === this.active.subclass);
                }
                return null;
            },
            activeFeature() {
                if (this.activeClass && this.active.feature) {
                    return this.activeClass.features.find(item => item.id === this.active.feature);
                }
                return null;
            },
            activeSubclassFeature() {
                if (this.activeSubclass && this.active.subFeature) {
                    return this.activeSubclass.features.find(item => item.id === this.active.subFeature);
                }
                return null;
            }
        },
        watch: {
            classes: {
                deep: true,
                handler(value) {
                    if (value) {
                        let firstClass = value[Object.keys(value)[0]];
                        this.setActive(firstClass);
                    }
                }
            }
        }
    }
</script>