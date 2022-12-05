<template>
    <div>
        <button uk-toggle="target: #spellbook-modal" class="uk-button uk-button-secondary" type="button">
            <i class="fas fa-book"></i> Spellbook
        </button>
        <div class="uk-margin spells-known">
            <template v-if="(spells.cantrips.length + spells.spells.length) > 0">
                <div v-if="level.spells.length > 0" class="spell-level" v-for="level in levels">
                    <h3>{{ level.title }}</h3>
                    <div class="uk-child-width-1-2@m uk-grid-small uk-grid-match" uk-grid>
                        <div v-for="spell in level.spells">
                            <div class="uk-card uk-card-body uk-card-primary">
                                <div class="uk-card-title">{{ spell.name }}</div>
                                <p>({{ spell.school }})</p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <div class="uk-alert-primary" uk-alert>
                Character doesn't have any known spells
            </div>
        </div>
    </div>
</template>

<script>

import { useLevels } from './player-character-spells-tab.state'

export default {
    name: 'player-character-spells-tab',
    props: ['spells'],
    setup(props) {
        const levels = useLevels(props.spells)

        return { spells: props.spells, levels }
    }
}
</script>