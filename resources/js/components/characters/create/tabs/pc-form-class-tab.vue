<template>
    <div class="player-classes">
        <button class="uk-button uk-button-secondary uk-margin-bottom" @click.prevent="openModal">
            Class list
        </button>
        <div v-if="Object.keys(availableClasses).length > 0" v-for="(charClass, index) in classes" class="uk-card uk-card-secondary objective">
            <div class="uk-card-header">
                <h3 class="uk-card-title">
                    Level {{ charClass.level }}
                    {{ charClass.subclass_id > 0 ? subclasses[charClass.class_id][charClass.subclass_id].name : '' }}
                    {{ charClass.class_id > 0 ? availableClasses[charClass.class_id].name : ' Nobody' }}
                </h3>
                <div class="uk-margin">
                    <label :for="`class_${index}`" class="uk-form-label"
                           :class="{'uk-text-danger': errors.hasOwnProperty(`classes.${index}.class_id`)}">Class*</label>
                    <select :id="`class_${index}`" :name="`class_${index}`" class="uk-select"
                            :class="{'uk-form-danger': errors.hasOwnProperty(`classes.${index}.class_id`)}"
                            v-model="charClass.class_id" @input="charClass.subclass_id = null">
                        <option :value="null">- Choose a class -</option>
                        <option v-for="availableClass in classOptions" :value="availableClass.id">{{ availableClass.name }}</option>
                    </select>
                </div>
                <div class="uk-margin">
                    <label :for="`subclass_${index}`" class="uk-form-label"
                           :class="{'uk-text-danger': errors.hasOwnProperty(`classes.${index}.subclass_id`)}">Subclass*</label>
                    <select :id="`subclass_${index}`" :name="`subclass_${index}`" class="uk-select"
                            :class="{'uk-form-danger': errors.hasOwnProperty(`classes.${index}.subclass_id`)}"
                            :disabled="charClass.class_id == null || availableClasses[charClass.class_id].subclasses.length === 0 || availableClasses[charClass.class_id].subclass_level > charClass.level"
                            v-model="charClass.subclass_id">
                        <option :value="null">- Choose a subclass -</option>
                        <option v-for="subclass in subclasses[charClass.class_id]" :value="subclass.id">{{ subclass.name }}</option>
                    </select>
                </div>
                <div class="uk-margin">
                    <label :for="`level_${index}`" class="uk-form-label"
                           :class="{'uk-text-danger': errors.hasOwnProperty(`classes.${index}.level`)}">Level*</label>
                    <input :id="`level_${index}`" type="number" name="level" class="uk-input"min="1" max="20"
                           :class="{'uk-form-danger': errors.hasOwnProperty(`classes.${index}.level`)}"
                           v-model="charClass.level" />
                </div>
                <div v-if="charClass.class_id" uk-accordion>
                    <div class="accordion">
                        <div class="uk-accordion-title">
                            Features
                            <i class="fas fa-xs fa-question-circle"
                               uk-tooltip="title: Check the Class List for the full description of each feature and option; pos: right; delay: 200"></i>
                        </div>
                        <div class="uk-accordion-content uk-form-horizontal">
                            <div v-if="feature.level <= charClass.level"
                                 v-for="feature in availableClasses[charClass.class_id].features || []"
                                 class="uk-margin">
                                <label :class="{'uk-form-label': feature.choose > 0}"
                                       :for="`feature_${feature.id}`">{{ feature.name }}</label>
                                <div class="uk-form-controls" v-if="feature.choose > 0">
                                    <select :id="`feature_${feature.id}_${index}`" class="uk-select"
                                            v-for="index in feature.choose"
                                            @input="changeChoice(charClass, feature.id, index, $event.target.value)">
                                        <option value="">- Make a choice -</option>
                                        <option v-if="!chosen(charClass, feature.id, choice.id, index)"
                                                v-for="choice in feature.choices"
                                                :value="choice.id"
                                                :selected="charClass.hasOwnProperty('features') && (charClass.features[feature.id] || {})[index] == choice.id">{{ choice.name }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a v-if="classes.length > 1" class="uk-text-danger uk-float-right" @click.prevent="removeClass(index)">
                        <i class="fa fa-trash"></i>
                    </a>
            </div>
        </div>
        <button class="uk-align-center uk-button uk-button-primary uk-button-round" @click.prevent="addClass">
            <i class="fas fa-plus fa-fw"></i>
        </button>
        <class-info-modal />
        <p class="uk-margin">
            <router-link class="uk-button uk-button-danger" :to="{name: 'player-characters'}">
                Cancel
            </router-link>
            <button class="uk-button uk-button-primary uk-align-right" @click.prevent="$emit('next')">Next <i class="fas fa-chevron-right"></i></button>
        </p>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import UIKit from 'uikit';
    import ClassInfoModal from "../../../classes/class-info-modal";

    export default {
        name: "pc-form-class-tab",
        props: ['value'],
        components: {ClassInfoModal},
        data() {
            return {
                classes: []
            }
        },
        created() {
            this.classes = this.value;
            if (this.classes.length === 0) {
                this.addClass();
            }
        },
        methods: {
            addClass() {
                this.classes.push({class_id: null, subclass_id: null, level: 1})
            },
            removeClass(index) {
                this.classes.splice(index, 1);
            },
            openModal() {
                UIKit.modal(`#class-info-modal`).show();
            },
            changeChoice(charClass, featureId, index, choice) {
                if (!charClass.hasOwnProperty('features')) {
                    this.$set(charClass, 'features', {});
                }
                if (choice === '') {
                    delete charClass.features[featureId][index];
                    if (Object.keys(charClass.features).length === 0) {
                        delete charClass.features[featureId][index];
                    }
                } else {
                    if (!charClass.features.hasOwnProperty(featureId)) {
                        this.$set(charClass.features, featureId, {});
                    }
                    this.$set(charClass.features[featureId], index, parseInt(choice));
                }
            },
            chosen(charClass, featureId, choiceId, index) {
                if (charClass.hasOwnProperty('features') && charClass.features.hasOwnProperty(featureId)) {
                    if (charClass.features[featureId][index] == choiceId) {
                        return false;
                    }
                    return Object.values(charClass.features[featureId]).map(item => parseInt(item)).includes(choiceId)
                }
                return false;
            }
        },
        computed: {
            ...mapState('Characters', {'availableClasses': 'classes', 'errors': 'errors'}),
            classOptions() {
                let classes = [];
                for (let classId in this.availableClasses) {
                    classes.push({id: classId, name: copy(this.availableClasses[classId]).name});
                }
                return classes;
            },
            subclasses() {
                let subclasses = {};
                for (let classId in this.availableClasses) {
                    if (!subclasses.hasOwnProperty(classId)) {
                        subclasses[classId] = {};
                    }
                    for (let subclass of this.availableClasses[classId].subclasses || []) {
                        subclasses[classId][subclass.id] = {id: subclass.id, name: subclass.name};
                    }
                }
                return subclasses;
            }
        },
        watch: {
            classes: {
                deep: true,
                handler() {
                    this.$emit('input', this.classes);
                }
            }
        },
    }
</script>