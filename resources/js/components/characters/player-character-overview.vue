<template>
    <div class="player-characters">
        <router-link class="uk-button uk-button-primary" :to="{name: 'character-create', params: {type: 'player'}}">
            <i class="fas fa-plus"></i> Add character
        </router-link>
        <table class="uk-table uk-table-divider" v-if="characters != null && characters.data.length > 0">
            <thead>
                <tr>
                    <th class="uk-table-shrink"></th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Level</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="character in characters.data">
                    <td>
                        <router-link :to="{name: 'character-edit', params: {id: character.id}}">
                            <i class="fas fa-edit"></i>
                        </router-link>
                    </td>
                    <td>{{ character.name }} ({{ character.title }})</td>
                    <td>{{ character.class }}</td>
                    <td>{{ character.level }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <p v-else class="uk-text-center">
            <i v-if="characters == null" class="fas fa-sync fa-spin fa-2x"></i>
            <span v-else>
                No characters found
            </span>
        </p>
    </div>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        name: "PlayerCharacterOverview",
        created() {
            this.$store.dispatch('Characters/loadCharacters', {campaign_id: 1, type: 'player'});
            this.$store.dispatch('Characters/loadRaces');
            this.$store.dispatch('Messages/success', 'This is a success message!');
        },
        computed: {
            ...mapState('Characters', ['characters', 'races'])
        }
    }
</script>