<template>
    <div id="spell-tab">
        <div class="spells-known">
            <h2>Spells known</h2>
            <div v-if="level.spells.length > 0 || (index == 0 && raceCantrip)" class="spell-level" v-for="(level, index) in spellsKnown">
                <h3>{{ level.title }}</h3>
                <div class="uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-small uk-grid-match" uk-grid>
                    <div v-for="(spell, index) in level.spells">
                        <div class="uk-card uk-card-body uk-card-primary">
                            <div class="uk-card-title">{{ spell.name }}</div>
                            <button v-if="selection[spell.level > 0 ? 'spells' : 'cantrips'].find(item => spell.id == item.id)"
                                    class="uk-text-danger uk-float-right uk-button uk-button-primary uk-button-round"
                                    @click.prevent="removeSpell(spell.level > 0 ? 'spells' : 'cantrips', spell.origin_type, spell.origin_id, spell.id)">
                                <i class="fas fa-trash"></i>
                            </button>
                            <p>({{spell.origin_name}})</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h2>Spell selection</h2>
        <div v-if="Object.keys(spells.items).length > 0 || (level == 0 && raceCantrips.length > 0)"
             v-for="(spells, level) in classSpells">
            <h3>{{ spells.title }}</h3>
            <div :class="{'uk-form-horizontal': chosenClasses.length > 1 || raceCantrips.length > 0}">
                <div class="uk-margin" v-if="level == 0 && raceCantrips.length > 0">
                    <label for="subrace_cantrip" class="uk-form-label">
                        {{ subrace.name }}
                    </label>
                    <div class="uk-form-controls">
                        <select id="subrace_cantrip" class="uk-select" :disabled="raceCantrip != null"
                                @input="selectRaceCantrip($event.target.value); $event.target.value = ''">
                            <option value="">
                                - Make a choice -
                            </option>
                            <option v-for="spell in raceCantrips" :value="spell.id">
                                {{ spell.name }} ({{ spell.school }})
                            </option>
                        </select>
                    </div>
                </div>
                <div class="uk-margin" v-if="classSpells[level].items[chosenClass.id]" v-for="chosenClass in chosenClasses">
                    <label v-if="chosenClasses.length > 1 || raceCantrips.length > 0"
                           :for="`spells_${level}_${chosenClass.id}`" class="uk-form-label">
                        {{ chosenClass.name }}
                    </label>
                    <div class="uk-form-controls">
                        <select :id="`spells_${level}_${chosenClass.id}`" class="uk-select"
                                :disabled="chosenClass.currentLevel[(level > 0) ? 'spells_known' : 'cantrips_known'] <= selection[level > 0 ? 'spells' : 'cantrips'].length"
                                @input="selectSpell(level, chosenClass, $event.target.value); $event.target.value = ''">
                            <option value="">
                                - Make a choice ({{ `${chosenClass.currentLevel[(level > 0) ? 'spells_known' : 'cantrips_known'] - selection[level > 0 ? 'spells' : 'cantrips'].length} remaining` }}) -
                            </option>
                            <option v-for="spell in classSpells[level].items[chosenClass.id]" :value="spell.id">
                                {{ spell.name }} ({{ spell.school }})
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
        name: "pc-form-spell-tab",
        props: ['info', 'characterClasses', 'value'],
        created() {
            this.$store.dispatch('Spells/load');
        },
        data() {
            return {
                selection: {
                    'cantrips': [],
                    'spells': [],
                },
                classSelected: {}
            }
        },
        methods: {
            selectSpell(level, charClass, id) {
                let spell = this.classSpells[level].items[charClass.id].find(item => item.id == id);
                if (spell) {
                    let type = level > 0 ? 'spells' : 'cantrips'
                    this.selection[type].push({
                        id: spell.id,
                        name: spell.name,
                        level: spell.level,
                        school: spell.school,
                        origin_id: charClass.id,
                        origin_name: charClass.name,
                        origin_type: 'Class'
                    });
                    if (!this.classSelected.hasOwnProperty(charClass.id)) {
                        this.classSelected[charClass.id] = {};
                    }
                    if (!this.classSelected[charClass.id].hasOwnProperty(type)) {
                        this.classSelected[charClass.id][type] = 0;
                    }
                    this.classSelected[charClass.id][type]++;
                }
            },
            removeSpell(type, originType, classId, id)  {
                let index = this.selection[type].findIndex(item => item.id == id);
                this.selection[type].splice(index, 1);
                if (originType == 'Class') {
                    this.classSelected[classId][type]--;
                }
            },
            selectRaceCantrip(id) {
                this.removeRaceCantrip();
                let cantrip = this.raceCantrips.find(item => item.id == id);
                if (this.subrace.traits.find(item => item.name.includes('Cantrip'))) {
                    cantrip.origin_id = this.info.subrace_id;
                    cantrip.origin_name = this.subrace.name;
                    cantrip.origin_type = 'Subrace';
                } else {
                    cantrip.origin_id = this.info.race_id;
                    cantrip.origin_name = this.races[this.info.race_id].name;
                    cantrip.origin_type = 'Race';
                }
                this.selection.cantrips.push(cantrip);
            },
            removeRaceCantrip() {
                if (this.raceCantrip) {
                    let existingIndex = this.selection.cantrips.findIndex(item => item.level == 0 && item.origin_type == 'Race');
                    this.selection.cantrips.splice(existingIndex, 1);
                }
            },
            getTitle(level) {
                switch (level) {
                    case 0:
                        return 'Cantrips';
                    case 1:
                        return '1st level';
                    case 2:
                        return '2nd level';
                    case 3:
                        return '3rd level';
                    default:
                        return `${level}th level`;
                }
            }
        },
        computed: {
            ...mapState('Characters', ['races', 'classes']),
            ...mapState('Spells', ['spells', 'multiclassTable']),
            subrace() {
                if (this.info.race_id && this.info.subrace_id) {
                    return this.races[this.info.race_id].subraces.find(item => item.id == this.info.subrace_id);
                }
                return null;
            },
            raceCantrips() {
                let spells = [];
                if (this.info.race_id > 0) {
                    let wizardSpells = [];
                    for (let index in this.classes) {
                        if (this.classes[index].name === 'Wizard') {
                            wizardSpells = this.classes[index].spells.filter((item) => item.level === 0) || [];
                        }
                    }
                    let race = this.races[this.info.race_id];
                    if (race.traits.find(item => item.name.includes('Cantrip')) != null) {
                        spells = wizardSpells.map(spell => {
                            spell.origin_id = race.id;
                            spell.origin_type = 'race';
                            spell.origin_name = race.name;
                            return spell;
                        });
                    }
                    if (this.subrace && this.subrace.traits.find(item => item.name.includes('Cantrip')) != null) {
                        spells = wizardSpells.map(spell => {
                            spell.origin_id = this.subrace.id;
                            spell.origin_type = 'subrace';
                            spell.origin_name = this.subrace.name;
                            return spell;
                        });
                    }
                }
                return spells;
            },
            raceCantrip() {
                return this.selection.cantrips.find((item) => {
                    return item.level == 0 && ['Race', 'Subrace'].includes(item.origin_type);
                });
            },
            classSpells() {
                let classSpells = {};
                for (let level = 0; level < 10; level++) {
                    let levelSpells = {
                        title: this.getTitle(level),
                        items: []
                    }
                    let type = level > 0 ? 'spells' : 'cantrips';
                    for (let charClass of this.chosenClasses) {
                        let canSelect = false;
                        if (type == 'cantrips') {
                            canSelect = (level == 0 && charClass.currentLevel['cantrips_known'] > 0)
                        } else {
                            canSelect = this.spellsAvailable.maxSpellLevel >= level && charClass.currentLevel[`${type}_known`] > 0;
                        }
                        if (canSelect) {
                            levelSpells.items[charClass.id] = this.classes[charClass.id].spells
                                .filter(item => {
                                    let selection = this.selection[level > 0 ? 'spells' : 'cantrips'].find(selected => selected.id == item.id);
                                    return selection == null && item.level === level;
                                })
                                .sort((a, b) =>  a.name < b.name ? -1 : 1);
                        }
                    }
                    classSpells[level] = levelSpells;
                }
                return classSpells;
            },
            spellsKnown() {
                let spells = {};
                for (let level = 0; level < 10; level++) {
                    let visible = true;
                    for (let charClass of this.chosenClasses) {
                        if (level > 0 && charClass.currentLevel.maxSpellLevel < level) {
                            visible = false;
                        }
                    }
                    if (visible) {
                        spells[level] = {title: this.getTitle(level), spells: []};
                    }
                }
                for (let type in this.selection) {
                    for (let spell of this.selection[type]) {
                        if (spells.hasOwnProperty(spell.level)) {
                            spells[spell.level].spells.push(spell);
                        }
                    }
                }
                return spells;
            },
            chosenClasses() {
                let chosenClasses = [];
                for (let charClass of this.characterClasses) {
                    if (charClass.class_id) {
                        let chosenClass = JSON.parse(JSON.stringify(this.classes[charClass.class_id]));
                        let currentLevel = chosenClass.levels.find(lvl => lvl.level == charClass.level);
                        if (currentLevel) {
                            chosenClass.currentLevel = currentLevel;
                        } else {
                            chosenClass.currentLevel = {};
                        }
                        chosenClasses.push(chosenClass);
                    }
                }
                return chosenClasses;
            },
            classLevels() {
                let levels = {};
                for (let charClass of this.characterClasses) {
                    if (charClass.class_id) {
                        levels[charClass.class_id] = this.classes[charClass.class_id].levels[charClass.level];
                    }
                }
                return levels;
            },
            spellsAvailable() {
                let casterClasses = [];
                for (let charClass of this.chosenClasses) {
                    if (charClass.currentLevel.spells_known > 0) {
                        casterClasses.push(charClass);
                    }
                }
                if (casterClasses.length === 0) {
                    return {maxSpellLevel: 0};
                }
                let level;
                if (casterClasses.length > 1) {
                    let spellSlotsLevel = 0;
                    for (let charClass of casterClasses) {
                        let level = (['paladin', 'ranger'].includes(charClass.name) ? charClass.currentLevel.level / 2 : charClass.currentLevel.level)
                        spellSlotsLevel += level;
                    }
                    level = this.multiclassTable[spellSlotsLevel];
                } else {
                    level = casterClasses[0].currentLevel;
                }
                for (let i = 1; i <= 9; i++) {
                    if (level[`spell_slots_level_${i}`] > 0) {
                        level.maxSpellLevel = i;
                    }
                }
                return level;
            }
        },
        watch: {
            selection: {
                deep: true,
                handler(value) {
                    this.$emit('input', value);
                }
            }
        }
    }
</script>