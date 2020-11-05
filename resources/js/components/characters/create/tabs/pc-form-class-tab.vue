<template>
    <div class="player-classes">
        <button class="uk-button uk-button-secondary uk-margin-bottom" @click.prevent="openModal">
            Class list
        </button>
        <div uk-accordion="active: 0">
            <div class="accordion" v-for="(charClass, index) in classes">
                <div class="uk-accordion-title">
                    Level {{ charClass.level }}
                    {{ charClass.subclass_id > 0 ? subclasses[charClass.class_id][charClass.subclass_id].name : '' }}
                    {{ charClass.class_id > 0 ? availableClasses[charClass.class_id].name : ' Nobody' }}
                </div>
                <div class="uk-accordion-content">
                    <div class="uk-margin">
                        <label :for="`class_${index}`" class="uk-form-label">Class</label>
                        <select :id="`class_${index}`" :name="`class_${index}`" class="uk-select" v-model="charClass.class_id" @input="charClass.subclass_id = null">
                            <option :value="null">- Choose a class -</option>
                            <option v-for="availableClass in classOptions" :value="availableClass.id">{{ availableClass.name }}</option>
                        </select>
                    </div>
                    <div class="uk-margin">
                        <label :for="`subclass_${index}`" class="uk-form-label">Subclass</label>
                        <select :id="`subclass_${index}`" :name="`subclass_${index}`" class="uk-select" v-model="charClass.subclass_id"
                                :disabled="charClass.class_id == null || availableClasses[charClass.class_id].subclasses.length == 0">
                            <option :value="null">- Choose a subclass -</option>
                            <option v-for="subclass in subclasses[charClass.class_id]" :value="subclass.id">{{ subclass.name }}</option>
                        </select>
                    </div>
                    <div class="uk-margin">
                        <label for="level" class="uk-form-label">Level</label>
                        <input id="level" type="number" name="level" class="uk-input" min="1" max="20" v-model="charClass.level" />
                    </div>
                    <a v-if="classes.length > 1"
                       class="uk-text-danger uk-float-right" @click.prevent="removeClass(index)">
                        <i class="fa fa-trash"></i>
                    </a>
                </div>
            </div>
            <button class="uk-align-center uk-button uk-button-primary uk-button-round"
                    @click.prevent="addClass">
                <i class="fas fa-plus fa-fw"></i>
            </button>
        </div>
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
            }
        },
        computed: {
            ...mapState('Characters', {'availableClasses': 'classes'}),
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