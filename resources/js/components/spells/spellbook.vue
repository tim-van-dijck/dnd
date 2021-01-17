<template>
    <div id="spellbook">
        <div class="filters">
            Level:
            <select class="uk-select uk-width-auto" v-model="filters.level">
                <option :value="0">Cantrips</option>
                <option v-for="level in 9" :value="level">{{ level }}</option>
            </select>
            <div class="uk-button-group">
                <button :class="{'uk-button-primary': filters.ritual, 'uk-button-danger': filters.ritual === false}"
                        class="uk-button"
                        @click.prevent="toggleFilter('ritual')">
                    Ritual
                </button>
                <button :class="{'uk-button-primary': filters.concentration, 'uk-button-danger': filters.concentration === false}"
                        class="uk-button"
                        @click.prevent="toggleFilter('concentration')">
                    Concentration
                </button>
            </div>
            Search:
            <input type="text" name="query" class="uk-input uk-width-auto" v-model="filters.query">
            <a v-if="filters.query.length > 0" class="uk-link-text" href="#" @click.prevent="filters.query = ''">
                <i class="fas fa-times"></i>
            </a>
        </div>
        <div uk-grid>
            <div id="spell-list" class="uk-width-1-4">
                <h3>Spells</h3>
                <ul class="uk-nav uk-nav-default" uk-overflow-auto>
                    <li :class="{'uk-active': spell && item.id === spell.id}" v-for="item in relevantSpells">
                        <a href="#" @click.prevent="spell = item">
                            {{ item.name }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="uk-width-3-4" v-if="spell">
                <h3>
                    {{ spell.name }}
                    <span v-if="spell.level > 0" class="uk-text-small">(level {{ spell.level }} {{ spell.school }} spell)</span>
                    <span v-else class="uk-text-small">({{ spell.school }} cantrip)</span>
                </h3>
                <ul class="uk-list">
                    <li><b>Casting Time:</b> {{ spell.casting_time }}</li>
                    <li><b>Range:</b> {{ spell.range }}</li>
                    <li><b>Components:</b> {{ spell.components }}</li>
                    <li><b>Duration:</b> {{ spell.duration }}</li>
                    <li>
                        <b>Concentration:</b>
                        <i :class="`fas fa-${spell.concentration ? 'check' : 'times'} uk-text-${spell.concentration ? 'success' : 'danger'}`"></i>
                    </li>
                    <li>
                        <b>Ritual:</b>
                        <i :class="`fas fa-${spell.ritual ? 'check' : 'times'} uk-text-${spell.ritual ? 'success' : 'danger'}`"></i>
                    </li>
                    <li><b>Description:</b></li>
                </ul>
                <div uk-overflow-auto v-html="spell.description"></div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";
    export default {
        name: "spellbook",
        created() {
            this.$store.dispatch('Spells/load');
        },
        data() {
            return {
                spell: null,
                filters: {
                    level: 0,
                    ritual: null,
                    concentration: null,
                    query: ''
                }
            }
        },
        methods: {
            toggleFilter(filter) {
                if (['ritual', 'concentration'].includes(filter)) {
                    switch (this.filters[filter]) {
                        case null:
                            this.filters[filter] = true;
                            break;
                        case true:
                            this.filters[filter] = false;
                            break;
                        case false:
                        default:
                            this.filters[filter] = null;
                            break;
                    }
                }
            }
        },
        computed: {
            ...mapState('Spells', ['spells']),
            relevantSpells() {
                if (this.spells == null) {
                    return [];
                }
                return this.spells
                    .filter((spell) => {
                        if ((this.filters.query || '').length > 0) {
                            return spell.name.toLowerCase().includes(this.filters.query.toLowerCase());
                        }

                        let visible = spell.level === this.filters.level;
                        if (this.filters.ritual != null) {
                            visible = visible && spell.ritual == this.filters.ritual;
                        }
                        if (this.filters.concentration != null) {
                            visible = visible && spell.concentration == this.filters.concentration;
                        }
                        return visible;
                    })
                    .sort((a, b) => {
                        return (a.name > b.name) ? 1 : ((b.name > a.name) ? -1 : 0)
                    });
            }
        },
        watch: {
            filters: {
                deep: true,
                handler() {
                    if (!this.relevantSpells.includes(this.spell)) {
                        this.$set(this, 'spell', null);
                    }
                }
            }
        }
    }
</script>