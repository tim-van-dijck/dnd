<template>
    <div class="skills">
        <div class="uk-accordion-title"><h2>Skills</h2></div>
        <div class="uk-accordion-content">
            <div class="racial" v-if="raceSkills.length > 0">
                <h4>Racial skill proficiencies</h4>
                <div class="uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-small uk-grid-match" uk-grid>
                    <div v-for="skill in raceSkills">
                        <div class="uk-card uk-card-body uk-card-primary">
                            <div class="uk-card-title">{{ skill.name }} ({{ skill.origin }})</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="class-based">
                <h4>Class-based</h4>
                <div class="uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-small uk-grid-match" uk-grid>
                    <template v-for="skills in classSkills">
                        <div v-for="skill in skills.known">
                            <div class="uk-card uk-card-body uk-card-primary">
                                <div class="uk-card-title">{{ skill.name }}</div>
                            </div>
                        </div>
                    </template>
                    <div v-for="(skill, index) in (skillSelection || [])">
                        <div class="uk-card uk-card-body uk-card-primary">
                            <div class="uk-card-title">{{ skill.name }} ({{ skill.origin }})</div>
                            <button class="uk-text-danger uk-float-right uk-button uk-button-primary uk-button-round"
                                    @click.prevent="removeSkill(index)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="class" v-for="(charClass, classIndex) in classes"
                     v-if="charClass.class_id && availableClasses[charClass.class_id].skill_choices > (classSelected[charClass.class_id] || 0)">
                    <h5>{{ availableClasses[charClass.class_id].name }}</h5>
                    <div class="uk-margin">
                        <label :for="`skills_${classIndex}`">
                            Choose {{ availableClasses[charClass.class_id].skill_choices - (classSelected[charClass.class_id] || 0) }} skill proficiencies
                        </label>
                        <select :name="`skills_${classIndex}`" :id="`skills_${classIndex}`" class="uk-select"
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
        props: ['classes', 'race', 'subrace', 'value'],
        created() {
            this.$set(this, 'skillSelection', this.value || []);
        },
        data() {
            return {
                skillSelection: [],
                classSelected: {}
            }
        },
        methods: {
            addSkillToClass(charClass, input) {
                let skill = this.classSkills[charClass.id].optional.find((item) => item.id == input);
                this.skillSelection.splice(this.skillSelection.length, 1, {
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
                let skill = this.skillSelection[index];
                if (skill.origin_type == 'class') {
                    this.classSelected[skill.origin_id]--;
                }
                this.skillSelection.splice(index, 1);
            }
        },
        computed: {
            ...mapState('Characters', {'availableClasses': 'classes'}),
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
                let skills = {};
                for (let chosenClass of this.classes) {
                    if (chosenClass.class_id) {
                        let charClass = copy(this.availableClasses[chosenClass.class_id]);
                        skills[chosenClass.class_id] = {
                            known: charClass.proficiencies.filter(item => item.type === 'Skills' && !item.optional),
                            optional: charClass.proficiencies.filter((item) => {
                                if (item.type === 'Skills' && item.optional) {
                                    let canChoose = true;
                                    for (let classId in this.skillSelection) {
                                        let duplicateClassSkill = this.skillSelection.find((skill) => item.id == skill.id);
                                        let duplicateRaceSkill = this.raceSkills.find((skill) => item.id == skill.id)
                                        if (duplicateClassSkill || duplicateRaceSkill) {
                                            canChoose = false;
                                        }
                                    }
                                    return canChoose;
                                }
                                return false;
                            })
                        };
                    }
                }
                return skills;
            }
        },
        watch: {
            classes: {
                deep: true,
                handler() {
                    let selection = [];
                    for (let charClass of this.classes) {
                        let skills = this.skillSelection.filter((item) => {
                            return item.origin_type == 'class' && item.origin_id == charClass.class_id;
                        });
                        selection.concat(skills);
                    }
                    this.$set(this, 'skillSelection', selection);
                }

            },
            skillSelection: {
                deep: true,
                handler() {
                    this.$emit('input', this.skillSelection);
                }
            }
        }
    }
</script>