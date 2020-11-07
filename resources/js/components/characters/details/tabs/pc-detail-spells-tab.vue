<template>
    <div>
        <pc-spellbook></pc-spellbook>
        <div class="uk-width-2-3@s spells-known" v-if="spells.cantrips.length > 0 || spells.spells.length > 0">
            <div v-if="level.spells.length > 0" class="spell-level" v-for="level in levels">
                <h3>{{ level.title }}</h3>
                <div class="uk-child-width-1-2@m uk-grid-small uk-grid-match" uk-grid>
                    <div v-for="spell in level.spells">
                        <div class="uk-card uk-card-body uk-card-primary">
                            <div class="uk-card-title">{{ spell.name }}</div>
                            <p>({{spell.school}})</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PcSpellbook from "../../create/partial/pc-spellbook";
    export default {
        name: "pc-detail-spells-tab",
        components: {PcSpellbook},
        props: ['spells'],
        created() {
            this.$store.dispatch('Spells/load');
        },
        computed: {
            levels() {
                let levels = {};
                if (this.spells.cantrips.length > 0) {
                    levels[0] = {title: 'Cantrips', spells: this.spells.cantrips};
                }
                for (let level = 1; level < 10; level++) {
                    let spells = this.spells.spells.filter(item => item.level == level);
                    if (spells.length > 0) {
                        levels[level] = {title: this.getTitle(level), spells};
                    }
                }
                return levels;
            }
        },
        getTitle(level) {
            switch (level) {
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
    }
</script>