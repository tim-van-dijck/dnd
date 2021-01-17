<template>
    <div class="tools" v-if="hasTools">
        <div class="uk-accordion-title"><h2>Tools</h2></div>
        <div class="uk-accordion-content">
            <div class="uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-small uk-grid-match" uk-grid>
                <div v-for="tool in known">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ tool.name }} ({{ tool.origin }})</div>
                    </div>
                </div>
                <div v-for="(tool, index) in (selection || [])">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ tool.name }} ({{ tool.origin }})</div>
                        <button class="uk-text-danger uk-float-right uk-button uk-button-primary uk-button-round"
                                @click.prevent="removeTool(index)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="class-based" v-if="Object.keys(availableClasses).length > 0 && classToolCount > 0">
                <div class="class" v-for="(charClass, classIndex) in classes"
                     v-if="charClass.class_id != null && availableClasses[charClass.class_id].tool_choices > (classSelected[charClass.class_id] || 0)">
                    <h4>{{ availableClasses[charClass.class_id].name }}</h4>
                    <div class="uk-margin">
                        <label :for="`tools_${classIndex}`">
                            Choose {{ availableClasses[charClass.class_id].tool_choices - (selection || []).length }} tool proficiencies
                        </label>
                        <select :name="`tools_${classIndex}`" :id="`tools_${classIndex}`" class="uk-select"
                                @input="addToolToClass(availableClasses[charClass.class_id], $event.target.value); $event.target.value = ''">
                            <option :value="null">- Make a choice -</option>
                            <option v-for="tool in classTools[charClass.class_id].optional" :value="tool.id"
                                    :disabled="classTools[charClass.class_id].known.includes(tool.id)">
                                {{ tool.name }}
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
        name: "tool-selection",
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
            addToolToClass(charClass, input) {
                let tool = this.classTools[charClass.id].optional.find((item) => item.id == input);
                this.selection.splice(this.selection.length, 1, {
                    origin_type: 'class',
                    origin_id: charClass.id,
                    origin: charClass.name,
                    name: tool.name,
                    id: input
                });
                if (this.classSelected.hasOwnProperty(charClass.id)) {
                    this.classSelected[charClass.id]++;
                } else {
                    this.classSelected[charClass.id] = 1;
                }
            },
            removeTool(index) {
                let tool = this.selection[index];
                if (tool.origin_type == 'class') {
                    this.classSelected[tool.origin_id]--;
                }
                this.selection.splice(index, 1);
            }
        },
        computed: {
            ...mapState('Characters', {'availableClasses': 'classes'}),
            raceTools() {
                let tools = [];
                if (this.race) {
                    tools = copy(this.race.proficiencies)
                        .filter((item) => item.type === 'Tools')
                        .map((item) => {
                            item.origin = this.race.name;
                            return item
                        });
                    if (this.subrace) {
                        let subraceTools = copy(this.subrace.proficiencies)
                            .filter((item) => item.type === 'Tools')
                            .map((item) => {
                                item.origin = this.subrace.name;
                                return item
                            });
                        tools.concat(subraceTools);
                    }
                }
                return tools;
            },
            classTools() {
                if (Object.keys(this.availableClasses).length == 0) {
                    return {};
                }
                let tools = {};
                for (let chosenClass of this.classes) {
                    if (chosenClass.class_id) {
                        let charClass = copy(this.availableClasses[chosenClass.class_id]);
                        tools[chosenClass.class_id] = {
                            known: charClass.proficiencies.filter(item => item.type === 'Tools' && !item.optional),
                            optional: charClass.proficiencies.filter((item) => {
                                if (item.type === 'Tools' && item.optional) {
                                    let canChoose = true;
                                    let duplicateClassTool = this.selection.find(tool => item.id == tool.id);
                                    let duplicateRaceTool = this.raceTools.find(tool => item.id == tool.id)
                                    if (duplicateClassTool || duplicateRaceTool) {
                                        canChoose = false;
                                    }
                                    return canChoose;
                                }
                                return false;
                            })
                        };
                    }
                }
                return tools;
            },
            backgroundTools() {
                if (this.background) {
                    return this.background.tools.filter((item) => item.type === 'Tools');
                }
                return [];
            },
            known() {
                let known = [];
                if (this.raceTools) {
                    known.concat(this.raceTools.known);
                }
                if (this.classSkills) {
                    for (let classId in this.classTools) {
                        known.concat(this.classTools[classId].known);
                    }
                }
                if (this.backgroundTools) {
                    known.concat(this.backgroundTools);
                }
                return known;
            },
            hasTools() {
                return this.raceTools.length + this.classToolCount;
            },
            classToolCount() {
                let count = 0;
                for (let classId in this.classTools) {
                    count += this.classTools[classId].known.length;
                    count += this.classTools[classId].optional.length;
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
                        let skills = this.selection.filter((item) => {
                            return item.origin_type == 'class' && item.origin_id == charClass.class_id;
                        });
                        selection.concat(skills);
                    }
                    this.$set(this, 'selection', selection);
                }
            }
        }
    }
</script>