<template>
    <div class="instruments" v-if="hasInstruments">
        <div class="uk-accordion-title"><h2>Instruments</h2></div>
        <div class="uk-accordion-content">
            <div class="racial" v-if="raceInstruments.length > 0">
                <h4>Racial instrument proficiencies</h4>
                <div class="uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-small uk-grid-match" uk-grid>
                    <div v-for="instrument in raceInstruments">
                        <div class="uk-card uk-card-body uk-card-primary">
                            <div class="uk-card-title">{{ instrument.name }} ({{ instrument.origin }})</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="class-based">
                <h4>Class-based</h4>
                <div class="class" v-for="(charClass, classIndex) in classes" v-if="charClass.class_id != null">
                    <h5>{{ availableClasses[charClass.class_id].name }}</h5>
                    <div class="uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-small uk-grid-match" uk-grid>
                        <div v-for="instrument in classInstruments[charClass.class_id].known">
                            <div class="uk-card uk-card-body uk-card-primary">
                                <div class="uk-card-title">{{ instrument.name }}</div>
                            </div>
                        </div>
                        <div v-for="(instrument, index) in (selection[charClass.class_id] || [])">
                            <div class="uk-card uk-card-body uk-card-primary">
                                <div class="uk-card-title">{{ instrument.name }} ({{ instrument.origin }})</div>
                                <button class="uk-text-danger uk-float-right uk-button uk-button-primary uk-button-round"
                                        @click.prevent="selection[charClass.class_id].splice(index, 1)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="uk-margin" v-if="availableClasses[charClass.class_id].instrument_choices > (selection[charClass.class_id] || []).length">
                        <label :for="`instruments_${classIndex}`">
                            Choose {{ availableClasses[charClass.class_id].instrument_choices - (selection[charClass.class_id] || []).length }} instrument proficiencies
                        </label>
                        <select :name="`instruments_${classIndex}`" :id="`instruments_${classIndex}`" class="uk-select"
                                @input="addInstrumentToClass(availableClasses[charClass.class_id], $event.target.value); $event.target.value = ''">
                            <option :value="null">- Make a choice -</option>
                            <option v-for="skill in classInstruments[charClass.class_id].optional" :value="skill.id"
                                    :disabled="classInstruments[charClass.class_id].known.includes(skill.id)">
                                {{ skill.name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "instrument-selection",
        props: ['race', 'subrace', 'classes', 'value'],
        created() {
            this.$set(this, 'selection', this.value || {});
        },
        data() {
            return {
                selection: {}
            }
        },
        methods: {
            addInstrumentToClass(charClass, input) {
                if (!this.selection.hasOwnProperty(charClass.id)) {
                    this.$set(this.selection, charClass.id, []);
                }
                let classSelection = this.selection[charClass.id];
                let skill = this.classInstruments[charClass.id].optional.find((item) => item.id == input);
                classSelection.splice(classSelection.length, 1, {origin: charClass.name, name: skill.name, id: input});
            }
        },
        computed: {
            ...mapState('Characters', {'availableClasses': 'classes'}),
            raceInstruments() {
                let instruments = [];
                if (this.race) {
                    instruments = copy(this.race.proficiencies)
                        .filter((item) => item.type == 'Instruments')
                        .map((item) => {
                            item.origin = this.race.name;
                            return item
                        });
                    if (this.subrace) {
                        let subraceInstruments = copy(this.subrace.proficiencies)
                            .filter((item) => item.type == 'Instruments')
                            .map((item) => {
                                item.origin = this.subrace.name;
                                return item
                            });
                        instruments.concat(subraceInstruments);
                    }
                }
                return instruments;
            },
            classInstruments() {
                let instruments = {};
                for (let chosenClass of this.classes) {
                    if (chosenClass.class_id) {
                        let charClass = copy(this.availableClasses[chosenClass.class_id]);
                        let classInstruments = {
                            known: charClass.proficiencies.filter(item => item.type == 'Instruments' && !item.optional),
                            optional: charClass.proficiencies.filter((item) => {
                                if (item.type == 'Instruments' && item.optional) {
                                    let canChoose = true;
                                    for (let classId in this.selection) {
                                        let duplicateClassInstrument = this.selection[classId].find(instrument => item.id == instrument.id);
                                        let duplicateRaceInstrument = this.raceInstruments.find(instrument => item.id == instrument.id)
                                        if (duplicateClassInstrument || duplicateRaceInstrument) {
                                            canChoose = false;
                                        }
                                    }
                                    return canChoose;
                                }
                                return false;
                            })
                        };
                        instruments[chosenClass.class_id] = classInstruments;
                    }
                }
                return instruments;
            },
            hasInstruments() {
                let count = 0;
                count += this.raceInstruments.length;
                for (let classId in this.classInstruments) {
                    count += this.classInstruments[classId].known.length;
                    count += this.classInstruments[classId].optional.length;
                }
                return count;
            }
        },
        watch: {
            classes: {
                deep: true,
                handler() {
                    let selection = {}
                    for (let charClass of this.classes) {
                        selection[charClass.class_id] = this.selection[charClass.class_id] || [];
                    }
                    this.$set(this, 'selection', selection);
                }
            },
            selection: {
                deep: true,
                handler() {
                    this.$emit('input', this.selection);
                }
            }
        }
    }
</script>