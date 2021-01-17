<template>
    <div class="instruments" v-if="hasInstruments">
        <div class="uk-accordion-title"><h2>Instruments</h2></div>
        <div class="uk-accordion-content">
            <div class="uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-small uk-grid-match uk-margin-bottom" uk-grid>
                <div v-for="instrument in known">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ instrument.name }} ({{ instrument.origin }})</div>
                    </div>
                </div>
                <div v-for="(instrument, index) in (selection || [])">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ instrument.name }} ({{ instrument.origin }})</div>
                        <button class="uk-text-danger uk-float-right uk-button uk-button-primary uk-button-round"
                                @click.prevent="removeInstrument(index)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="class-based" v-if="Object.keys(availableClasses).length > 0 && classInstrumentCount > 0">
                <div class="class" v-for="(charClass, classIndex) in classes"
                     v-if="charClass.class_id != null && availableClasses[charClass.class_id].instrument_choices > (classSelected[charClass.class_id] || 0)">
                    <h4>Class: {{ availableClasses[charClass.class_id].name }}</h4>
                    <div class="uk-margin">
                        <label :for="`instruments_${classIndex}`">
                            Choose {{ availableClasses[charClass.class_id].instrument_choices - (selection[charClass.class_id] || []).length }} instrument proficiencies
                        </label>
                        <select :name="`instruments_${classIndex}`" :id="`instruments_${classIndex}`" class="uk-select"
                                @input="addInstrumentToClass(availableClasses[charClass.class_id], $event.target.value); $event.target.value = ''">
                            <option :value="null">- Make a choice -</option>
                            <option v-for="instrument in classInstruments[charClass.class_id].optional" :value="instrument.id"
                                    :disabled="classInstruments[charClass.class_id].known.includes(instrument.id)">
                                {{ instrument.name }}
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
        props: ['background', 'classes', 'race', 'subrace', 'value'],
        created() {
            this.$set(this, 'selection', this.value || []);
            for (let selected of this.selection) {
                if (selected.origin_type === 'class') {
                    if (this.classSelected.hasOwnProperty(selected.origin_id)) {
                        this.classSelected[selected.origin_id]++;
                    } else {
                        this.classSelected[selected.origin_id] = 1;
                    }
                }
            }
        },
        data() {
            return {
                selection: [],
                classSelected: {}
            }
        },
        methods: {
            addInstrumentToClass(charClass, input) {
                let instrument = this.classInstruments[charClass.id].optional.find((item) => item.id == input);
                this.selection.splice(this.selection.length, 1, {
                    origin_type: 'class',
                    origin_id: charClass.id,
                    origin: charClass.name,
                    name: instrument.name,
                    id: input
                });
                if (this.classSelected.hasOwnProperty(charClass.id)) {
                    this.classSelected[charClass.id]++;
                } else {
                    this.classSelected[charClass.id] = 1;
                }
            },
            removeInstrument(index) {
                let instrument = this.selection[index];
                if (instrument.origin_type == 'class') {
                    this.classSelected[instrument.origin_id]--;
                }
                this.selection.splice(index, 1);
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
                if (Object.keys(this.availableClasses).length == 0) {
                    return {};
                }
                let instruments = {};
                for (let chosenClass of this.classes) {
                    if (chosenClass.class_id) {
                        let charClass = copy(this.availableClasses[chosenClass.class_id]);
                        instruments[chosenClass.class_id] = {
                            known: charClass.proficiencies.filter(item => item.type === 'Instruments' && !item.optional),
                            optional: charClass.proficiencies.filter((item) => {
                                if (item.type === 'Instruments' && item.optional) {
                                    let canChoose = true;
                                    let duplicateClassInstrument = this.selection.find(instrument => item.id == instrument.id);
                                    let duplicateRaceInstrument = this.raceInstruments.find(instrument => item.id == instrument.id)
                                    if (duplicateClassInstrument || duplicateRaceInstrument) {
                                        canChoose = false;
                                    }
                                    return canChoose;
                                }
                                return false;
                            })
                        };
                    }
                }
                return instruments;
            },
            backgroundInstruments() {
                let instruments = {known: [], optional: []};
                if (this.background) {
                    instruments.known = copy(this.background.tools)
                        .filter(item => item.type == 'Instruments' && item.pivot.optional == 0)
                        .map((item) => {
                            return {
                                id: item.id,
                                name: item.name,
                                origin: this.background.name
                            };
                        });
                    instruments.optional = copy(this.background.tools)
                        .filter(item => item.type == 'Instruments' && item.pivot.optional == 1)
                        .map((item) => {
                            return {
                                id: item.id,
                                name: item.name,
                                origin: this.background.name
                            };
                        });
                }
                return instruments;
            },
            known() {
                let known = [];
                if (this.raceInstruments) {
                    known.concat(this.raceInstruments.known);
                }
                if (this.classSkills) {
                    for (let classId in this.classInstruments) {
                        known.concat(this.classInstruments[classId].known);
                    }
                }
                if (this.backgroundInstruments) {
                    known.concat(this.backgroundInstruments);
                }
                return known;
            },
            hasInstruments() {
                return this.raceInstruments.length + this.classInstrumentCount;
            },
            classInstrumentCount() {
                let count = 0;
                for (let classId in this.classInstruments) {
                    count += this.classInstruments[classId].known.length;
                    count += this.classInstruments[classId].optional.length;
                }
                return count;
            }
        },
        watch: {
            background: {
                deep: true,
                handler() {
                    let selection = this.selection.filter((item) => item.origin_type !== 'background');
                    this.$set(this, 'selection', selection);
                }
            },
            classes: {
                deep: true,
                handler() {
                    let selection = [];
                    for (let charClass of this.classes) {
                        let instruments = this.selection.filter((item) => {
                            return item.origin_type === 'class' && item.origin_id === charClass.class_id;
                        });
                        selection.concat(instruments);
                    }
                    selection.concat(this.selection.filter((item) => item.origin_type === 'background'))
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