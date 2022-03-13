<template>
    <div class="player-characters">
        <router-link class="uk-button uk-button-primary" :to="{name: 'character-create', params: {type: 'npc'}}">
            <i class="fas fa-plus"></i> Add NPC
        </router-link>
        <table v-if="characters != null && characters.data.length > 0" class="uk-table uk-table-divider">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Level</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="character in characters">
                    <td>{{ character.name }}</td>
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
        name: "NPCOverview",
        created() {
            this.$store.dispatch('Characters/load', 'npc');
            this.$store.dispatch('Characters/loadRaces');
        },
        computed: {
            ...mapState('Characters', ['characters', 'races'])
        }
    }
</script>