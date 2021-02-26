<template>
    <div class="skills">
        <div class="uk-accordion-title"><h2>Skills</h2></div>
        <div class="uk-accordion-content">
            <div class="uk-margin-bottom">
                <div class="uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-small uk-grid-match" uk-grid>
                    <div v-for="skill in known">
                        <div class="uk-card uk-card-body uk-card-primary">
                            <div class="uk-card-title">{{ skill.name }} ({{ skill.origin }})</div>
                        </div>
                    </div>
                    <div v-for="(skill, index) in (selection || [])">
                        <div class="uk-card uk-card-body uk-card-primary">
                            <div class="uk-card-title">{{ skill.name }} ({{ skill.origin }})</div>
                            <button class="uk-text-danger uk-float-right uk-button uk-button-primary uk-button-round"
                                    @click.prevent="removeSkill(index)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="class-based" v-if="Object.keys(availableClasses).length > 0">
                <div class="class" v-for="(charClass, classIndex) in classes"
                     v-if="charClass.class_id && availableClasses[charClass.class_id].skill_choices > (classSelected[charClass.class_id] || 0)">
                    <h4>Class: {{ availableClasses[charClass.class_id].name }}</h4>
                    <div class="uk-margin">
                        <label :for="`skills_${classIndex}`" :class="{'uk-text-danger': errors.hasOwnProperty('proficiencies.skills')}">
                            Choose {{ availableClasses[charClass.class_id].skill_choices - (classSelected[charClass.class_id] || 0) }} skill proficiencies
                        </label>
                        <select :name="`skills_${classIndex}`" :id="`skills_${classIndex}`" class="uk-select"
                                :class="{'uk-form-danger': errors.hasOwnProperty('proficiencies.skills')}"
                                @input="addSkillToClass(availableClasses[charClass.class_id], $event.target.value); $event.target.value = ''">
                            <option :value="null">- Make a choice -</option>
                            <option v-for="skill in classSkills[charClass.class_id].optional" :value="skill.id"
                                    :disabled="classSkills[charClass.class_id].known.includes(skill.id)">
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
        name: "skill-selection",
        props: ['background', 'classes', 'race', 'subrace', 'value'],
        created() {
            if (this.value.length > 0) {
                let selection = [];
                for (let selected of this.value) {
                    if (selected.origin_type === 'class') {
                        selection.push(selected);
                        if (this.classSelected.hasOwnProperty(selected.origin_id)) {
                            this.classSelected[selected.origin_id]++;
                        } else {
                            this.classSelected[selected.origin_id] = 1;
                        }
                    }
                }
                this.$set(this, 'selection', selection);
            }
        },
        data() {
            return {
                selection: [],
                classSelected: {}
            }
        },
        methods: {
            addSkillToClass(charClass, input) {
                let skill = this.classSkills[charClass.id].optional.find((item) => item.id == input);
                this.selection.splice(this.selection.length, 1, {
                    origin_type: 'class',
                    origin_id: charClass.id,
                    origin: charClass.name,
                    name: skill.name,
                    id: input,
                });
                if (this.classSelected.hasOwnProperty(charClass.id)) {
                    this.classSelected[charClass.id]++;
                } else {
                    this.classSelected[charClass.id] = 1;
                }
            },
            removeSkill(index) {
                let skill = this.selection[index];
                if (skill.origin_type == 'class') {
                    this.classSelected[skill.origin_id]--;
                }
                this.selection.splice(index, 1);
            }
        },
        computed: {
            ...mapState('Characters', {'availableClasses': 'classes', 'errors': 'errors'}),
            raceSkills() {
                let skills = [];
                if (this.race) {
                    skills = copy(this.race.proficiencies)
                        .filter((item) => item.type === 'Skills')
                        .map((item) => {
                            item.origin = this.race.name;
                            return item
                        });
                    if (this.subrace) {
                        let subraceSkills = copy(this.subrace.proficiencies)
                            .filter((item) => item.type === 'Skills')
                            .map((item) => {
                                item.origin = this.subrace.name;
                                return item
                            });
                        skills.concat(subraceSkills);
                    }
                }
                return skills;
            },
            classSkills() {
                if (Object.keys(this.availableClasses).length == 0) {
                    return {};
                }
                let skills = {};
                for (let chosenClass of this.classes) {
                    if (chosenClass.class_id) {
                        let charClass = copy(this.availableClasses[chosenClass.class_id]);
                        skills[chosenClass.class_id] = {
                            known: charClass.proficiencies.filter(item => item.type === 'Skills' && !item.optional),
                            optional: charClass.proficiencies.filter((item) => {
                                if (item.type === 'Skills' && item.optional) {
                                    let canChoose = true;
                                    let duplicateClassSkill = this.selection.find((skill) => item.id == skill.id);
                                    let duplicateRaceSkill = this.raceSkills.find((skill) => item.id == skill.id);
                                    let duplicateBackgroundSkill = this.backgroundSkills.find((skill) => item.id == skill.id);
                                    if (duplicateClassSkill || duplicateRaceSkill || duplicateBackgroundSkill) {
                                        canChoose = false;
                                    }
                                    return canChoose;
                                }
                                return false;
                            })
                        };
                    }
                }
                return skills;
            },
            backgroundSkills() {
                let skills = [];
                if (this.background) {
                    skills = copy(this.background.skills)
                        .map((item) => {
                            return {
                                id: item.id,
                                name: item.name,
                                origin: this.background.name
                            };
                        });
                }
                return skills;
            },
            known() {
                let known = [];
                if (this.raceSkills) {
                    known = known.concat(this.raceSkills);
                }
                if (this.classSkills) {
                    for (let classId in this.classSkills) {
                        known = known.concat(this.classSkills[classId].known);
                    }
                }
                if (this.backgroundSkills) {
                    known = known.concat(this.backgroundSkills);
                }
                return known;
            }
        },
        watch: {
            classes: {
                deep: true,
                handler() {
                    let selection = [];
                    for (let charClass of this.classes) {
                        let skills = this.selection.filter((item) => {
                            return item.origin_type == 'class' && item.origin_id == charClass.class_id;
                        });
                        selection.concat(skills);
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