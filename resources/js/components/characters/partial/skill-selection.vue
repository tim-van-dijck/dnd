<template>
    <div class="skills">
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
            <div class="class" v-for="(charClass, classIndex) in classes" v-if="charClass.class_id != null">
                <h5>{{ availableClasses[charClass.class_id].name }}</h5>
                <div class="uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-small uk-grid-match" uk-grid>
                    <div v-for="skill in classSkills[charClass.class_id].known">
                        <div class="uk-card uk-card-body uk-card-primary">
                            <div class="uk-card-title">{{ skill.name }}</div>
                        </div>
                    </div>
                    <div v-for="(skill, index) in (skillSelection[charClass.class_id] || [])">
                        <div class="uk-card uk-card-body uk-card-primary">
                            <div class="uk-card-title">{{ skill.name }} ({{ skill.origin }})</div>
                            <button class="uk-text-danger uk-float-right uk-button uk-button-primary uk-button-round"
                                    @click.prevent="skillSelection[charClass.class_id].splice(index, 1)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="uk-margin" v-if="availableClasses[charClass.class_id].skill_choices > (skillSelection[charClass.class_id] || []).length">
                    <label :for="`skills_${classIndex}`">
                        Choose {{ availableClasses[charClass.class_id].skill_choices - (skillSelection[charClass.class_id] || []).length }} skill proficiencies
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
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "skill-selection",
        props: ['classes', 'race', 'subrace', 'value'],
        created() {
            this.$set(this, 'skillSelection', this.value || {});
        },
        data() {
            return {
                skillSelection: {},
            }
        },
        methods: {
            addSkillToClass(charClass, input) {
                if (!this.skillSelection.hasOwnProperty(charClass.id)) {
                    this.$set(this.skillSelection, charClass.id, []);
                }
                let classSelection = this.skillSelection[charClass.id];
                let skill = this.classSkills[charClass.id].optional.find((item) => item.id == input);
                classSelection.splice(classSelection.length, 1, {origin: charClass.name, name: skill.name, id: input});
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
                                        let duplicateClassSkill = this.skillSelection[classId].find(skill => item.id == skill.id);
                                        let duplicateRaceSkill = this.raceSkills.find(skill => item.id == skill.id)
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
                    let selection = {}
                    for (let charClass of this.classes) {
                        selection[charClass.class_id] = this.skillSelection[charClass.class_id] || [];
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