<template>
    <div id="class-tab" class="uk-width">
        <div v-if="Object.keys(availableClasses).length > 0" class="uk-card uk-card-body uk-card-secondary" v-for="charClass in characterClasses">
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
                                        <li v-if="feature.choose === 0" :class="{'uk-active': charClass.activeFeature === feature}">
                                            <a href="#" @click.prevent="setActive('features', charClass.id, feature)">
                                                {{ feature.name }}
                                            </a>
                                        </li>
                                        <li v-else-if="(feature.choices || []).length > 0"
                                            v-for="choice in feature.choices"
                                            :class="{'uk-active': charClass.activeFeature === choice}">
                                            <a href="#" @click.prevent="setActive('features', charClass.id, choice)">
                                                {{ choice.name }}
                                            </a>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                            <div class="uk-width-2-3 class-specs" v-if="active.features[charClass.id]">
                                <h4>{{ active.features[charClass.id].name }}</h4>
                                <div v-html="active.features[charClass.id].description"></div>
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
    import {mapState} from "vuex";

    export default {
        name: "pc-detail-class-tab",
        props: ['classes'],
        created() {
            this.$store.dispatch('Characters/loadClasses');
        },
        data() {
            return {
                active: {
                    features: {}
                }
            }
        },
        methods: {
            setActive(type, classId, object) {
                if (this.active.hasOwnProperty(type)) {
                    this.$set(this.active[type], classId, object);
                }
            }
        },
        computed: {
            ...mapState('Characters', {'availableClasses': 'classes'}),
            characterClasses() {
                let classes = [];
                if (Object.keys(this.availableClasses).length === 0) {
                    return classes;
                }
                for (let charClass of this.classes) {
                    let classObject = copy(this.availableClasses[charClass.id] || {});
                    classObject.level = charClass.level;
                    classObject.features = charClass.features.sort((a,b) => {
                        return a.level < b.level ? -1 : (a.level > b.level);
                    });

                    if (charClass.subclass_id) {
                        classObject.subclass = classObject.subclasses.find(item => item.id === charClass.subclass_id);
                    }
                    classes.push(classObject);
                }
                return classes;
            }
        }
    }
</script>