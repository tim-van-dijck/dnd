<template>
    <div class="tools" v-if="hasTools">
        <div class="uk-accordion-title"><h2>Tools</h2></div>
        <div class="uk-accordion-content">
            <div class="racial" v-if="raceTools.length > 0">
                <h4>Racial tool proficiencies</h4>
                <div class="uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-small uk-grid-match" uk-grid>
                    <div v-for="tool in raceTools">
                        <div class="uk-card uk-card-body uk-card-primary">
                            <div class="uk-card-title">{{ tool.name }} ({{ tool.origin }})</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="class-based" v-if="classToolCount > 0">
                <h4>Class-based</h4>
                <div class="uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-small uk-grid-match" uk-grid>
                    <template v-for="tools in classTools">
                        <div v-for="tool in tools.known">
                            <div class="uk-card uk-card-body uk-card-primary">
                                <div class="uk-card-title">{{ tool.name }}</div>
                            </div>
                        </div>
                    </template>
                    <div v-for="(tool, index) in (selection[charClass.class_id] || [])">
                        <div class="uk-card uk-card-body uk-card-primary">
                            <div class="uk-card-title">{{ tool.name }} ({{ tool.origin }})</div>
                            <button class="uk-text-danger uk-float-right uk-button uk-button-primary uk-button-round"
                                    @click.prevent="selection[charClass.class_id].splice(index, 1)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
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
        props: ['race', 'subrace', 'classes', 'value'],
        created() {
            this.$set(this, 'selection', this.value || {});
        },
        data() {
            return {
                selection: []
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
                let tools = {};
                for (let chosenClass of this.classes) {
                    if (chosenClass.class_id) {
                        let charClass = copy(this.availableClasses[chosenClass.class_id]);
                        tools[chosenClass.class_id] = {
                            known: charClass.proficiencies.filter(item => item.type === 'Tools' && !item.optional),
                            optional: charClass.proficiencies.filter((item) => {
                                if (item.type === 'Tools' && item.optional) {
                                    let canChoose = true;
                                    for (let classId in this.selection) {
                                        let duplicateClassTool = this.selection.find(tool => item.id == tool.id);
                                        let duplicateRaceTool = this.raceTools.find(tool => item.id == tool.id)
                                        if (duplicateClassTool || duplicateRaceTool) {
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
                return tools;
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